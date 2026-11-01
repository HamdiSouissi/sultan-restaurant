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
    <p class="eyebrow">Organizzare il menù</p>
    <h2 class="pg-title">Le <em>famiglie</em> del menù.</h2>
    <p class="pg-sub"><?= count($categories) ?> categorie · <?= $total ?> piatti divisi per famiglia.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/categories?new=1#editeur">Nuova categoria</a></div>
</section>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head">
      <div><p class="eyebrow">Ordine di visualizzazione</p><h3>Categorie</h3></div>
      <span class="badge-soft">sposta in alto / in basso</span>
    </header>
    <?php if (!$categories): ?>
      <div class="empty-state">
        <p>Nessuna categoria. Create «Antipasti», «Grigliate», «Couscous»… per cominciare.</p>
        <a class="abtn gold sm" href="/admin/categories?new=1#editeur">Crea una categoria</a>
      </div>
    <?php else: ?>
      <ul class="catlist">
        <?php foreach ($categories as $i => $c): ?>
          <li class="catitem<?= $editing && (int)$editing['id'] === (int)$c['id'] ? ' is-editing' : ''?>">
            <span class="catitem-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
            <div class="catitem-main">
              <b><?=e($c['name'])?></b>
              <small>/<?=e($c['slug'])?> · <?= (int)$c['n'] ?> piatto<?= (int)$c['n'] > 1 ? 'i' : ''?></small>
            </div>
            <div class="catitem-acts">
              <form method="post" action="/admin/categories/deplacer">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <input type="hidden" name="dir" value="up">
                <button class="icon-btn" title="Sposta in alto"<?= $i === 0 ? ' disabled' : ''?>>↑</button>
              </form>
              <form method="post" action="/admin/categories/deplacer">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <input type="hidden" name="dir" value="down">
                <button class="icon-btn" title="Sposta in basso"<?= $i === count($categories) - 1 ? ' disabled' : ''?>>↓</button>
              </form>
              <a class="icon-btn" href="/admin/categories?edit=<?= (int)$c['id'] ?>#editeur">Modifica</a>
              <form method="post" action="/admin/categories/supprimer" data-confirm="Eliminare la categoria « <?=e($c['name'])?> »?">
                <input type="hidden" name="csrf" value="<?=csrf()?>">
                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="icon-btn danger" title="Elimina">Elimina</button>
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
        <h3>Categorie del menù</h3>
        <p class="muted">Le categorie organizzano le schede del menù. Scegliete un nome chiaro e un ordine di visualizzazione.</p>
        <a class="abtn line sm" href="/admin/categories?new=1#editeur">Crea una categoria</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div>
          <p class="eyebrow"><?= $editing ? 'Modifica' : 'Nuova categoria' ?></p>
          <h3><?= $editing ? e($cat['name']) : 'Una nuova famiglia' ?></h3>
        </div>
        <a class="lnk sm" href="/admin/categories">Chiudi</a>
      </header>
      <form method="post" action="/admin/categories/enregistrer" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
        <label class="field">
          <span>Nome della categoria</span>
          <input name="name" required maxlength="80" value="<?=e($v('name'))?>" placeholder="Grigliate">
        </label>
        <label class="field">
          <span>Identificativo URL <i>facoltativo</i></span>
          <input name="slug" maxlength="80" value="<?=e($v('slug'))?>" placeholder="grigliate">
          <i class="hint">Lasciate vuoto per generarlo dal nome.</i>
        </label>
        <label class="field">
          <span>Posizione</span>
          <input name="sort" type="number" min="1" max="999" value="<?=e($v('sort'))?>">
        </label>
        <button class="abtn gold full"><?= $editing ? 'Salva' : 'Crea la categoria' ?> <?=$arrow?></button>
      </form>

      <?php if ($editing): ?>
        <div class="danger-zone">
          <p class="eyebrow">Zona sensibile</p>
          <h4>Eliminare questa categoria</h4>
          <p class="muted"><?= (int)$editing['n'] > 0 ? 'Questa categoria contiene '.(int)$editing['n'].' piatto/i.' : 'Questa categoria è vuota.'?></p>
          <form method="post" action="/admin/categories/supprimer" class="form" data-confirm="Eliminare definitivamente « <?=e($editing['name'])?> »?">
            <input type="hidden" name="csrf" value="<?=csrf()?>">
            <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
            <?php if ((int)$editing['n'] > 0): ?>
              <label class="check"><input type="checkbox" name="force" value="1" required><span>Elimina anche i <?= (int)$editing['n'] ?> piatto/i associati</span></label>
            <?php endif; ?>
            <button class="abtn danger full">Elimina la categoria</button>
          </form>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
