<?php
/** @var string $pillar @var string $type @var ?array $service @var array $services */
use App\Core\Lang;
use App\Core\View;
use App\Core\Csrf;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
$lang = $_SESSION['locale'] ?? 'fr';
$isSystems = $pillar === 'systems';
$isQuant   = $pillar === 'quant';
$pillarColors = ['quant' => '#1565C0', 'qual' => '#00897B', 'systems' => '#F9A825', 'analytics' => '#7B1FA2', 'advisory' => '#E53935'];
$currentColor = $pillarColors[$pillar] ?? 'var(--accent)';
$pillarIcons  = ['quant' => '📊', 'qual' => '🧠', 'systems' => '🔄', 'analytics' => '📈', 'advisory' => '🤝'];
?>
<section class="section" style="padding-top:28px">
<div class="container">
<div class="intake-layout">

<!-- LEFT: Formulaire -->
<div class="intake-main">
    <a class="back" href="/services">← <?= $e(Lang::t('nav_services')) ?></a>

    <div class="intake-header" style="border-left:5px solid <?= $e($currentColor) ?>">
        <div class="intake-header__icon" style="background:<?= $e($currentColor) ?>"><?= $pillarIcons[$pillar] ?? '📋' ?></div>
        <div>
            <h1 class="intake-header__title"><?= $e(Lang::t('intake_title')) ?></h1>
            <?php if ($service): ?>
            <p class="intake-header__service"><?= $e($pick($service, 'title')) ?></p>
            <?php endif; ?>
            <span class="intake-header__tag" style="background:<?= $e($currentColor) ?>"><?= $e($type) ?></span>
        </div>
    </div>

    <form class="intake-form" method="post" action="/intake">
        <?= Csrf::field() ?>

        <div class="intake-section">
            <h3 class="intake-section__title"><?= $e($lang === 'fr' ? 'Vos coordonnées' : 'Your details') ?></h3>
            <div class="form-grid">
                <label><?= $e(Lang::t('f_lead')) ?> *
                    <input type="text" name="lead_name" required placeholder="<?= $e($lang === 'fr' ? 'Dr. Nom Prénom' : 'Dr. First Last') ?>"></label>
                <label><?= $e(Lang::t('f_org')) ?> *
                    <input type="text" name="organisation" required placeholder="<?= $e($lang === 'fr' ? 'Institution / Organisation' : 'Institution / Organization') ?>"></label>
                <label><?= $e(Lang::t('f_email')) ?> *
                    <input type="email" name="email" required placeholder="email@institution.org"></label>
                <label><?= $e($lang === 'fr' ? 'WhatsApp (avec indicatif pays)' : 'WhatsApp line (with country code)') ?>
                    <div style="display:flex;gap:8px">
                        <select name="phone_code" style="width:120px">
                            <option value="+237">🇨🇲 +237</option>
                            <option value="+254">🇰🇪 +254</option>
                            <option value="+234">🇳🇬 +234</option>
                            <option value="+27">🇿🇦 +27</option>
                            <option value="+233">🇬🇭 +233</option>
                            <option value="+225">🇨🇮 +225</option>
                            <option value="+221">🇸🇳 +221</option>
                            <option value="+243">🇨🇩 +243</option>
                            <option value="+256">🇺🇬 +256</option>
                            <option value="+255">🇹🇿 +255</option>
                            <option value="+251">🇪🇹 +251</option>
                            <option value="+212">🇲🇦 +212</option>
                            <option value="+20">🇪🇬 +20</option>
                            <option value="+250">🇷🇼 +250</option>
                            <option value="+226">🇧🇫 +226</option>
                            <option value="+229">🇧🇯 +229</option>
                            <option value="+228">🇹🇬 +228</option>
                            <option value="+223">🇲🇱 +223</option>
                            <option value="+227">🇳🇪 +227</option>
                            <option value="+235">🇹🇩 +235</option>
                        </select>
                        <input type="text" name="phone_number" placeholder="6xx xxx xxx" style="flex:1">
                    </div>
                </label>
            </div>
        </div>

        <div class="intake-section">
            <h3 class="intake-section__title"><?= $e($lang === 'fr' ? 'Service & projet' : 'Service & project') ?></h3>
            <label><?= $e($lang === 'fr' ? 'Service demandé' : 'Requested service') ?> *
                <select name="pillar" required>
                    <option value="quant" <?= $pillar === 'quant' ? 'selected' : '' ?>>Pillar A — <?= $e($lang === 'fr' ? 'Data Science quantitative & épidémiologie spatiale' : 'Quantitative Data Science & Spatial Epidemiology') ?></option>
                    <option value="qual" <?= $pillar === 'qual' ? 'selected' : '' ?>>Pillar B — <?= $e($lang === 'fr' ? 'Analyse qualitative & intelligence comportementale' : 'Qualitative Analysis & Behavioural Health Intelligence') ?></option>
                    <option value="systems" <?= $pillar === 'systems' ? 'selected' : '' ?>>Pillar C — <?= $e($lang === 'fr' ? 'Pensée systémique & simulation' : 'Systems Thinking & Simulation') ?></option>
                    <option value="advisory" <?= $pillar === 'advisory' ? 'selected' : '' ?>><?= $e($lang === 'fr' ? 'Advisory & Partenariats' : 'Advisory & Partnerships') ?></option>
                </select>
            </label>
            <label><?= $e(Lang::t('f_project')) ?> *
                <input type="text" name="project_title" required placeholder="<?= $e($lang === 'fr' ? 'Titre du projet ou de la mission' : 'Project or mission title') ?>"></label>
            <label><?= $e($lang === 'fr' ? 'Plan d\'analyse de données (DAP), Termes de référence (ToR), ou Narratif de projet' : 'Data Analysis Plan (DAP), Terms of Reference (ToR), or Brief Project Narrative') ?> *
                <textarea name="description" rows="6" required placeholder="<?= $e($lang === 'fr' ? 'Décrivez vos objectifs, le contexte, la méthodologie envisagée et les résultats attendus...' : 'Describe your objectives, context, envisaged methodology and expected outcomes...') ?>"></textarea></label>
            <label><?= $e(Lang::t('f_timeline')) ?>
                <input type="date" name="timeline"></label>
        </div>

        <button class="btn btn-gold lg full" type="submit" style="margin-top:8px"><?= $e(Lang::t('submit_request')) ?></button>
        <p class="muted small" style="margin-top:12px;text-align:center">🔒 <?= $e(Lang::t('intake_privacy')) ?></p>
    </form>
