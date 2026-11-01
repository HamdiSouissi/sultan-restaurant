<?php /** @var string $brand */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<div class="guest-card">
  <p class="eyebrow">Accès privé</p>
  <h2 class="guest-title">Bienvenue<br><em>au palais.</em></h2>
  <p class="guest-sub">Identifiez-vous pour gérer la carte, les informations et les demandes de la maison.</p>
  <form method="post" action="/admin/login" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <label class="field">
      <span>Adresse e-mail</span>
      <input type="email" name="email" required autocomplete="username" autofocus placeholder="vous@exemple.com">
    </label>
    <label class="field">
      <span>Mot de passe</span>
      <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
    </label>
    <button class="abtn gold full">Entrer dans l’espace <?=$arrow?></button>
  </form>
  <p class="guest-note">Accès réservé. Les tentatives sont limitées et enregistrées.</p>
</div>
