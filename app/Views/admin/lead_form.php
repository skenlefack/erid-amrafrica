<?php
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<a class="back" href="/admin/leads">← Leads</a>
<div class="panel" style="max-width:640px">
  <h2>Nouveau lead</h2>
  <form method="post" action="/admin/leads">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Nom du contact *
        <input type="text" name="lead_name" required placeholder="Dr. Nom Prénom"></label>
      <label>Organisation *
        <input type="text" name="organisation" required placeholder="Institution / Organisation"></label>
      <label>E-mail *
        <input type="email" name="email" required placeholder="email@org.africa"></label>
      <label>Téléphone
        <input type="text" name="phone" placeholder="+237 6xx xxx xxx"></label>
    </div>
    <label>Type d'intake
      <select name="intake_type">
        <option value="Service_Quant">Pillar A — Quantitative</option>
        <option value="Service_Qual">Pillar B — Qualitative</option>
        <option value="Service_Systems">Pillar C — Systems</option>
        <option value="Data_Analytics">Data Analytics</option>
        <option value="Advisory_Partnership" selected>Advisory & Partnership</option>
      </select>
    </label>
    <label>Titre du projet
      <input type="text" name="project_title" placeholder="Titre du projet ou de la mission"></label>
    <label>Description
      <textarea name="description" rows="4" placeholder="Objectifs, contexte, livrables attendus..."></textarea></label>
    <div class="form-grid">
      <label>Statut
        <select name="status">
          <option value="new">New</option>
          <option value="reviewing">Reviewing</option>
          <option value="scoping">Scoping</option>
        </select>
      </label>
      <label>Valeur estimée (USD)
        <input type="number" step="100" name="est_value_usd" placeholder="0"></label>
    </div>
    <button class="btn btn-gold full" type="submit" style="margin-top:12px">Créer le lead</button>
  </form>
</div>
