<?php
/** @var array $items @var array|null $editing @var bool $isNew */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$hasOld = isset($_SESSION['old']);
$review = $editing ?? ['id' => 0, 'author' => '', 'rating' => 5, 'body' => '', 'source' => 'google', 'approved' => 1];
$isEditor = $editing !== null || $isNew;
$v = fn(string $k, $d = '') => old($k, (string)($review[$k] ?? $d));
$approved = $hasOld ? isset($_SESSION['old']['approved']) : (bool)$review['approved'];
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">Réputation</p>
    <h2 class="pg-title">Les <em>avis</em> de la table.</h2>
    <p class="pg-sub">Publiez les témoignages de vos hôtes ; seuls les avis validés apparaissent sur le site.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/avis?new=1#editeur">Ajouter un avis</a></div>
</section>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head"><div><p class="eyebrow"><?= count($items) ?> avis</p><h3>Tous les témoignages</h3></div></header>
    <?php if (!$items): ?>
      <div class="empty-state"><p>Aucun avis enregistré.</p><a class="abtn gold sm" href="/admin/avis?new=1#editeur">Ajouter un avis</a></div>
    <?php else: ?>
      <ul class="review-list">
        <?php foreach ($items as $r): ?>
          <li class="review-item<?= $editing && (int)$editing['id'] === (int)$r['id'] ? ' is-editing' : ''?>">
            <div class="review-top">
              <span class="stars"><?=str_repeat('★', (int)$r['rating'])?><span class="stars-off"><?=str_repeat('★', 5 - (int)$r['rating'])?></span></span>
              <span class="tag <?= $r['approved'] ? 'ok' : 'warn' ?>"><?= $r['approved'] ? 'Publié' : 'En attente' ?></span>
            </div>
            <p>« <?=e(mb_substr((string)$r['body'], 0, 180))?><?=mb_strlen((string)$r['body']) > 180 ? '…' : ''?> »</p>
            <div class="review-foot">
              <small><?=e($r['author'])?> · <?=e(ucfirst((string)$r['source']))?></small>
              <div class="row-acts">
                <a class="icon-btn" href="/admin/avis?edit=<?= (int)$r['id'] ?>#editeur">Modifier</a>
                <form method="post" action="/admin/avis/supprimer" data-confirm="Supprimer cet avis ?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button class="icon-btn danger">Suppr.</button>
                </form>
              </div>
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
        <h3>Modérer les avis</h3>
        <p class="muted">Choisissez un avis pour le corriger, le publier ou le retirer.</p>
        <a class="abtn line sm" href="/admin/avis?new=1#editeur">Ajouter un avis manuellement</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div><p class="eyebrow"><?= $editing ? 'Édition' : 'Nouvel avis' ?></p><h3><?= $editing ? e($editing['author']) : 'Un témoignage' ?></h3></div>
        <a class="lnk sm" href="/admin/avis">Fermer</a>
      </header>
      <form method="post" action="/admin/avis/enregistrer" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$review['id'] ?>">
        <div class="field-row">
          <label class="field"><span>Auteur</span><input name="author" required maxlength="80" value="<?=e($v('author'))?>" placeholder="Amine B."></label>
          <label class="field"><span>Note</span>
            <select name="rating">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?=$i?>"<?= (int)$v('rating', '5') === $i ? ' selected' : ''?>><?=str_repeat('★', $i)?> (<?=$i?>/5)</option>
              <?php endfor; ?>
            </select>
          </label>
        </div>
        <label class="field"><span>Source</span>
          <select name="source">
            <?php foreach (['google', 'tripadvisor', 'instagram', 'facebook', 'sur place'] as $src): ?>
              <option value="<?=e($src)?>"<?= $v('source') === $src ? ' selected' : ''?>><?=e(ucfirst($src))?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="field"><span>Témoignage</span><textarea name="body" rows="5" required maxlength="2000" placeholder="Une table généreuse, un accueil rare…"><?=e($v('body'))?></textarea></label>
        <label class="check"><input type="checkbox" name="approved" value="1"<?= $approved ? ' checked' : ''?>><span>Publier sur le site</span></label>
        <button class="abtn gold full"><?= $editing ? 'Enregistrer' : 'Ajouter l’avis' ?> <?=$arrow?></button>
      </form>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
