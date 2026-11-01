<?php
/** @var array|null $row @var bool $supported */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$hasOld = isset($_SESSION['old']);
$d = $row ?? ['name' => '', 'description' => '', 'ingredients' => '', 'allergens' => '', 'price' => '',
              'image' => null, 'active' => 1, 'served_on' => date('Y-m-d')];
$v = fn(string $k, $fallback = '') => old($k, (string)($d[$k] ?? $fallback));
$active = $hasOld ? isset($_SESSION['old']['active']) : (bool)($d['active'] ?? 1);
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">L’ardoise du jour</p>
    <h2 class="pg-title">Le plat <em>du jour.</em></h2>
    <p class="pg-sub">Un seul plat, mis en lumière sur la page d’accueil : son nom, son prix et sa composition.</p>
  </div>
  <div class="pg-acts">
    <?php if ($row && !empty($row['name'])): ?>
      <?php if (!empty($row['active'])): ?>
        <span class="tag ok">En ligne</span>
      <?php else: ?>
        <span class="tag off">Hors ligne</span>
      <?php endif; ?>
      <a class="abtn line" href="/#plat-du-jour" target="_blank" rel="noopener">Voir sur le site</a>
    <?php endif; ?>
  </div>
</section>

<?php if (!$supported): ?>
  <section class="card">
    <div class="empty-state">
      <span class="ornament">✦</span>
      <h3>Activation nécessaire</h3>
      <p>La table <code>daily_special</code> n’existe pas encore. Importez le fichier <code>sql/migration_admin.sql</code> dans phpMyAdmin, puis rechargez cette page.</p>
    </div>
  </section>
<?php else: ?>
  <div class="split-edit wide-right">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Édition</p><h3>Composer le plat du jour</h3></div>
      </header>
      <form method="post" action="/admin/plat-du-jour/enregistrer" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="image_path" value="<?=e((string)($d['image'] ?? ''))?>">

        <label class="field">
          <span>Nom du plat</span>
          <input name="name" required maxlength="120" value="<?=e($v('name'))?>" placeholder="Ojja merguez du Chef">
        </label>

        <div class="field-row">
          <label class="field">
            <span>Prix (€)</span>
            <input name="price" required inputmode="decimal" value="<?=e($v('price'))?>" placeholder="8,50">
          </label>
          <label class="field">
            <span>Date de service</span>
            <input type="date" name="served_on" value="<?=e($v('served_on', date('Y-m-d')))?>">
          </label>
        </div>

        <label class="field">
          <span>Composition</span>
          <textarea name="ingredients" rows="4" required maxlength="400" placeholder="Œufs pochés, merguez grillées, poivrons confits, tomates fraîches, harissa maison, pain de semoule"><?=e($v('ingredients'))?></textarea>
          <i class="hint">Ce texte s’affiche sous le nom du plat sur la page d’accueil.</i>
        </label>

        <label class="field">
          <span>Description courte <i>facultatif</i></span>
          <input name="description" maxlength="255" value="<?=e($v('description'))?>" placeholder="La cuisine de rue, servie à table">
        </label>

        <label class="field">
          <span>Allergènes <i>facultatif</i></span>
          <input name="allergens" maxlength="255" value="<?=e($v('allergens'))?>" placeholder="œufs, gluten">
        </label>

        <div class="field">
          <span>Photo</span>
          <?php if (!empty($d['image'])): ?>
            <div class="current-img">
              <img src="<?=e($d['image'])?>" alt="">
              <label class="check"><input type="checkbox" name="remove_image" value="1"><span>Retirer la photo</span></label>
            </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <i class="hint">JPG, PNG ou WEBP · 5 Mo maximum</i>
        </div>

        <label class="check"><input type="checkbox" name="active" value="1"<?= $active ? ' checked' : ''?>><span>Afficher sur le site</span></label>

        <button class="abtn gold full">Publier le plat du jour <?=$arrow?></button>
      </form>

      <?php if ($row && !empty($row['active'])): ?>
        <form method="post" action="/admin/plat-du-jour/retirer" class="inline-form" data-confirm="Retirer le plat du jour du site ?">
          <input type="hidden" name="csrf" value="<?=csrf()?>">
          <button class="lnk danger sm">Retirer du site</button>
        </form>
      <?php endif; ?>
    </section>

    <section class="card preview-card">
      <header class="card-head"><div><p class="eyebrow">Aperçu</p><h3>Sur la page d’accueil</h3></div></header>
      <?php $pvName = old('name', (string)($d['name'] ?? '')); $pvPrice = old('price', (string)($d['price'] ?? '')); $pvIng = old('ingredients', (string)($d['ingredients'] ?? '')); ?>
      <div class="site-preview">
        <div class="site-preview-media">
          <?php if (!empty($d['image'])): ?>
            <img src="<?=e($d['image'])?>" alt="">
          <?php else: ?>
            <span>Photo du plat</span>
          <?php endif; ?>
        </div>
        <div class="site-preview-body">
          <p class="sp-eyebrow"><i></i>Le plat du jour</p>
          <h4><?= $pvName !== '' ? e($pvName) : 'Nom du plat' ?></h4>
          <p><?= $pvIng !== '' ? e($pvIng) : 'La composition apparaîtra ici, au fil des mots.' ?></p>
          <b><?= $pvPrice !== '' && is_numeric(str_replace(',', '.', $pvPrice)) ? e(money(str_replace(',', '.', $pvPrice))) : '0,00 €' ?></b>
        </div>
      </div>
      <p class="muted small">L’aperçu reprend la mise en page réelle du site. Pensez à enregistrer pour publier.</p>
    </section>
  </div>
<?php endif; ?>
<?php forget_old(); ?>
