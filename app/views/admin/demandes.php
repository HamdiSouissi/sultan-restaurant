<?php
/** @var string $type @var string $status @var array $counts @var array $reservations @var array $messages @var array $newsletter */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$labels = ['new' => 'Nuova', 'confirmed' => 'Confermata', 'cancelled' => 'Annullata'];
$tabs = [
  'reservations' => ['Prenotazioni', (int)$counts['reservations']],
  'messages'     => ['Messaggi', (int)$counts['messages']],
  'newsletter'   => ['Newsletter', (int)$counts['newsletter']],
];
?>
<section class="pg-head">
  <div>
    <p class="eyebrow">I vostri ospiti</p>
    <h2 class="pg-title">Le <em>richieste.</em></h2>
    <p class="pg-sub">Prenotazioni, messaggi e iscrizioni: gestitele da un unico posto.</p>
  </div>
  <div class="pg-acts"><a class="abtn line" href="/admin">Pannello di controllo</a></div>
</section>

<nav class="seg" aria-label="Tipo di richiesta">
  <?php foreach ($tabs as $key => [$label, $count]): ?>
    <a class="seg-item<?= $type === $key ? ' on' : ''?>" href="/admin/demandes?type=<?=$key?>"><?=e($label)?> <span><?=$count?></span></a>
  <?php endforeach; ?>
</nav>

<?php if ($type === 'reservations'): ?>
  <section class="card">
    <header class="card-head">
      <div><p class="eyebrow">Servizio</p><h3>Prenotazioni</h3></div>
      <div class="chips">
        <a class="chip<?= $status === '' ? ' on' : ''?>" href="/admin/demandes?type=reservations">Tutte</a>
        <a class="chip<?= $status === 'new' ? ' on' : ''?>" href="/admin/demandes?type=reservations&status=new">Nuove</a>
        <a class="chip<?= $status === 'confirmed' ? ' on' : ''?>" href="/admin/demandes?type=reservations&status=confirmed">Confermate</a>
        <a class="chip<?= $status === 'cancelled' ? ' on' : ''?>" href="/admin/demandes?type=reservations&status=cancelled">Annullate</a>
      </div>
    </header>
    <?php if ($reservations): ?>
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>Ospite</th><th>Contatto</th><th>Data</th><th class="ta-c">Coperti</th><th>Note</th><th class="ta-c">Stato</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($reservations as $r): ?>
            <tr>
              <td><b><?=e($r['name'])?></b><small class="block muted"><?=e(it_date($r['created'] ?? '', true))?></small></td>
              <td>
                <a class="lnk sm" href="tel:<?=e(preg_replace('/\s+/', '', (string)$r['phone']))?>"><?=e($r['phone'])?></a>
                <?php if (!empty($r['email'])): ?><small class="block muted"><?=e($r['email'])?></small><?php endif; ?>
              </td>
              <td><?=e(it_date($r['rdate']))?><small class="block muted"><?=e(substr((string)$r['rtime'], 0, 5))?></small></td>
              <td class="ta-c"><?= (int)$r['guests'] ?></td>
              <td class="muted small"><?=e($r['note'] ?: '—')?></td>
              <td class="ta-c">
                <span class="tag <?=$r['status'] === 'new' ? 'warn' : ($r['status'] === 'confirmed' ? 'ok' : 'off')?>"><?=e($labels[$r['status']] ?? $r['status'])?></span>
              </td>
              <td class="ta-r row-acts">
                <?php if ($r['status'] !== 'confirmed'): ?>
                  <form method="post" action="/admin/demandes/statut">
                    <input type="hidden" name="csrf" value="<?=csrf()?>">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input type="hidden" name="status" value="confirmed">
                    <button class="icon-btn ok">Conferma</button>
                  </form>
                <?php endif; ?>
                <?php if ($r['status'] !== 'cancelled'): ?>
                  <form method="post" action="/admin/demandes/statut">
                    <input type="hidden" name="csrf" value="<?=csrf()?>">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <input type="hidden" name="status" value="cancelled">
                    <button class="icon-btn">Annulla</button>
                  </form>
                <?php endif; ?>
                <form method="post" action="/admin/demandes/supprimer" data-confirm="Eliminare definitivamente questa prenotazione?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="type" value="reservation">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <button class="icon-btn danger">Elimina</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="empty">Nessuna prenotazione in questa categoria.</p>
    <?php endif; ?>
  </section>

<?php elseif ($type === 'messages'): ?>
  <section class="card">
    <header class="card-head"><div><p class="eyebrow">Corrispondenza</p><h3>Messaggi dal modulo</h3></div></header>
    <?php if ($messages): ?>
      <ul class="msg-list">
        <?php foreach ($messages as $m): ?>
          <li class="msg-item">
            <div class="msg-head">
              <div><b><?=e($m['name'])?></b><a class="muted small" href="mailto:<?=e($m['email'])?>"><?=e($m['email'])?></a></div>
              <span class="muted small"><?=e(it_date($m['created'] ?? '', true))?></span>
            </div>
            <p><?=nl2br(e($m['message']))?></p>
            <form method="post" action="/admin/demandes/supprimer" data-confirm="Eliminare questo messaggio?">
              <input type="hidden" name="csrf" value="<?=csrf()?>">
              <input type="hidden" name="type" value="message">
              <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
              <button class="icon-btn danger">Elimina</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="empty">Nessun messaggio ricevuto per ora.</p>
    <?php endif; ?>
  </section>

<?php else: ?>
  <section class="card">
    <header class="card-head">
      <div><p class="eyebrow">Notiziario</p><h3>Iscritti</h3></div>
      <a class="abtn line sm" href="/admin/export/newsletter">Esporta in CSV <?=$arrow?></a>
    </header>
    <?php if ($newsletter): ?>
      <div class="table-wrap">
        <table class="tbl">
          <thead><tr><th>Indirizzo e-mail</th><th>Iscrizione</th><th></th></tr></thead>
          <tbody>
          <?php foreach ($newsletter as $n): ?>
            <tr>
              <td><a class="lnk sm" href="mailto:<?=e($n['email'])?>"><?=e($n['email'])?></a></td>
              <td class="muted"><?=e(it_date($n['created'] ?? '', true))?></td>
              <td class="ta-r">
                <form method="post" action="/admin/demandes/supprimer" data-confirm="Rimuovere questo iscritto?">
                  <input type="hidden" name="csrf" value="<?=csrf()?>">
                  <input type="hidden" name="type" value="newsletter">
                  <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                  <button class="icon-btn danger">Rimuovi</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="empty">Nessun iscritto per ora.</p>
    <?php endif; ?>
  </section>
<?php endif; ?>
