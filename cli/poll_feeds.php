<?php
declare(strict_types=1);

/**
 * CLI RSS Feed Poller — Signal Capture Engine
 *
 * Fetches active RSS sources, matches items against bilingual keyword rules,
 * and inserts matching signals into the rumours table for triage.
 *
 * Usage:
 *   php cli/poll_feeds.php                  # normal run (respects poll_interval)
 *   php cli/poll_feeds.php --force          # ignore poll_interval
 *   php cli/poll_feeds.php --source-id=3   # poll single source
 *
 * Cron (every 6 hours):
 *   0 */6 * * * cd /path/to/erid-amrafrica && php cli/poll_feeds.php >> storage/logs/rss.log 2>&1
 */

define('APP_ROOT', dirname(__DIR__));

// Bootstrap (same autoloader as index.php, without session/routing)
spl_autoload_register(function (string $class): void {
    if (!str_starts_with($class, 'App\\')) return;
    $path = APP_ROOT . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($path)) require $path;
});

use App\Core\Config;
use App\Core\Database;

Config::load(APP_ROOT);

// ---- Parse CLI arguments ----
$force    = in_array('--force', $argv ?? [], true);
$sourceId = null;
foreach ($argv ?? [] as $arg) {
    if (str_starts_with($arg, '--source-id=')) {
        $sourceId = (int) substr($arg, 12);
    }
}

$log = function (string $msg): void {
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
};

$log('RSS poll started' . ($force ? ' (forced)' : ''));

// ---- 1. Fetch active sources due for polling ----
$where  = 'is_active = 1';
$params = [];

if ($sourceId) {
    $where .= ' AND id = ?';
    $params[] = $sourceId;
}
if (!$force) {
    $where .= ' AND (last_polled_at IS NULL OR last_polled_at < DATE_SUB(NOW(), INTERVAL poll_interval MINUTE))';
}

$sources = Database::all("SELECT * FROM rss_sources WHERE {$where}", $params);
$log(count($sources) . ' source(s) due for polling');

// ---- 2. Load active keyword rules ----
$rules = Database::all('SELECT * FROM keyword_rules WHERE is_active = 1');
$log(count($rules) . ' keyword rule(s) active');

$totalInserted = 0;

foreach ($sources as $src) {
    $log("Polling: {$src['name']} ({$src['url']})");
    $inserted = 0;

    try {
        // Fetch feed with timeout
        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 15,
                'user_agent' => 'ERID-AMRAfrica Signal Capture/1.0',
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);
        $xml = @file_get_contents($src['url'], false, $ctx);

        if ($xml === false) {
            throw new \RuntimeException('Failed to fetch feed');
        }

        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml);
        if ($feed === false) {
            $xmlErrors = array_map(fn($e) => trim($e->message), libxml_get_errors());
            libxml_clear_errors();
            throw new \RuntimeException('XML parse error: ' . implode('; ', array_slice($xmlErrors, 0, 3)));
        }

        // ---- Normalize items (RSS 2.0 vs Atom) ----
        $items = [];

        if (isset($feed->channel->item)) {
            // RSS 2.0
            foreach ($feed->channel->item as $item) {
                $items[] = [
                    'title' => (string) ($item->title ?? ''),
                    'link'  => (string) ($item->link ?? ''),
                    'desc'  => (string) ($item->description ?? ''),
                    'date'  => (string) ($item->pubDate ?? ''),
                ];
            }
        } elseif (isset($feed->entry)) {
            // Atom
            foreach ($feed->entry as $entry) {
                $link = '';
                if (isset($entry->link['href'])) {
                    $link = (string) $entry->link['href'];
                }
                $items[] = [
                    'title' => (string) ($entry->title ?? ''),
                    'link'  => $link,
                    'desc'  => (string) ($entry->summary ?? $entry->content ?? ''),
                    'date'  => (string) ($entry->updated ?? $entry->published ?? ''),
                ];
            }
        }

        $log("  Found " . count($items) . " items");

        foreach ($items as $item) {
            if (empty($item['link'])) continue;

            // Dedup check by source_url
            $exists = Database::one('SELECT id FROM rumours WHERE source_url = ?', [$item['link']]);
            if ($exists) continue;

            // ---- Keyword matching ----
            $text = mb_strtolower($item['title'] . ' ' . strip_tags(html_entity_decode($item['desc'])));
            $matchedKeywords = [];
            $maxRisk = 0;
            $matchedSector = 'unknown';

            foreach ($rules as $rule) {
                $keywords = array_map('trim', explode(',', mb_strtolower($rule['pattern'])));
                foreach ($keywords as $kw) {
                    if ($kw !== '' && mb_strpos($text, $kw) !== false) {
                        $matchedKeywords[] = $kw;
                        if ((int) $rule['risk_weight'] > $maxRisk) {
                            $maxRisk = (int) $rule['risk_weight'];
                            if ($rule['category'] && $rule['category'] !== 'unknown') {
                                $matchedSector = $rule['category'];
                            }
                        }
                        break; // one keyword per rule is enough
                    }
                }
            }

            // Only insert if at least one keyword matched
            if (empty($matchedKeywords)) continue;

            // Build signal text
            $signal = $item['title'];
            if ($item['desc']) {
                $cleanDesc = strip_tags(html_entity_decode($item['desc']));
                $signal .= "\n\n" . $cleanDesc;
            }
            if (mb_strlen($signal) > 2000) {
                $signal = mb_substr($signal, 0, 2000) . '...';
            }

            // Validate sector against ENUM
            $validSectors = ['human', 'animal', 'environment', 'agriculture', 'pharma', 'unknown'];
            if (!in_array($matchedSector, $validSectors, true)) {
                $matchedSector = 'unknown';
            }

            Database::exec(
                'INSERT IGNORE INTO rumours
                    (source_channel, is_anonymous, raw_signal, source_url, sector,
                     triage_status, risk_score, nlp_keywords, country, region)
                 VALUES (?, 1, ?, ?, ?, ?, ?, ?, NULL, NULL)',
                [
                    'rss',
                    $signal,
                    $item['link'],
                    $matchedSector,
                    'new',
                    min($maxRisk, 100),
                    implode(', ', array_unique(array_slice($matchedKeywords, 0, 10))),
                ]
            );
            $inserted++;
        }

        // Update source metadata
        Database::exec(
            'UPDATE rss_sources SET last_polled_at = NOW(), last_error = NULL,
                    items_ingested = items_ingested + ? WHERE id = ?',
            [$inserted, (int) $src['id']]
        );
        $log("  Inserted {$inserted} new signal(s)");

    } catch (\Throwable $e) {
        $errMsg = mb_substr($e->getMessage(), 0, 500);
        Database::exec(
            'UPDATE rss_sources SET last_polled_at = NOW(), last_error = ? WHERE id = ?',
            [$errMsg, (int) $src['id']]
        );
        $log("  ERROR: {$errMsg}");
    }

    $totalInserted += $inserted;
}

$log("Poll complete. Total new signals: {$totalInserted}");
