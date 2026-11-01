<?php /** @var string $brand */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<div class="guest-card">
  <p class="eyebrow">Première installation</p>
  <h2 class="guest-title">Créez votre<br><em>compte d’administration.</em></h2>
  <p class="guest-sub">Aucun compte n’existe encore. Choisissez les identifiants du gérant : ils ouvriront l’espace de gestion du Sultan.</p>
  <form method="post" action="/admin/install" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <label class="field">
      <span>Adresse e-mail</span>
      <input type="email" name="email" required autocomplete="username" value="<?=e(old('email'))?>" placeholder="vous@exemple.com">
    </label>
    <label class="field">
      <span>Mot de passe <i>10 caractères minimum</i></span>
      <input type="password" name="password" required minlength="10" autocomplete="new-password">
    </label>
    <label class="field">
      <span>Confirmer le mot de passe</span>
      <input type="password" name="password2" required minlength="10" autocomplete="new-password">
    </label>
    <button class="abtn gold full">Créer mon compte <?=$arrow?></button>
  </form>
  <p class="guest-note">Les mots de passe sont stockés hachés (bcrypt) — jamais en clair.</p>
</div>
<?php forget_old(); ?>
