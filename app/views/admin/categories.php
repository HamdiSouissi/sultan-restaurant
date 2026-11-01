<?php
/** @var array $categories @var array|null $editing @var bool $isNew @var int $maxSort */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$hasOld = isset($_SESSION['old']);
$cat = $editing ?? ['id' => 0, 'name' => '', 'slug' => '', 'sort' => $maxSort + 1];
$isEditor = $editing !== null || $isNew;
$v = fn(string $k, $d = '') => old($k, (string)($cat[$k] ?? $d));
$total = 0;
foreach ($categories as $c) $total += (int)$c['n'];
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Structurer la carte</p>
    <h2 class="pg-title">Les <em>familles</em> du menu.</h2>
    <p class="pg-sub"><?= count($categories) ?> catégories · <?= $total ?> plats rangés par famille.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/categories?new=1#editeur">Nouvelle catégorie</a></div>
</section>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head">
      <div><p class="eyebrow">Ordre d’affichage</p><h3>Catégories</h3></div>
      <span class="badge-soft">glisser vers le haut / bas</span>
    </header>
    <?php if (!$categories): ?>
      <div class="empty-state">
        <p>Aucune catégorie. Créez « Entrées », « Grillades », « Couscous »… pour commencer.</p>
        <a class="abtn gold sm" href="/admin/categories?new=1#editeur">Créer une catégorie</a>
      </div>
    <?php else: ?>
      <ul class="catlist">
        <?php foreach ($categories as $i => $c): ?>
          <li class="catitem<?= $editing && (int)$editing['id'] === (int)$c['id'] ? ' is-editing' : ''?>">
            <span class="catitem-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="catitem-main">
              <b><?=e($c['name'])?></b>
              <small>/<?=e($c['slug'])?> · <?= (int)$c['n'] ?> plat<?= (int)$c['n'] > 1 ? 's' : ''?></small>
            </div>
            <div class="catitem-acts">
              <form method="post" action="/admin/categories/deplacer">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <input type="hidden" name="dir" value="up">
                <button class="icon-btn" title="Monter"<?= $i === 0 ? ' disabled' : ''?>>↑</button>
              </form>
              <form method="post" action="/admin/categories/deplacer">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <input type="hidden" name="dir" value="down">
                <button class="icon-btn" title="Descendre"<?= $i === count($categories) - 1 ? ' disabled' : ''?>>↓</button>
              </form>
              <a class="icon-btn" href="/admin/categories?edit=<?= (int)$c['id'] ?>#editeur">Modifier</a>
              <form method="post" action="/admin/categories/supprimer" data-confirm="Supprimer la catégorie « <?=e($c['name'])?> » ?">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="icon-btn danger" title="Supprimer">Suppr.</button>
              </form>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <section class="card panel-edit" id="editeur">
    <?php if (!$isEditor): ?>
      <div class="editor-empty">
        <span class="ornament">✦</span>
        <h3>Catégories de la carte</h3>
        <p class="muted">Les catégories organisent les onglets du menu. Choisissez un nom clair et un ordre d’affichage.</p>
        <a class="abtn line sm" href="/admin/categories?new=1#editeur">Créer une catégorie</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div>
          <p class="eyebrow"><?= $editing ? 'Édition' : 'Nouvelle catégorie' ?></p>
          <h3><?= $editing ? e($cat['name']) : 'Une nouvelle famille' ?></h3>
        </div>
        <a class="lnk sm" href="/admin/categories">Fermer</a>
      </header>
      <form method="post" action="/admin/categories/enregistrer" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
        <label class="field">
          <span>Nom de la catégorie</span>
          <input name="name" required maxlength="80" value="<?=e($v('name'))?>" placeholder="Grillades">
        </label>
        <label class="field">
          <span>Identifiant d’URL <i>facultatif</i></span>
          <input name="slug" maxlength="80" value="<?=e($v('slug'))?>" placeholder="grillades">
          <i class="hint">Laissez vide pour le générer depuis le nom.</i>
        </label>
        <label class="field">
          <span>Position</span>
          <input name="sort" type="number" min="1" max="999" value="<?=e($v('sort'))?>">
        </label>
        <button class="abtn gold full"><?= $editing ? 'Enregistrer' : 'Créer la catégorie' ?> <?=$arrow?></button>
      </form>

      <?php if ($editing): ?>
        <div class="danger-zone">
          <p class="eyebrow">Zone sensible</p>
          <h4>Supprimer cette catégorie</h4>
          <p class="muted"><?= (int)$editing['n'] > 0 ? 'Cette catégorie contient '.(int)$editing['n'].' plat(s).' : 'Cette catégorie est vide.'?></p>
          <form method="post" action="/admin/categories/supprimer" class="form" data-confirm="Supprimer définitivement « <?=e($editing['name'])?> » ?">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
            <?php if ((int)$editing['n'] > 0): ?>
              <label class="check"><input type="checkbox" name="force" value="1" required><span>Supprimer aussi les <?= (int)$editing['n'] ?> plat(s) associés</span></label>
            <?php endif; ?>
            <button class="abtn danger full">Supprimer la catégorie</button>
          </form>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
