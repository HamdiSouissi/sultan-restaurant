<?php
/** @var array $s @var array $hours @var array $socials */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$val = fn(string $k, string $fallback = '') => old($k, (string)($s[$k] ?? $fallback));
$days = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
$byDay = [];
foreach ($hours as $h) $byDay[(int)$h['day']] = $h;
$t = fn($v) => $v ? substr((string)$v, 0, 5) : '';
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Identité & contact</p>
    <h2 class="pg-title">Les informations <em>de la maison.</em></h2>
    <p class="pg-sub">Nom, adresse, téléphone, horaires : tout ce que vos hôtes trouvent sur le site.</p>
  </div>
  <div class="pg-acts"><a class="abtn line" href="/" target="_blank" rel="noopener">Voir le site <?=$arrow?></a></div>
</section>

<form method="post" action="/admin/infos/enregistrer" class="form">
  <input type="hidden" name="csrf" value="<?=csrf()?>">

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">01</p><h3>Identité</h3></div></header>
    <div class="field-row">
      <label class="field"><span>Nom de la maison</span><input name="name" required maxlength="60" value="<?=e($val('name'))?>" placeholder="Sultan"></label>
      <label class="field"><span>Accroche <i>sous le logo</i></span><input name="tagline" maxlength="160" value="<?=e($val('tagline'))?>" placeholder="Cuisine méditerranéenne · Street food tunisienne"></label>
    </div>
    <label class="field"><span>Description (référencement & réseaux)</span><textarea name="description" rows="3" maxlength="400"><?=e($val('description'))?></textarea></label>
    <label class="field"><span>Texte « Notre histoire »</span><textarea name="about_text" rows="4" maxlength="600"><?=e($val('about_text'))?></textarea></label>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">02</p><h3>Contact & adresse</h3></div></header>
    <div class="field-row">
      <label class="field"><span>Téléphone affiché</span><input name="phone" maxlength="40" value="<?=e($val('phone'))?>" placeholder="+39 379 210 7500"></label>
      <label class="field"><span>WhatsApp <i>chiffres uniquement</i></span><input name="whatsapp" maxlength="30" value="<?=e($val('whatsapp'))?>" placeholder="393792107500"></label>
    </div>
    <label class="field"><span>Adresse e-mail <i>facultatif</i></span><input type="email" name="email" maxlength="150" value="<?=e($val('email'))?>"></label>
    <label class="field"><span>Adresse postale</span><input name="address" maxlength="255" value="<?=e($val('address'))?>"></label>
    <div class="field-row">
      <label class="field"><span>Latitude</span><input name="lat" maxlength="20" value="<?=e($val('lat'))?>" placeholder="44.80"></label>
      <label class="field"><span>Longitude</span><input name="lng" maxlength="20" value="<?=e($val('lng'))?>" placeholder="10.33"></label>
    </div>
    <label class="field"><span>Lien d’intégration de la carte (Google Maps)</span><input name="maps_embed" maxlength="500" value="<?=e($val('maps_embed'))?>" placeholder="https://www.google.com/maps?q=…&output=embed"></label>
  </section>

  <section class="card">
    <header class="card-head"><div><p class="eyebrow">03</p><h3>Textes de la carte</h3></div></header>
    <label class="field"><span>Note sous la carte <i>suppléments, précisions</i></span><textarea name="menu_note" rows="2" maxlength="400"><?=e($val('menu_note'))?></textarea></label>
    <label class="field"><span>Citation / proverbe</span><input name="quote_text" maxlength="200" value="<?=e($val('quote_text'))?>" placeholder="À Tunis, on ne compte pas les plats, on compte les convives."></label>
  </section>

  <div class="form-actions">
    <button class="abtn gold">Enregistrer les informations <?=$arrow?></button>
    <span class="muted small">Les changements sont visibles immédiatement sur le site.</span>
  </div>
</form>

<section class="card" id="horaires">
  <header class="card-head"><div><p class="eyebrow">04</p><h3>Horaires d’ouverture</h3></div></header>
  <form method="post" action="/admin/horaires/enregistrer" class="form">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <div class="table-wrap">
      <table class="tbl hours-tbl">
        <thead><tr><th>Jour</th><th>Service 1</th><th>Service 2</th><th class="ta-c">Fermé</th></tr></thead>
        <tbody>
        <?php foreach ($days as $num => $label): $row = $byDay[$num] ?? []; ?>
          <tr>
            <td><b><?=e($label)?></b></td>
            <td>
              <div class="time-pair">
                <input type="time" name="hours[<?=$num?>][open1]" value="<?=e($t($row['open1'] ?? ''))?>" aria-label="Ouverture <?=e($label)?>">
                <span>–</span>
                <input type="time" name="hours[<?=$num?>][close1]" value="<?=e($t($row['close1'] ?? ''))?>" aria-label="Fermeture <?=e($label)?>">
              </div>
            </td>
            <td>
              <div class="time-pair">
                <input type="time" name="hours[<?=$num?>][open2]" value="<?=e($t($row['open2'] ?? ''))?>" aria-label="Ouverture soir <?=e($label)?>">
                <span>–</span>
                <input type="time" name="hours[<?=$num?>][close2]" value="<?=e($t($row['close2'] ?? ''))?>" aria-label="Fermeture soir <?=e($label)?>">
              </div>
            </td>
            <td class="ta-c">
              <label class="check"><input type="checkbox" name="hours[<?=$num?>][closed]" value="1"<?= !empty($row['closed']) ? ' checked' : ''?>><span class="sr-only">Fermé</span></label>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="form-actions"><button class="abtn gold">Enregistrer les horaires</button></div>
  </form>
</section>

<section class="card" id="reseaux">
  <header class="card-head"><div><p class="eyebrow">05</p><h3>Réseaux sociaux</h3></div></header>
  <?php if ($socials): ?>
    <ul class="social-list">
      <?php foreach ($socials as $so): ?>
        <li>
          <form method="post" action="/admin/reseaux/enregistrer" class="form social-form">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$so['id'] ?>">
            <input name="network" maxlength="30" value="<?=e($so['network'])?>" aria-label="Réseau" placeholder="instagram">
            <input name="url" maxlength="255" value="<?=e($so['url'])?>" aria-label="Adresse" placeholder="https://…">
            <button class="abtn line sm">Enregistrer</button>
          </form>
          <form method="post" action="/admin/reseaux/supprimer" data-confirm="Supprimer ce réseau social ?">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$so['id'] ?>">
            <button class="icon-btn danger" title="Supprimer">Suppr.</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="empty">Aucun réseau social renseigné.</p>
  <?php endif; ?>

  <form method="post" action="/admin/reseaux/enregistrer" class="form social-form add-social">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <input name="network" maxlength="30" placeholder="Réseau : instagram, google, tripadvisor…" aria-label="Nouveau réseau">
    <input name="url" maxlength="255" placeholder="https://…" aria-label="Adresse du réseau">
    <button class="abtn gold sm">Ajouter</button>
  </form>
</section>
<?php forget_old(); ?>
