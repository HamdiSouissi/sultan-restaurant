<?php /** @var array $items */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Il palazzo in immagini</p>
    <h2 class="pg-title">La <em>galleria.</em></h2>
    <p class="pg-sub"><?= count($items) ?> immagini compongono il muro della casa sulla pagina iniziale.</p>
  </div>
</section>

<div class="gallery-grid">
  <?php foreach ($items as $item): ?>
    <figure class="gal-card">
      <div class="gal-thumb"><img src="<?=e(img_url($item['path']))?>" alt="" loading="lazy" decoding="async"></div>
      <form method="post" action="/admin/galerie/enregistrer" class="form gal-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
        <input type="hidden" name="path" value="<?=e($item['path'])?>">
        <label class="field"><span>Didascalia</span><input name="alt" maxlength="160" value="<?=e($item['alt'])?>" placeholder="Descrivete l’immagine"></label>
        <label class="field"><span>Posizione</span><input type="number" name="sort" min="0" max="999" value="<?= (int)$item['sort'] ?>"></label>
        <div class="field">
          <span>Sostituisci l’immagine <i>facoltativo</i></span>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="gal-acts">
          <button class="abtn line sm">Salva</button>
        </div>
      </form>
      <form method="post" action="/admin/galerie/supprimer" class="gal-del" data-confirm="Rimuovere questa immagine dalla galleria?">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
        <button class="icon-btn danger" title="Elimina">Elimina</button>
      </form>
    </figure>
  <?php endforeach; ?>
</div>
<?php if (!$items): ?>
  <p class="empty">Nessuna immagine. Aggiungete le vostre tavole più belle qui sotto.</p>
<?php endif; ?>

<section class="card">
  <header class="card-head"><div><p class="eyebrow">Nuova immagine</p><h3>Aggiungi alla galleria</h3></div></header>
  <form method="post" action="/admin/galerie/enregistrer" class="form" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <div class="field-row">
      <label class="field"><span>File immagine</span><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
      <label class="field"><span>oppure percorso esistente</span><input name="path" placeholder="/assets/img/terrasse.jpg"><i class="hint">Le immagini del sito sono in /assets/img/</i></label>
    </div>
    <div class="field-row">
      <label class="field"><span>Didascalia</span><input name="alt" maxlength="160" placeholder="Tavola apparecchiata davanti al Bosforo"></label>
      <label class="field"><span>Posizione</span><input type="number" name="sort" min="0" max="999" placeholder="22"></label>
    </div>
    <button class="abtn gold">Aggiungi l’immagine <?=$arrow?></button>
  </form>
</section>
