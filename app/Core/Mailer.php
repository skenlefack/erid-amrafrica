<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Envoi d'e-mails. En production, brancher PHPMailer/SMTP.
 * Le starter journalise les envois dans storage/mail.log pour la démo.
 */
final class Mailer
{
    public static function send(string $to, string $subject, string $body): bool
    {
        $cfg  = Config::get('mail');
        $from = $cfg['from_name'] . ' <' . $cfg['from'] . '>';

        // --- DÉMO : journalisation. En prod, remplacer par PHPMailer SMTP. ---
        $logDir = Config::get('root') . '/storage';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }
        $entry = sprintf(
            "[%s] TO:%s\nFROM:%s\nSUBJECT:%s\n%s\n%s\n",
            date('c'), $to, $from, $subject, str_repeat('-', 40), $body
        );
        @file_put_contents($logDir . '/mail.log', $entry . "\n", FILE_APPEND);

        // mail($to, $subject, $body, "From: {$from}\r\nContent-Type: text/plain; charset=utf-8");
        return true;
    }

    /** Remplit un modèle {{var}} avec des valeurs. */
    public static function fill(string $template, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $template = str_replace('{{' . $k . '}}', (string) $v, $template);
        }
        return $template;
    }
}