</div>

<!-- RIGHT: Sidebar -->
<div class="intake-side">

    <!-- Service actuel -->
    <?php if ($service): ?>
    <div class="widget">
        <div class="widget-title" style="background:<?= $e($currentColor) ?>"><span><?= $e($lang === 'fr' ? 'Service sélectionné' : 'Selected service') ?></span></div>
        <div class="widget-body">
            <h4 style="font-family:var(--font-d);font-size:15px;margin:0 0 8px;color:var(--ink)"><?= $e($pick($service, 'title')) ?></h4>
            <p style="font-size:13px;color:var(--muted);margin:0 0 10px;line-height:1.5"><?= $e($pick($service, 'summary')) ?></p>
            <span class="badge"><?= $e(Lang::t('model_' . $service['price_model'])) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Processus -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Comment ça marche' : 'How it works') ?></span></div>
        <div class="widget-body">
            <div class="process-steps">
                <div class="process-step">
                    <span class="process-step__num" style="background:<?= $e($currentColor) ?>">1</span>
                    <div>
                        <strong><?= $e($lang === 'fr' ? 'Soumission' : 'Submit') ?></strong>
                        <p><?= $e($lang === 'fr' ? 'Remplissez le formulaire ci-contre' : 'Fill out the form') ?></p>
                    </div>
                </div>
                <div class="process-step">
                    <span class="process-step__num" style="background:<?= $e($currentColor) ?>">2</span>
                    <div>
                        <strong><?= $e($lang === 'fr' ? 'Triage (48h)' : 'Triage (48h)') ?></strong>
                        <p><?= $e($lang === 'fr' ? 'Routage automatique vers l\'expert adéquat' : 'Auto-routing to the right expert') ?></p>
                    </div>
                </div>
                <div class="process-step">
                    <span class="process-step__num" style="background:<?= $e($currentColor) ?>">3</span>
                    <div>
                        <strong><?= $e($lang === 'fr' ? 'Cadrage' : 'Scoping') ?></strong>
                        <p><?= $e($lang === 'fr' ? 'Session de cadrage pour définir le périmètre' : 'Scoping session to define the scope') ?></p>
                    </div>
                </div>
                <div class="process-step">
                    <span class="process-step__num" style="background:<?= $e($currentColor) ?>">4</span>
                    <div>
                        <strong><?= $e($lang === 'fr' ? 'Proposition technique & financière' : 'Technical & Financial Proposal') ?></strong>
                        <p><?= $e($lang === 'fr' ? 'Proposition technique & financière / Note de cadrage partenariat' : 'Technical & Financial Proposal / Partnership Scoping Note') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Autres services -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Autres expertises' : 'Other expertise') ?></span></div>
        <div class="widget-body" style="padding:0">
            <?php
            $svcIcons = ['quant' => '📊', 'qual' => '🧠', 'systems' => '🔄'];
            $svcColors = ['quant' => '#1565C0', 'qual' => '#00897B', 'systems' => '#F9A825'];
            foreach ($services as $s):
                if ($s['pillar'] === $pillar) continue;
            ?>
            <a href="/intake/<?= $e($s['pillar']) ?>" class="sidebar-service" style="--svc-color:<?= $e($svcColors[$s['pillar']] ?? 'var(--accent)') ?>">
                <div class="sidebar-service__icon"><?= $svcIcons[$s['pillar']] ?? '💡' ?></div>
                <div class="sidebar-service__body">
                    <h4><?= $e($pick($s, 'title')) ?></h4>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Garanties -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Garanties' : 'Guarantees') ?></span></div>
        <div class="widget-body">
            <div class="guarantee-list">
                <div class="guarantee-item">🔒 <span><?= $e($lang === 'fr' ? 'Données chiffrées AES-256' : 'AES-256 encrypted data') ?></span></div>
                <div class="guarantee-item">⏱️ <span><?= $e($lang === 'fr' ? 'Réponse sous 48h ouvrées' : 'Reply within 48 business hours') ?></span></div>
                <div class="guarantee-item">🌍 <span><?= $e($lang === 'fr' ? 'Expertise pan-africaine' : 'Pan-African expertise') ?></span></div>
                <div class="guarantee-item">📋 <span><?= $e($lang === 'fr' ? 'Confidentialité totale' : 'Full confidentiality') ?></span></div>
            </div>
        </div>
    </div>

</div>

</div>
</div>
</section>
