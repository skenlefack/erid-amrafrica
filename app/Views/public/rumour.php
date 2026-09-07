<?php
use App\Core\Lang;
use App\Core\View;
use App\Core\Csrf;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<section class="section">
<div class="container" style="max-width:640px">

    <div style="text-align:center;margin-bottom:32px">
        <span style="font-size:48px">📡</span>
        <h1 class="page-title" style="margin-top:12px"><?= $e($lang === 'fr' ? 'Signal anonyme' : 'Anonymous Signal') ?></h1>
        <p class="section-sub"><?= $e($lang === 'fr'
            ? 'Partagez un signal de terrain de manière confidentielle. Aucune information personnelle n\'est requise.'
            : 'Share a field signal confidentially. No personal information is required.') ?></p>
    </div>

    <form class="intake-form" method="post" action="/rumour" enctype="multipart/form-data">
        <?= Csrf::field() ?>

        <!-- 1. Signal Description -->
        <div class="intake-section">
            <h3 class="intake-section__title"><?= $e($lang === 'fr' ? 'Description du signal' : 'Signal description') ?></h3>
            <label>
                <textarea name="signal" rows="5" required placeholder="<?= $e($lang === 'fr'
                    ? 'Qu\'avez-vous observé ou entendu ? Ex : vente non réglementée d\'antibiotiques, mortalité soudaine du bétail, mauvais usage de pesticides...'
                    : 'What did you observe or hear? e.g., unregulated antibiotic sale, sudden livestock mortality, pesticide misuse...') ?>"></textarea>
            </label>
        </div>

        <!-- 2. Location Details -->
        <div class="intake-section">
            <h3 class="intake-section__title"><?= $e($lang === 'fr' ? 'Localisation' : 'Location details') ?></h3>
            <div class="form-grid">
                <label><?= $e($lang === 'fr' ? 'Pays' : 'Country') ?>
                    <select name="country">
                        <option value=""><?= $e($lang === 'fr' ? '-- Sélectionnez --' : '-- Select --') ?></option>
                        <?php
                        $countries = ['Cameroun/Cameroon','Nigeria','Kenya','South Africa/Afrique du Sud','Ghana',
                            'Senegal/Sénégal','Côte d\'Ivoire','DRC/RDC','Uganda/Ouganda','Tanzania/Tanzanie',
                            'Ethiopia/Éthiopie','Morocco/Maroc','Egypt/Égypte','Rwanda','Burkina Faso',
                            'Benin/Bénin','Togo','Mali','Niger','Chad/Tchad','Gabon','Congo',
                            'Mozambique','Madagascar','Zambia/Zambie','Zimbabwe','Malawi','Angola','Somalia/Somalie'];
                        foreach ($countries as $c):
                            $parts = explode('/', $c);
                            $display = $lang === 'fr' && isset($parts[1]) ? $parts[1] : $parts[0];
                            $value = $parts[0];
                        ?>
                        <option value="<?= $e($value) ?>"><?= $e($display) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label><?= $e($lang === 'fr' ? 'Région / District' : 'Region / District') ?>
                    <input type="text" name="region" placeholder="<?= $e($lang === 'fr' ? 'Ex : Littoral, Ashanti, Nairobi...' : 'e.g., Littoral, Ashanti, Nairobi...') ?>">
                </label>
                <label><?= $e($lang === 'fr' ? 'Type de lieu' : 'Setting type') ?>
                    <select name="setting_type">
                        <option value=""><?= $e($lang === 'fr' ? '-- Sélectionnez --' : '-- Select --') ?></option>
                        <option value="farm"><?= $e($lang === 'fr' ? 'Exploitation agricole' : 'Farm') ?></option>
                        <option value="veterinary_clinic"><?= $e($lang === 'fr' ? 'Clinique vétérinaire' : 'Veterinary Clinic') ?></option>
                        <option value="open_market"><?= $e($lang === 'fr' ? 'Marché ouvert' : 'Open Market') ?></option>
                        <option value="community"><?= $e($lang === 'fr' ? 'Communauté' : 'Community') ?></option>
                        <option value="other"><?= $e($lang === 'fr' ? 'Autre' : 'Other') ?></option>
                    </select>
                </label>
            </div>
        </div>

        <!-- 3. Optional Media Upload -->
        <div class="intake-section">
            <h3 class="intake-section__title"><?= $e($lang === 'fr' ? 'Média (optionnel)' : 'Media (optional)') ?></h3>
            <label class="file-upload" tabindex="0" style="display:block;padding:24px;border:2px dashed var(--border);border-radius:8px;text-align:center;cursor:pointer">
                <input type="file" name="media" accept="image/*,audio/*" style="display:none" class="file-upload__input">
                <span style="font-size:28px">📷</span>
                <p style="margin:8px 0 0;color:var(--muted);font-size:13px"><?= $e($lang === 'fr'
                    ? 'Photo d\'emballage, étiquette de prescription, ou mémo vocal — max 15 MB'
                    : 'Photo of packaging, prescription label, or voice memo — max 15 MB') ?></p>
            </label>
            <span class="file-upload__name" style="font-size:13px;color:var(--accent);margin-top:4px;display:block"></span>
        </div>

        <button class="btn btn-gold lg full" type="submit" style="margin-top:12px"><?= $e($lang === 'fr' ? 'Envoyer le signal' : 'Submit signal') ?></button>
        <p class="muted small" style="margin-top:12px;text-align:center">🔒 <?= $e($lang === 'fr'
            ? 'Soumission 100% anonyme. Aucune adresse IP ni métadonnée n\'est conservée.'
            : '100% anonymous submission. No IP address or metadata is stored.') ?></p>
    </form>

</div>
</section>
