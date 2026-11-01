<?php /** @var array|null $admin */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Sécurité</p>
    <h2 class="pg-title">Mon <em>compte.</em></h2>
    <p class="pg-sub">Vos identifiants d’accès à l’espace de gestion du Sultan.</p>
  </div>
</section>

<div class="two-col">
  <section class="card">
    <header class="card-head"><div><p class="eyebrow">Identifiant</p><h3>Adresse e-mail</h3></div></header>
    <form method="post" action="/admin/compte/enregistrer" class="form">
      <input type="hidden" name="csrf" value="<?=csrf()?>">
      <input type="hidden" name="action" value="email">
      <label class="field"><span>Adresse de connexion</span><input type="email" name="email" required maxlength="150" value="<?=e($admin['email'] ?? '')?>"></label>
      <button class="abtn gold">Mettre à jour <?=$arrow?></button>
    </form>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">Confidentialité</p><h3>Mot de passe</h3></div></header>
    <form method="post" action="/admin/compte/enregistrer" class="form">
      <input type="hidden" name="csrf" value="<?=csrf()?>">
      <input type="hidden" name="action" value="password">
      <label class="field"><span>Mot de passe actuel</span><input type="password" name="current" required autocomplete="current-password"></label>
      <div class="field-row">
        <label class="field"><span>Nouveau mot de passe</span><input type="password" name="password" required minlength="10" autocomplete="new-password"></label>
        <label class="field"><span>Confirmation</span><input type="password" name="password2" required minlength="10" autocomplete="new-password"></label>
      </div>
      <button class="abtn gold">Changer le mot de passe <?=$arrow?></button>
    </form>
  </section>
</div>

<section class="card tips">
  <header class="card-head"><div><p class="eyebrow">Bonnes pratiques</p><h3>Protéger la maison</h3></div></header>
  <ul class="ticks">
    <li>Utilisez un mot de passe long, unique, conservé dans un gestionnaire de mots de passe.</li>
    <li>Ne partagez pas le compte : créez-en un par personne si besoin (<code>admins</code>).</li>
    <li>Déconnectez-vous après une session sur un ordinateur partagé.</li>
    <li>Le site tourne en HTTPS : vérifiez que le certificat reste valide.</li>
    <li>Les tentatives de connexion sont limitées à 8 par quart d’heure et par adresse.</li>
  </ul>
</section>
