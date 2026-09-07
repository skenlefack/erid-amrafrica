<?php
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<section class="section" style="text-align:center;padding:80px 20px">
    <div class="container" style="max-width:520px">
        <span style="font-size:56px">✅</span>
        <h1 class="page-title" style="margin-top:16px"><?= $e($lang === 'fr' ? 'Signal reçu' : 'Signal received') ?></h1>
        <p style="color:var(--muted);line-height:1.7;margin:16px 0 28px"><?= $e($lang === 'fr'
            ? 'Merci pour votre contribution à la surveillance One Health. Votre signal a été transmis en toute confidentialité à notre équipe d\'analyse. Aucune information permettant de vous identifier n\'a été conservée.'
            : 'Thank you for your contribution to One Health surveillance. Your signal has been securely forwarded to our analysis team. No identifying information has been stored.') ?></p>
        <a class="btn btn-gold" href="/"><?= $e(Lang::t('back_home')) ?></a>
    </div>
</section>
