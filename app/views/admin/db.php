<?php /** @var string $message */ ?>
<div class="guest-card">
  <p class="eyebrow">Base de données</p>
  <h2 class="guest-title">Connexion<br><em>impossible.</em></h2>
  <p class="guest-sub">L’espace d’administration n’a pas pu joindre la base de données.</p>
  <div class="notice err">
    <b>Détail technique</b>
    <p><?=e($message)?></p>
  </div>
  <ol class="steps">
    <li>Vérifiez les identifiants MySQL dans <code>app/config.php</code>.</li>
    <li>Importez <code>sql/schema.sql</code> puis <code>sql/migration_admin.sql</code>.</li>
    <li>Rechargez cette page.</li>
  </ol>
  <a class="abtn line full" href="/admin">Réessayer</a>
</div>
