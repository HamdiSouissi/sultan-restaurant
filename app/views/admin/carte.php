<?php
/** @var array $dishes @var array $categories @var array|null $editing @var string $q @var int $cat @var bool $new */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$hasOld = isset($_SESSION['old']);
$dish = $editing ?? [
  'id' => 0, 'category_id' => $categories[0]['id'] ?? 0, 'name' => '', 'description' => '',
  'ingredients' => '', 'allergens' => '', 'price' => '', 'image' => null, 'featured' => 0, 'available' => 1,
];
$isEditor = $editing !== null || $new;
$v = fn(string $k, $d = '') => old($k, (string)($dish[$k] ?? $d));
$isChecked = fn(string $k, $fallback) => $hasOld ? isset($_SESSION['old'][$k]) : (bool)$fallback;
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">La carte · <?= count($dishes) ?> plat<?= count($dishes) > 1 ? 's' : '' ?></p>
    <h2 class="pg-title">Composer <em>le menu.</em></h2>
    <p class="pg-sub">Chaque plat, son prix, sa composition et sa disponibilité du jour.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/carte?new=1">Nouveau plat</a></div>
</section>

<form class="toolbar" method="get" action="/admin/carte">
  <input type="search" name="q" value="<?=e($q)?>" placeholder="Rechercher un plat ou un ingrédient…" aria-label="Rechercher">
  <select name="cat" aria-label="Filtrer par catégorie">
    <option value="0">Toutes les catégories</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int)$c['id'] ?>"<?= $cat === (int)$c['id'] ? ' selected' : ''?>><?=e($c['name'])?></option>
    <?php endforeach; ?>
  </select>
  <button class="abtn line sm">Filtrer</button>
  <?php if ($q !== '' || $cat > 0): ?><a class="lnk sm" href="/admin/carte">Réinitialiser</a><?php endif; ?>
</form>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head">
      <div><p class="eyebrow">Plats</p><h3>La carte actuelle</h3></div>
    </header>
    <?php if (!$dishes): ?>
      <div class="empty-state">
        <p>Aucun plat ne correspond à cette recherche.</p>
        <a class="abtn gold sm" href="/admin/carte?new=1">Créer un plat</a>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table class="tbl">
          <thead>
            <tr><th>Plat</th><th>Catégorie</th><th class="ta-r">Prix</th><th class="ta-c">Dispo</th><th class="ta-c">Cœur</th><th></th></tr>
          </thead>
          <tbody>
          <?php foreach ($dishes as $d): ?>
            <tr<?= $editing && (int)$editing['id'] === (int)$d['id'] ? ' class="is-editing"' : ''?>>
              <td>
                <div class="media-cell">
                  <?php if (!empty($d['image'])): ?>
                    <img src="<?=e($d['image'])?>" alt="">
                  <?php else: ?>
                    <span class="thumb-empty">✦</span>
                  <?php endif; ?>
                  <div>
                    <b><?=e($d['name'])?></b>
                    <?php if (!empty($d['description'])): ?><small><?=e($d['description'])?></small><?php endif; ?>
                  </div>
                </div>
              </td>
              <td class="muted"><?=e($d['cat_name'])?></td>
              <td class="ta-r price"><?=e(money($d['price']))?></td>
              <td class="ta-c">
                <form method="post" action="/admin/carte/basculer">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <input type="hidden" name="field" value="available">
                  <button class="switch<?= $d['available'] ? ' on' : '' ?>" aria-label="Basculer la disponibilité" title="Disponible / épuisé"><i></i></button>
                </form>
              </td>
              <td class="ta-c">
                <form method="post" action="/admin/carte/basculer">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <input type="hidden" name="field" value="featured">
                  <button class="switch heart<?= $d['featured'] ? ' on' : '' ?>" aria-label="Mettre en avant" title="Mettre en avant"><i></i></button>
                </form>
              </td>
              <td class="ta-r row-acts">
                <a class="icon-btn" href="/admin/carte?edit=<?= (int)$d['id'] ?>" title="Modifier">Modifier</a>
                <form method="post" action="/admin/carte/supprimer" data-confirm="Supprimer « <?=e($d['name'])?> » de la carte ?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <button class="icon-btn danger" title="Supprimer">Suppr.</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <section class="card panel-edit" id="editeur">
    <?php if (!$isEditor): ?>
      <div class="editor-empty">
        <span class="ornament">✦</span>
        <h3>Modifier un plat</h3>
        <p class="muted">Sélectionnez un plat dans la liste pour en changer le prix, la composition ou la photo.</p>
        <a class="abtn line sm" href="/admin/carte?new=1">Ou créer un nouveau plat</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div>
          <p class="eyebrow"><?= $editing ? 'Édition' : 'Nouveau plat' ?></p>
          <h3><?= $editing ? e($dish['name']) : 'Une nouvelle création' ?></h3>
        </div>
        <a class="lnk sm" href="/admin/carte">Fermer</a>
      </header>
      <form method="post" action="/admin/carte/enregistrer" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$dish['id'] ?>">
        <input type="hidden" name="image_path" value="<?=e((string)($dish['image'] ?? ''))?>">
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <label class="field">
          <span>Nom du plat</span>
          <input name="name" required maxlength="120" value="<?=e($v('name'))?>" placeholder="Couscous agneau">
        </label>

        <div class="field-row">
          <label class="field">
            <span>Catégorie</span>
            <select name="category_id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>"<?= (int)$v('category_id', '0') === (int)$c['id'] ? ' selected' : ''?>><?=e($c['name'])?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="field">
            <span>Prix (€)</span>
            <input name="price" required inputmode="decimal" value="<?=e($v('price'))?>" placeholder="9,00">
          </label>
        </div>

        <label class="field">
          <span>Description courte</span>
          <input name="description" maxlength="255" value="<?=e($v('description'))?>" placeholder="Semoule, légumes, agneau">
        </label>

        <label class="field">
          <span>Composition / ingrédients</span>
          <textarea name="ingredients" rows="3" maxlength="255" placeholder="Semoule roulée à la main, agneau mijoté, pois chiches, carottes, harissa maison"><?=e($v('ingredients'))?></textarea>
        </label>

        <label class="field">
          <span>Allergènes <i>facultatif</i></span>
          <input name="allergens" maxlength="255" value="<?=e($v('allergens'))?>" placeholder="gluten, œufs">
        </label>

        <div class="field">
          <span>Photo du plat</span>
          <?php if (!empty($dish['image'])): ?>
            <div class="current-img">
              <img src="<?=e($dish['image'])?>" alt="">
              <label class="check"><input type="checkbox" name="remove_image" value="1"><span>Retirer la photo</span></label>
            </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <i class="hint">JPG, PNG ou WEBP · 5 Mo maximum</i>
        </div>

        <div class="switch-row">
          <label class="check"><input type="checkbox" name="available" value="1"<?= $isChecked('available', $dish['available']) ? ' checked' : ''?>><span>Disponible à la carte</span></label>
          <label class="check"><input type="checkbox" name="featured" value="1"<?= $isChecked('featured', $dish['featured']) ? ' checked' : ''?>><span>Mise en avant (carrousel)</span></label>
        </div>

        <button class="abtn gold full"><?= $editing ? 'Enregistrer les modifications' : 'Ajouter à la carte' ?> <?=$arrow?></button>
      </form>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
