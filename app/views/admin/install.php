<?php /** @var string $brand */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<div class="guest-card">
  <p class="eyebrow">Prima installazione</p>
  <h2 class="guest-title">Create il vostro<br><em>account amministratore.</em></h2>
  <p class="guest-sub">Non esiste ancora alcun account. Scegliete le credenziali del gestore: apriranno lo spazio di gestione del Sultan.</p>
  <form method="post" action="/admin/install" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <label class="field">
      <span>Indirizzo e-mail</span>
      <input type="email" name="email" required autocomplete="username" value="<?=e(old('email'))?>" placeholder="voi@esempio.it">
    </label>
    <label class="field">
      <span>Password <i>almeno 10 caratteri</i></span>
      <input type="password" name="password" required minlength="10" autocomplete="new-password">
    </label>
    <label class="field">
      <span>Confermate la password</span>
      <input type="password" name="password2" required minlength="10" autocomplete="new-password">
    </label>
    <button class="abtn gold full">Crea il mio account <?=$arrow?></button>
  </form>
  <p class="guest-note">Le password sono memorizzate con hash (bcrypt), mai in chiaro.</p>
</div>
<?php forget_old(); ?>
