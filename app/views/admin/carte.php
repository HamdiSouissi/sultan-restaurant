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
    <p class="eyebrow">Il menù · <?= count($dishes) ?> piatto<?= count($dishes) > 1 ? 'i' : '' ?></p>
    <h2 class="pg-title">Comporre <em>il menù.</em></h2>
    <p class="pg-sub">Ogni piatto, il suo prezzo, la sua composizione e la disponibilità del giorno.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/carte?new=1#editeur">Nuovo piatto</a></div>
</section>

<form class="toolbar" method="get" action="/admin/carte">
  <input type="search" name="q" value="<?=e($q)?>" placeholder="Cerca un piatto o un ingrediente…" aria-label="Cerca">
  <select name="cat" aria-label="Filtra per categoria">
    <option value="0">Tutte le categorie</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int)$c['id'] ?>"<?= $cat === (int)$c['id'] ? ' selected' : ''?>><?=e($c['name'])?></option>
    <?php endforeach; ?>
  </select>
  <button class="abtn line sm">Filtra</button>
  <?php if ($q !== '' || $cat > 0): ?><a class="lnk sm" href="/admin/carte">Azzera</a><?php endif; ?>
</form>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head">
      <div><p class="eyebrow">Piatti</p><h3>Il menù attuale</h3></div>
    </header>
    <?php if (!$dishes): ?>
      <div class="empty-state">
        <p>Nessun piatto corrisponde a questa ricerca.</p>
        <a class="abtn gold sm" href="/admin/carte?new=1#editeur">Crea un piatto</a>
      </div>
    <?php else: ?>
      <div class="table-wrap">
        <table class="tbl">
          <thead>
            <tr><th>Piatto</th><th>Categoria</th><th class="ta-r">Prezzo</th><th class="ta-c">Disp.</th><th class="ta-c">Cuore</th><th></th></tr>
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
                  <button class="switch<?= $d['available'] ? ' on' : '' ?>" aria-label="Cambia la disponibilità" title="Disponibile / esaurito"><i></i></button>
                </form>
              </td>
              <td class="ta-c">
                <form method="post" action="/admin/carte/basculer">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <input type="hidden" name="field" value="featured">
                  <button class="switch heart<?= $d['featured'] ? ' on' : '' ?>" aria-label="Metti in evidenza" title="Metti in evidenza"><i></i></button>
                </form>
              </td>
              <td class="ta-r row-acts">
                <a class="icon-btn" href="/admin/carte?edit=<?= (int)$d['id'] ?>#editeur" title="Modifica">Modifica</a>
                <form method="post" action="/admin/carte/supprimer" data-confirm="Eliminare « <?=e($d['name'])?> » dal menù?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                  <button class="icon-btn danger" title="Elimina">Elimina</button>
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
        <h3>Modificare un piatto</h3>
        <p class="muted">Scegliete un piatto dall’elenco per cambiarne il prezzo, la composizione o la foto.</p>
        <a class="abtn line sm" href="/admin/carte?new=1#editeur">Oppure create un nuovo piatto</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div>
          <p class="eyebrow"><?= $editing ? 'Modifica' : 'Nuovo piatto' ?></p>
          <h3><?= $editing ? e($dish['name']) : 'Una nuova creazione' ?></h3>
        </div>
        <a class="lnk sm" href="/admin/carte">Chiudi</a>
      </header>
      <form method="post" action="/admin/carte/enregistrer" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$dish['id'] ?>">
        <input type="hidden" name="image_path" value="<?=e((string)($dish['image'] ?? ''))?>">
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <label class="field">
          <span>Nome del piatto</span>
          <input name="name" required maxlength="120" value="<?=e($v('name'))?>" placeholder="Couscous all’agnello">
        </label>

        <div class="field-row">
          <label class="field">
            <span>Categoria</span>
            <select name="category_id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>"<?= (int)$v('category_id', '0') === (int)$c['id'] ? ' selected' : ''?>><?=e($c['name'])?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="field">
            <span>Prezzo (€)</span>
            <input name="price" required inputmode="decimal" value="<?=e($v('price'))?>" placeholder="9,00">
          </label>
        </div>

        <label class="field">
          <span>Descrizione breve</span>
          <input name="description" maxlength="255" value="<?=e($v('description'))?>" placeholder="Semolino, verdure, agnello">
        </label>

        <label class="field">
          <span>Composizione / ingredienti</span>
          <textarea name="ingredients" rows="3" maxlength="255" placeholder="Semolino arrotolato a mano, agnello stufato, ceci, carote, harissa della casa"><?=e($v('ingredients'))?></textarea>
        </label>

        <label class="field">
          <span>Allergeni <i>facoltativo</i></span>
          <input name="allergens" maxlength="255" value="<?=e($v('allergens'))?>" placeholder="glutine, uova">
        </label>

        <div class="field">
          <span>Foto del piatto</span>
          <?php if (!empty($dish['image'])): ?>
            <div class="current-img">
              <img src="<?=e($dish['image'])?>" alt="">
              <label class="check"><input type="checkbox" name="remove_image" value="1"><span>Rimuovi la foto</span></label>
            </div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
          <i class="hint">JPG, PNG o WEBP · 5 MB al massimo</i>
        </div>

        <div class="switch-row">
          <label class="check"><input type="checkbox" name="available" value="1"<?= $isChecked('available', $dish['available']) ? ' checked' : ''?>><span>Disponibile nel menù</span></label>
          <label class="check"><input type="checkbox" name="featured" value="1"<?= $isChecked('featured', $dish['featured']) ? ' checked' : ''?>><span>In evidenza (carosello)</span></label>
        </div>

        <button class="abtn gold full"><?= $editing ? 'Salva le modifiche' : 'Aggiungi al menù' ?> <?=$arrow?></button>
      </form>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
