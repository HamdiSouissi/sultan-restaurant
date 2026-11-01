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
    <p class="eyebrow">Il cartello del giorno</p>
    <h2 class="pg-title">Il piatto <em>del giorno.</em></h2>
    <p class="pg-sub">Un solo piatto, messo in luce sulla pagina iniziale: nome, prezzo e composizione.</p>
  </div>
  <div class="pg-acts">
    <?php if ($row && !empty($row['name'])): ?>
      <?php if (!empty($row['active'])): ?>
        <span class="tag ok">Online</span>
      <?php else: ?>
        <span class="tag off">Offline</span>
      <?php endif; ?>
      <a class="abtn line" href="/#plat-du-jour" target="_blank" rel="noopener">Vedi sul sito</a>
    <?php endif; ?>
  </div>
</section>

<?php if (!$supported): ?>
  <section class="card">
    <div class="empty-state">
      <span class="ornament">✦</span>
      <h3>Attivazione necessaria</h3>
      <p>La tabella <code>daily_special</code> non esiste ancora. Importate il file <code>sql/migration_admin.sql</code> in phpMyAdmin e ricaricate questa pagina.</p>
    </div>
  </section>
<?php else: ?>
  <div class="split-edit wide-right">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Modifica</p><h3>Comporre il piatto del giorno</h3></div>
      </header>
      <form method="post" action="/admin/plat-du-jour/enregistrer" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="image_path" value="<?=e((string)($d['image'] ?? ''))?>">

        <label class="field">
          <span>Nome del piatto</span>
          <input name="name" required maxlength="120" value="<?=e($v('name'))?>" placeholder="Ojja con merguez dello Chef">
        </label>

        <div class="field-row">
          <label class="field">
            <span>Prezzo (€)</span>
            <input name="price" required inputmode="decimal" value="<?=e($v('price'))?>" placeholder="8,50">
          </label>
          <label class="field">
            <span>Data di servizio</span>
            <input type="date" name="served_on" value="<?=e($v('served_on', date('Y-m-d')))?>">
          </label>
        </div>

        <label class="field">
          <span>Composizione</span>
          <textarea name="ingredients" rows="4" required maxlength="400" placeholder="Uova in camicia, merguez grigliate, peperoni confit, pomodori freschi, harissa della casa, pane di semolino"><?=e($v('ingredients'))?></textarea>
          <i class="hint">Questo testo appare sotto il nome del piatto nella pagina iniziale.</i>
        </label>

        <label class="field">
          <span>Descrizione breve <i>facoltativo</i></span>
          <input name="description" maxlength="255" value="<?=e($v('description'))?>" placeholder="La cucina di strada, servita a tavola">
        </label>

        <label class="field">
          <span>Allergeni <i>facoltativo</i></span>
          <input name="allergens" maxlength="255" value="<?=e($v('allergens'))?>" placeholder="uova, glutine">
        </label>

        <div class="field">
          <span>Foto</span>
          <?php if (!empty($d['image'])): ?>
            <div class="current-img">
              <img loading="lazy" decoding="async" src="<?=e(img_url($d['image']))?>" alt="">
              <label class="check"><input type="checkbox" name="remove_image" value="1"><span>Rimuovi la foto</span></label>
            </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <i class="hint">JPG, PNG o WEBP · 5 MB al massimo</i>
        </div>

        <label class="check"><input type="checkbox" name="active" value="1"<?= $active ? ' checked' : ''?>><span>Mostra sul sito</span></label>

        <button class="abtn gold full">Pubblica il piatto del giorno <?=$arrow?></button>
      </form>

      <?php if ($row && !empty($row['active'])): ?>
        <form method="post" action="/admin/plat-du-jour/retirer" class="inline-form" data-confirm="Rimuovere il piatto del giorno dal sito?">
          <input type="hidden" name="csrf" value="<?=csrf()?>">
          <button class="lnk danger sm">Rimuovi dal sito</button>
        </form>
      <?php endif; ?>
    </section>

    <section class="card preview-card">
      <header class="card-head"><div><p class="eyebrow">Anteprima</p><h3>Sulla pagina iniziale</h3></div></header>
      <?php $pvName = old('name', (string)($d['name'] ?? '')); $pvPrice = old('price', (string)($d['price'] ?? '')); $pvIng = old('ingredients', (string)($d['ingredients'] ?? '')); ?>
      <div class="site-preview">
        <div class="site-preview-media">
          <?php if (!empty($d['image'])): ?>
            <img loading="lazy" decoding="async" src="<?=e(img_url($d['image']))?>" alt="">
          <?php else: ?>
            <span>Foto del piatto</span>
          <?php endif; ?>
        </div>
        <div class="site-preview-body">
          <p class="sp-eyebrow"><i></i>Il piatto del giorno</p>
          <h4><?= $pvName !== '' ? e($pvName) : 'Nome del piatto' ?></h4>
          <p><?= $pvIng !== '' ? e($pvIng) : 'La composizione apparirà qui, parola per parola.' ?></p>
          <b><?= $pvPrice !== '' && is_numeric(str_replace(',', '.', $pvPrice)) ? e(money(str_replace(',', '.', $pvPrice))) : '0,00 €' ?></b>
        </div>
      </div>
      <p class="muted small">L’anteprima riprende l’impostazione reale del sito. Ricordatevi di salvare per pubblicare.</p>
    </section>
  </div>
<?php endif; ?>
<?php forget_old(); ?>
