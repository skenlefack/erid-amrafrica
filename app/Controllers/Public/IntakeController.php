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
        $this->view('public/intake', [
            'title'   => 'Demande de consultation — ERID-AMRAfrica',
            'pillar'  => $pillar,
            'type'    => self::PILLAR_MAP[$pillar],
            'service' => $service,
        ], 'public');
    }

    public function submit(): void
    {
        Csrf::verify();

        $pillar = $this->input('pillar', 'analytics');
        $type   = self::PILLAR_MAP[$pillar] ?? 'Data_Analytics';

        // Champs spécifiques au pilier → stockés en JSON
        $extra = [
            'dap'           => $this->input('dap'),
            'sectors'       => $_POST['sectors'] ?? null,
            'methodology'   => $this->input('methodology'),
            'timeline'      => $this->input('timeline'),
            'deliverable'   => $this->input('deliverable'),
        ];

        $leadId = Database::exec(
            'INSERT INTO leads
                (intake_type, lead_name, organisation, email, phone, project_title, description, extra_json, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $type,
                $this->input('lead_name', ''),
                $this->input('organisation', ''),
                $this->input('email', ''),
                $this->input('phone'),
                $this->input('project_title'),
                $this->input('description'),
                json_encode(array_filter($extra), JSON_UNESCAPED_UNICODE),
                'new',
            ]
        );

        // E-mail de triage automatique (white-label) — du brief §8.2
        $tpl = Database::one("SELECT * FROM email_templates WHERE template_key = 'triage_default'");
        if ($tpl) {
            $locale  = Lang::current();
            $subject = $tpl['subject_' . $locale];
            $body    = Mailer::fill($tpl['body_' . $locale], [
                'lead_name'     => $this->input('lead_name', ''),
                'project_title' => $this->input('project_title', '—'),
            ]);
            Mailer::send($this->input('email', ''), $subject, $body);
            Database::exec('UPDATE leads SET triage_sent_at = NOW() WHERE id = ?', [$leadId]);
        }

        Audit::log('create', 'lead', (string) $leadId, ['type' => $type]);

        $this->view('public/intake_success', [
            'title' => 'Demande reçue — ERID-AMRAfrica',
            'type'  => $type,
        ], 'public');
    }

    /** Rumour Management System — soumission anonyme (Event-Based Surveillance). */
    public function rumour(): void
    {
        Csrf::verify();
        $id = Database::exec(
            'INSERT INTO rumours (source_channel, is_anonymous, country, sector, raw_signal)
             VALUES (?, ?, ?, ?, ?)',
            [
                'web_form',
                1,
                $this->input('country'),
                $this->input('sector', 'unknown'),
                $this->input('signal', ''),
            ]
        );
        Audit::log('create', 'rumour', (string) $id);
        $this->json(['ok' => true, 'id' => $id]);
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
