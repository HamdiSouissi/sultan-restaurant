<?php /** @var string $message */ ?>
<div class="guest-card">
  <p class="eyebrow">Database</p>
  <h2 class="guest-title">Connessione<br><em>non riuscita.</em></h2>
  <p class="guest-sub">Lo spazio di amministrazione non ha potuto raggiungere il database.</p>
  <div class="notice err">
    <b>Dettaglio tecnico</b>
    <p><?=e($message)?></p>
  </div>
  <ol class="steps">
    <li>Controllate le credenziali MySQL in <code>app/config.php</code>.</li>
    <li>Importate <code>sql/schema.sql</code> e poi <code>sql/migration_admin.sql</code>.</li>
    <li>Ricaricate questa pagina.</li>
  </ol>
  <a class="abtn line full" href="/admin">Riprova</a>
</div>
