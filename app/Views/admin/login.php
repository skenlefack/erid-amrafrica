<?php
/** @var ?string $error */
use App\Core\View;
use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<div class="login-wrap">
    <div class="login-card">
        <div class="brand brand-light center"><img src="/assets/logo.png" alt="ERID-AMRAfrica" style="max-width:220px;height:auto;margin:0 auto;display:block"></div>
        <p class="muted center">Console d'administration · Admin console</p>
        <?php if (!empty($error)): ?><div class="alert"><?= $e($error) ?></div><?php endif; ?>
        <form method="post" action="/admin/login">
            <?= Csrf::field() ?>
            <label>Email <input type="email" name="email" required autofocus></label>
            <label>Mot de passe / Password <input type="password" name="password" required></label>
            <button class="btn btn-gold lg full" type="submit">Connexion / Sign in</button>
        </form>
        <p class="muted small center">Démo : admin@erid-amrafrica.org · ChangeMe!2026</p>
    </div>
</div>
