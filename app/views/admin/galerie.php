<?php /** @var array $items */ $arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>'; ?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Le palais en images</p>
    <h2 class="pg-title">La <em>galerie.</em></h2>
    <p class="pg-sub"><?= count($items) ?> images composent le mur de la maison sur la page d’accueil.</p>
  </div>
</section>

<div class="gallery-grid">
  <?php foreach ($items as $item): ?>
    <figure class="gal-card">
      <div class="gal-thumb"><img src="<?=e($item['path'])?>" alt="" loading="lazy"></div>
      <form method="post" action="/admin/galerie/enregistrer" class="form gal-form" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
        <input type="hidden" name="path" value="<?=e($item['path'])?>">
        <label class="field"><span>Légende</span><input name="alt" maxlength="160" value="<?=e($item['alt'])?>" placeholder="Décrire l’image"></label>
        <label class="field"><span>Position</span><input type="number" name="sort" min="0" max="999" value="<?= (int)$item['sort'] ?>"></label>
        <div class="field">
          <span>Remplacer l’image <i>facultatif</i></span>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="gal-acts">
          <button class="abtn line sm">Enregistrer</button>
        </div>
      </form>
      <form method="post" action="/admin/galerie/supprimer" class="gal-del" data-confirm="Retirer cette image de la galerie ?">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
        <button class="icon-btn danger" title="Supprimer">Supprimer</button>
      </form>
    </figure>
  <?php endforeach; ?>
</div>
<?php if (!$items): ?>
  <p class="empty">Aucune image. Ajoutez vos plus belles tables ci-dessous.</p>
<?php endif; ?>

<section class="card">
  <header class="card-head"><div><p class="eyebrow">Nouvelle image</p><h3>Ajouter à la galerie</h3></div></header>
  <form method="post" action="/admin/galerie/enregistrer" class="form" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <div class="field-row">
      <label class="field"><span>Fichier image</span><input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
      <label class="field"><span>ou chemin existant</span><input name="path" placeholder="/assets/img/terrasse.jpg"><i class="hint">Les images du site sont dans /assets/img/</i></label>
    </div>
    <div class="field-row">
      <label class="field"><span>Légende</span><input name="alt" maxlength="160" placeholder="Table dressée face au Bosphore"></label>
      <label class="field"><span>Position</span><input type="number" name="sort" min="0" max="999" placeholder="22"></label>
    </div>
    <button class="abtn gold">Ajouter l’image <?=$arrow?></button>
  </form>
</section>
