<?php /** @var array|null $admin */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Sicurezza</p>
    <h2 class="pg-title">Il mio <em>account.</em></h2>
    <p class="pg-sub">Le vostre credenziali di accesso allo spazio di gestione del Sultan.</p>
  </div>
</section>

<div class="two-col">
  <section class="card">
    <header class="card-head"><div><p class="eyebrow">Identificativo</p><h3>Indirizzo e-mail</h3></div></header>
    <form method="post" action="/admin/compte/enregistrer" class="form">
      <input type="hidden" name="csrf" value="<?=csrf()?>">
      <input type="hidden" name="action" value="email">
      <label class="field"><span>Indirizzo di accesso</span><input type="email" name="email" required maxlength="150" value="<?=e($admin['email'] ?? '')?>"></label>
      <button class="abtn gold">Aggiorna <?=$arrow?></button>
    </form>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">Riservatezza</p><h3>Password</h3></div></header>
    <form method="post" action="/admin/compte/enregistrer" class="form">
      <input type="hidden" name="csrf" value="<?=csrf()?>">
      <input type="hidden" name="action" value="password">
      <label class="field"><span>Password attuale</span><input type="password" name="current" required autocomplete="current-password"></label>
      <div class="field-row">
        <label class="field"><span>Nuova password</span><input type="password" name="password" required minlength="10" autocomplete="new-password"></label>
        <label class="field"><span>Conferma</span><input type="password" name="password2" required minlength="10" autocomplete="new-password"></label>
      </div>
      <button class="abtn gold">Cambia la password <?=$arrow?></button>
    </form>
  </section>
</div>

<section class="card tips">
  <header class="card-head"><div><p class="eyebrow">Buone pratiche</p><h3>Proteggere la casa</h3></div></header>
  <ul class="ticks">
    <li>Usate una password lunga e unica, conservata in un gestore di password.</li>
    <li>Non condividete l’account: createne uno per persona se serve (tabella <code>admins</code>).</li>
    <li>Uscite dopo una sessione su un computer condiviso.</li>
    <li>Il sito gira in HTTPS: verificate che il certificato resti valido.</li>
    <li>I tentativi di accesso sono limitati a 8 per quarto d’ora e per indirizzo.</li>
  </ul>
</section>
