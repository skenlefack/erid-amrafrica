<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Mailer;
use App\Core\Audit;
use App\Core\Lang;

/**
 * Portails d'intake : Data Analytics, Expert Advisory, et les 3 piliers.
 * Chaque soumission :
 *   1. est enregistrée dans le CRM (table leads) avec un routing_tag contextuel ;
 *   2. déclenche un e-mail de triage automatique white-labellé (48 h) ;
 *   3. est journalisée dans l'audit.
 */
final class IntakeController extends Controller
{
    /** Correspondance pilier → type d'intake (routage contextuel du brief §8.1). */
    private const PILLAR_MAP = [
        'quant'     => 'Service_Quant',
        'qual'      => 'Service_Qual',
        'systems'   => 'Service_Systems',
        'analytics' => 'Data_Analytics',
        'advisory'  => 'Advisory_Partnership',
    ];

    public function form(string $pillar): void
    {
        if (!isset(self::PILLAR_MAP[$pillar])) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        $service = Database::one('SELECT * FROM services WHERE pillar = ?', [$pillar]);
        $services = Database::all('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order');
        $this->view('public/intake', [
            'title'    => 'Demande de consultation — ERID-AMRAfrica',
            'pillar'   => $pillar,
            'type'     => self::PILLAR_MAP[$pillar],
            'service'  => $service,
            'services' => $services,
        ], 'public');
    }

    public function submit(): void
    {
        Csrf::verify();

        $pillar = $this->input('pillar', 'analytics');
        $type   = self::PILLAR_MAP[$pillar] ?? 'Data_Analytics';

        // Champs complémentaires → stockés en JSON
        $extra = array_filter([
            'timeline' => $this->input('timeline'),
        ]);

        $leadName     = $this->input('lead_name', '');
        $organisation = $this->input('organisation', '');
        $email        = $this->input('email', '');
        $phone        = trim($this->input('phone_code', '') . ' ' . $this->input('phone_number', ''));
        $projectTitle = $this->input('project_title', '');
        $description  = $this->input('description', '');

        $leadId = Database::exec(
            'INSERT INTO leads
                (intake_type, lead_name, organisation, email, phone, project_title, description, extra_json, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $type,
                $leadName,
                $organisation,
                $email,
                $phone ?: null,
                $projectTitle,
                $description,
                $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null,
                'new',
            ]
        );

        // E-mail de triage automatique (white-label) — du brief §8.2
        $tpl = Database::one("SELECT * FROM email_templates WHERE template_key = 'triage_default'");
        if ($tpl) {
            $locale  = Lang::current();
            $subject = $tpl['subject_' . $locale];
            $body    = Mailer::fill($tpl['body_' . $locale], [
                'lead_name'     => $leadName,
                'project_title' => $projectTitle ?: '—',
            ]);
            Mailer::send($email, $subject, $body);
            Database::exec('UPDATE leads SET triage_sent_at = NOW() WHERE id = ?', [$leadId]);
        }

        // Notification admin → consulting@ avec CC macj@
        $adminSubject = "[New Lead] {$type} — {$projectTitle}";
        $adminBody    = "New intake submission\n\nType: {$type}\nName: {$leadName}\nOrganisation: {$organisation}\nEmail: {$email}\nPhone: {$phone}\nProject: {$projectTitle}\n\nDescription:\n{$description}";
        Mailer::send('consulting@erid-amrafrica.org', $adminSubject, $adminBody, 'macj@erid-amrafrica.org');

        Audit::log('create', 'lead', (string) $leadId, ['type' => $type]);

        $this->view('public/intake_success', [
            'title' => 'Demande reçue — ERID-AMRAfrica',
            'type'  => $type,
        ], 'public');
    }

    /** Formulaire anonyme de soumission de signal (EBS). */
    public function rumourForm(): void
    {
        $this->view('public/rumour', [
            'title' => Lang::current() === 'fr' ? 'Signal anonyme — ERID-AMRAfrica' : 'Anonymous signal — ERID-AMRAfrica',
        ], 'public');
    }

    /** Rumour Management System — soumission anonyme (Event-Based Surveillance). */
    public function rumour(): void
    {
        Csrf::verify();

        // Media upload (photo/audio, max 15 MB)
        $mediaFile = null;
        if (!empty($_FILES['media']['name']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $maxSize = 15 * 1024 * 1024; // 15 MB
            if ($_FILES['media']['size'] <= $maxSize) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/rumours';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0775, true);
                }
                $ext  = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
                $name = uniqid('signal_') . '.' . strtolower($ext);
                if (move_uploaded_file($_FILES['media']['tmp_name'], $uploadDir . '/' . $name)) {
                    $mediaFile = '/uploads/rumours/' . $name;
                }
            }
        }

        $id = Database::exec(
            'INSERT INTO rumours (source_channel, is_anonymous, country, region, setting_type, sector, raw_signal, media_file)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                'web_form',
                1,
                $this->input('country'),
                $this->input('region'),
                $this->input('setting_type'),
                'unknown',
                $this->input('signal', ''),
                $mediaFile,
            ]
        );

        // IP strippée pour protéger les sources
        Audit::log('create', 'rumour', (string) $id, [], true);

        // Notification admin
        $signal = mb_substr($this->input('signal', ''), 0, 200);
        Mailer::send(
            'consulting@erid-amrafrica.org',
            '[EBS Signal] New anonymous submission #' . $id,
            "New anonymous signal received\n\nCountry: " . $this->input('country', '—') .
            "\nRegion: " . $this->input('region', '—') .
            "\nSetting: " . $this->input('setting_type', '—') .
            "\n\nSignal:\n{$signal}"
        );

        $this->view('public/rumour_success', [
            'title' => Lang::current() === 'fr' ? 'Signal reçu — ERID-AMRAfrica' : 'Signal received — ERID-AMRAfrica',
        ], 'public');
    }

    /** Abonnement (monétisation : produits de données premium). */
    public function subscribe(): void
    {
        Csrf::verify();
        $email = $this->input('email', '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['ok' => false, 'error' => 'invalid_email'], 422);
        }
        Database::exec(
            'INSERT IGNORE INTO subscribers (email, full_name, organisation, tier, locale)
             VALUES (?, ?, ?, ?, ?)',
            [$email, $this->input('full_name'), $this->input('organisation'), 'free', Lang::current()]
        );
        $this->json(['ok' => true]);
    }
}
