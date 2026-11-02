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
    <p class="eyebrow">Reputazione</p>
    <h2 class="pg-title">Le <em>recensioni</em> della tavola.</h2>
    <p class="pg-sub">Pubblicate le testimonianze dei vostri ospiti: solo le recensioni approvate compaiono sul sito.</p>
  </div>
  <div class="pg-acts"><a class="abtn gold" href="/admin/avis?new=1#editeur">Aggiungi una recensione</a></div>
</section>

<div class="split-edit">
  <section class="card panel-list">
    <header class="card-head"><div><p class="eyebrow"><?= count($items) ?> recensioni</p><h3>Tutte le testimonianze</h3></div></header>
    <?php if (!$items): ?>
      <div class="empty-state"><p>Nessuna recensione registrata.</p><a class="abtn gold sm" href="/admin/avis?new=1#editeur">Aggiungi una recensione</a></div>
    <?php else: ?>
      <ul class="review-list">
        <?php foreach ($items as $r): ?>
          <li class="review-item<?= $editing && (int)$editing['id'] === (int)$r['id'] ? ' is-editing' : ''?>">
            <div class="review-top">
              <span class="stars"><?=str_repeat('★', (int)$r['rating'])?><span class="stars-off"><?=str_repeat('★', 5 - (int)$r['rating'])?></span></span>
              <span class="tag <?= $r['approved'] ? 'ok' : 'warn' ?>"><?= $r['approved'] ? 'Pubblicata' : 'In attesa' ?></span>
            </div>
            <p>« <?=e(mb_substr((string)$r['body'], 0, 180))?><?=mb_strlen((string)$r['body']) > 180 ? '…' : ''?> »</p>
            <div class="review-foot">
              <small><?=e($r['author'])?> · <?=e(ucfirst((string)$r['source']))?></small>
              <div class="row-acts">
                <a class="icon-btn" href="/admin/avis?edit=<?= (int)$r['id'] ?>#editeur">Modifica</a>
                <form method="post" action="/admin/avis/supprimer" data-confirm="Eliminare questa recensione?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button class="icon-btn danger">Elimina</button>
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
        <h3>Moderare le recensioni</h3>
        <p class="muted">Scegliete una recensione per correggerla, pubblicarla o rimuoverla.</p>
        <a class="abtn line sm" href="/admin/avis?new=1#editeur">Aggiungi una recensione manualmente</a>
      </div>
    <?php else: ?>
      <header class="card-head">
        <div><p class="eyebrow"><?= $editing ? 'Modifica' : 'Nuova recensione' ?></p><h3><?= $editing ? e($editing['author']) : 'Una testimonianza' ?></h3></div>
        <a class="lnk sm" href="/admin/avis">Chiudi</a>
      </header>
      <form method="post" action="/admin/avis/enregistrer" class="form">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        <input type="hidden" name="id" value="<?= (int)$review['id'] ?>">
        <div class="field-row">
          <label class="field"><span>Autore</span><input name="author" required maxlength="80" value="<?=e($v('author'))?>" placeholder="Amine B."></label>
          <label class="field"><span>Valutazione</span>
            <select name="rating">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?=$i?>"<?= (int)$v('rating', '5') === $i ? ' selected' : ''?>><?=str_repeat('★', $i)?> (<?=$i?>/5)</option>
              <?php endfor; ?>
            </select>
          </label>
        </div>
        <label class="field"><span>Fonte</span>
          <select name="source">
            <?php foreach (['google', 'tripadvisor', 'instagram', 'facebook', 'in sala'] as $src): ?>
              <option value="<?=e($src)?>"<?= $v('source') === $src ? ' selected' : ''?>><?=e(ucfirst($src))?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="field"><span>Testimonianza</span><textarea name="body" rows="5" required maxlength="2000" placeholder="Una tavola generosa, un’accoglienza rara…"><?=e($v('body'))?></textarea></label>
        <label class="check"><input type="checkbox" name="approved" value="1"<?= $approved ? ' checked' : ''?>><span>Pubblica sul sito</span></label>
        <button class="abtn gold full"><?= $editing ? 'Salva' : 'Aggiungi la recensione' ?> <?=$arrow?></button>
      </form>
    <?php endif; ?>
  </section>
</div>
<?php forget_old(); ?>
