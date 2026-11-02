<?php /** @var string $brand */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<div class="guest-card">
  <p class="eyebrow">Accesso privato</p>
  <h2 class="guest-title">Benvenuti<br><em>al palazzo.</em></h2>
  <p class="guest-sub">Accedete per gestire il menù, le informazioni e le richieste della casa.</p>
  <form method="post" action="/admin/login" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <label class="field">
      <span>Indirizzo e-mail</span>
      <input type="email" name="email" required autocomplete="username" autofocus placeholder="voi@esempio.it">
    </label>
    <label class="field">
      <span>Password</span>
      <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
    </label>
    <button class="abtn gold full">Entra nello spazio <?=$arrow?></button>
  </form>
  <p class="guest-note">Accesso riservato. I tentativi sono limitati e registrati.</p>
</div>
