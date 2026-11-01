<?php
/** @var array $s @var array $hours @var array $socials */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$val = fn(string $k, string $fallback = '') => old($k, (string)($s[$k] ?? $fallback));
$days = [1 => 'Lunedì', 2 => 'Martedì', 3 => 'Mercoledì', 4 => 'Giovedì', 5 => 'Venerdì', 6 => 'Sabato', 7 => 'Domenica'];
$byDay = [];
foreach ($hours as $h) $byDay[(int)$h['day']] = $h;
$t = fn($v) => $v ? substr((string)$v, 0, 5) : '';
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Identità e contatti</p>
    <h2 class="pg-title">Le informazioni <em>della casa.</em></h2>
    <p class="pg-sub">Nome, indirizzo, telefono, orari: tutto ciò che i vostri ospiti trovano sul sito.</p>
  </div>
  <div class="pg-acts"><a class="abtn line" href="/" target="_blank" rel="noopener">Vedi il sito <?=$arrow?></a></div>
</section>

<form method="post" action="/admin/infos/enregistrer" class="form">
  <input type="hidden" name="csrf" value="<?=csrf()?>">

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">01</p><h3>Identità</h3></div></header>
    <div class="field-row">
      <label class="field"><span>Nome della casa</span><input name="name" required maxlength="60" value="<?=e($val('name'))?>" placeholder="Sultan"></label>
      <label class="field"><span>Sottotitolo <i>sotto il logo</i></span><input name="tagline" maxlength="160" value="<?=e($val('tagline'))?>" placeholder="Cucina mediterranea · Street food tunisino"></label>
    </div>
    <label class="field"><span>Descrizione (referenziazione e social)</span><textarea name="description" rows="3" maxlength="400"><?=e($val('description'))?></textarea></label>
    <label class="field"><span>Testo «La nostra storia»</span><textarea name="about_text" rows="4" maxlength="600"><?=e($val('about_text'))?></textarea></label>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">02</p><h3>Contatti e indirizzo</h3></div></header>
    <div class="field-row">
      <label class="field"><span>Telefono visualizzato</span><input name="phone" maxlength="40" value="<?=e($val('phone'))?>" placeholder="+39 379 210 7500"></label>
      <label class="field"><span>WhatsApp <i>solo cifre</i></span><input name="whatsapp" maxlength="30" value="<?=e($val('whatsapp'))?>" placeholder="393792107500"></label>
    </div>
    <label class="field"><span>Indirizzo e-mail <i>facoltativo</i></span><input type="email" name="email" maxlength="150" value="<?=e($val('email'))?>"></label>
    <label class="field"><span>Indirizzo</span><input name="address" maxlength="255" value="<?=e($val('address'))?>"></label>
    <div class="field-row">
      <label class="field"><span>Latitudine</span><input name="lat" maxlength="20" value="<?=e($val('lat'))?>" placeholder="44.80"></label>
      <label class="field"><span>Longitudine</span><input name="lng" maxlength="20" value="<?=e($val('lng'))?>" placeholder="10.33"></label>
    </div>
    <label class="field"><span>Link di incorporamento della mappa (Google Maps)</span><input name="maps_embed" maxlength="500" value="<?=e($val('maps_embed'))?>" placeholder="https://www.google.com/maps?q=…&output=embed"></label>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">03</p><h3>Testi del menù</h3></div></header>
    <label class="field"><span>Nota sotto il menù <i>supplementi, precisazioni</i></span><textarea name="menu_note" rows="2" maxlength="400"><?=e($val('menu_note'))?></textarea></label>
    <label class="field"><span>Citazione / proverbio</span><input name="quote_text" maxlength="200" value="<?=e($val('quote_text'))?>" placeholder="A Tunisi non si contano i piatti, si contano gli ospiti."></label>
  </section>

  <div class="form-actions">
    <button class="abtn gold">Salva le informazioni <?=$arrow?></button>
    <span class="muted small">Le modifiche sono visibili subito sul sito.</span>
  </div>
</form>

<section class="card" id="horaires">
  <header class="card-head"><div><p class="eyebrow">04</p><h3>Orari di apertura</h3></div></header>
  <form method="post" action="/admin/horaires/enregistrer" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <div class="table-wrap">
      <table class="tbl hours-tbl">
        <thead><tr><th>Giorno</th><th>Primo servizio</th><th>Secondo servizio</th><th class="ta-c">Chiuso</th></tr></thead>
        <tbody>
        <?php foreach ($days as $num => $label): $row = $byDay[$num] ?? []; ?>
          <tr>
            <td><b><?=e($label)?></b></td>
            <td>
              <div class="time-pair">
                <input type="time" name="hours[<?=$num?>][open1]" value="<?=e($t($row['open1'] ?? ''))?>" aria-label="Apertura <?=e($label)?>">
                <span>–</span>
                <input type="time" name="hours[<?=$num?>][close1]" value="<?=e($t($row['close1'] ?? ''))?>" aria-label="Chiusura <?=e($label)?>">
              </div>
            </td>
            <td>
              <div class="time-pair">
                <input type="time" name="hours[<?=$num?>][open2]" value="<?=e($t($row['open2'] ?? ''))?>" aria-label="Apertura serale <?=e($label)?>">
                <span>–</span>
                <input type="time" name="hours[<?=$num?>][close2]" value="<?=e($t($row['close2'] ?? ''))?>" aria-label="Chiusura serale <?=e($label)?>">
              </div>
            </td>
            <td class="ta-c">
              <label class="check"><input type="checkbox" name="hours[<?=$num?>][closed]" value="1"<?= !empty($row['closed']) ? ' checked' : ''?>><span class="sr-only">Chiuso</span></label>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="form-actions"><button class="abtn gold">Salva gli orari</button></div>
  </form>
</section>

<section class="card" id="reseaux">
  <header class="card-head"><div><p class="eyebrow">05</p><h3>Social network</h3></div></header>
  <?php if ($socials): ?>
    <ul class="social-list">
      <?php foreach ($socials as $so): ?>
        <li>
          <form method="post" action="/admin/reseaux/enregistrer" class="form social-form">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$so['id'] ?>">
            <input name="network" maxlength="30" value="<?=e($so['network'])?>" aria-label="Social" placeholder="instagram">
            <input name="url" maxlength="255" value="<?=e($so['url'])?>" aria-label="Indirizzo" placeholder="https://…">
            <button class="abtn line sm">Salva</button>
          </form>
          <form method="post" action="/admin/reseaux/supprimer" data-confirm="Eliminare questo social network?">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$so['id'] ?>">
            <button class="icon-btn danger" title="Elimina">Elimina</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="empty">Nessun social network impostato.</p>
  <?php endif; ?>

  <form method="post" action="/admin/reseaux/enregistrer" class="form social-form add-social">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <input name="network" maxlength="30" placeholder="Social: instagram, google, tripadvisor…" aria-label="Nuovo social">
    <input name="url" maxlength="255" placeholder="https://…" aria-label="Indirizzo del social">
    <button class="abtn gold sm">Aggiungi</button>
  </form>
</section>
<?php forget_old(); ?>
