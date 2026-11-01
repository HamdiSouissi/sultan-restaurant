<?php
/** @var array $stats @var array $visits @var int $maxVisits @var array $reservations @var array $messages @var array $categories @var array|null $daily @var string $today */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$statusLabel = ['new' => 'Nuova', 'confirmed' => 'Confermata', 'cancelled' => 'Annullata'];
?>
<section class="pg-head">
  <div>
    <p class="eyebrow"><?=e(it_date($today, true))?></p>
    <h2 class="pg-title">La casa,<br><em>in uno sguardo.</em></h2>
    <p class="pg-sub">Tutto ciò che fa vivere il Sultan: il menù, il piatto del giorno e le richieste dei vostri ospiti.</p>
  </div>
  <div class="pg-acts">
    <a class="abtn gold" href="/admin/carte?new=1#editeur">Aggiungi un piatto</a>
    <a class="abtn line" href="/admin/plat-du-jour">Piatto del giorno</a>
  </div>
</section>

<div class="stat-grid">
  <a class="stat" href="/admin/carte">
    <span class="stat-label">Piatti nel menù</span>
    <b class="stat-value"><?= (int)$stats['dishes'] ?></b>
    <span class="stat-hint"><?= (int)$stats['dishes_on'] ?> disponibili · <?= (int)$stats['categories'] ?> categorie</span>
  </a>
  <a class="stat" href="/admin/demandes?type=reservations">
    <span class="stat-label">Prenotazioni da gestire</span>
    <b class="stat-value"><?= (int)$stats['res_new'] ?></b>
    <span class="stat-hint">nuove richieste</span>
  </a>
  <a class="stat" href="/admin/demandes?type=messages">
    <span class="stat-label">Messaggi ricevuti</span>
    <b class="stat-value"><?= (int)$stats['messages'] ?></b>
    <span class="stat-hint"><?= (int)$stats['newsletter'] ?> iscritti alla newsletter</span>
  </a>
  <a class="stat" href="/admin/demandes?type=newsletter">
    <span class="stat-label">Visite · 7 giorni</span>
    <b class="stat-value"><?= (int)$stats['visits_week'] ?></b>
    <span class="stat-hint"><?= (int)$stats['visits_today'] ?> oggi</span>
  </a>
</div>

<div class="dash-grid">
  <div class="stack">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Affluenza</p><h3>Due settimane di visite</h3></div>
        <span class="badge-soft"><?= count($visits) ?> giorni</span>
      </header>
      <?php if ($visits): ?>
        <div class="chart" role="img" aria-label="Visite degli ultimi quattordici giorni">
          <?php foreach ($visits as $v): $h = (int)round(((int)$v['visits'] / $maxVisits) * 100); ?>
            <div class="chart-col" title="<?=e(it_date($v['day']))?> · <?= (int)$v['visits'] ?> visites">
              <span class="chart-bar" style="height:<?=max(4, $h)?>%"></span>
              <small><?= (int)date('j', strtotime($v['day'])) ?></small>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="empty">Le statistiche compariranno con le prime visite del sito.</p>
      <?php endif; ?>
    </section>

    <section class="card card-daily">
      <header class="card-head">
        <div><p class="eyebrow">Oggi in tavola</p><h3>Piatto del giorno</h3></div>
        <a class="lnk sm" href="/admin/plat-du-jour">Gestisci <?=$arrow?></a>
      </header>
      <?php if ($daily && !empty($daily['name'])): ?>
        <div class="daily-row">
          <?php if (!empty($daily['image'])): ?>
            <img class="daily-img" src="<?=e($daily['image'])?>" alt="">
          <?php else: ?>
            <span class="daily-img empty-img">Piatto</span>
          <?php endif; ?>
          <div class="daily-body">
            <p class="daily-meta">
              <?php if ($daily['active']): ?><span class="tag ok">Online</span><?php else: ?><span class="tag off">Offline</span><?php endif; ?>
              <span><?=e(it_date($daily['served_on'] ?? ''))?></span>
            </p>
            <h4><?=e($daily['name'])?></h4>
            <p class="daily-ing"><?=e($daily['ingredients'] ?? '')?></p>
            <b class="daily-price"><?=e(money($daily['price']))?></b>
          </div>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <p>Nessun piatto del giorno impostato. Mettete in evidenza la creazione dello Chef già da oggi.</p>
          <a class="abtn gold sm" href="/admin/plat-du-jour">Imposta il piatto del giorno</a>
        </div>
      <?php endif; ?>
    </section>

    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Equilibrio</p><h3>Composizione del menù</h3></div>
        <a class="lnk sm" href="/admin/categories">Categorie <?=$arrow?></a>
      </header>
      <?php $total = max(1, (int)$stats['dishes']); ?>
      <ul class="bars">
        <?php foreach ($categories as $c): ?>
          <li>
            <span class="bars-label"><?=e($c['name'])?></span>
            <span class="bars-track"><i style="width:<?=round(((int)$c['n'] / $total) * 100)?>%"></i></span>
            <b><?= (int)$c['n'] ?></b>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php if (!$categories): ?><p class="empty">Create la prima categoria per organizzare il menù.</p><?php endif; ?>
    </section>
  </div>

  <div class="stack">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Servizio</p><h3>Ultime prenotazioni</h3></div>
        <a class="lnk sm" href="/admin/demandes?type=reservations">Vedi tutto <?=$arrow?></a>
      </header>
      <?php if ($reservations): ?>
        <ul class="list">
          <?php foreach ($reservations as $r): ?>
            <li class="list-row">
              <div>
                <b><?=e($r['name'])?></b>
                <small><?=e(it_date($r['rdate']))?> · <?=e(substr((string)$r['rtime'], 0, 5))?> · <?= (int)$r['guests'] ?> pers.</small>
              </div>
              <span class="tag <?=$r['status'] === 'new' ? 'warn' : ($r['status'] === 'confirmed' ? 'ok' : 'off')?>"><?=e($statusLabel[$r['status']] ?? $r['status'])?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="empty">Nessuna prenotazione per ora.</p>
      <?php endif; ?>
    </section>

    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Corrispondenza</p><h3>Ultimi messaggi</h3></div>
        <a class="lnk sm" href="/admin/demandes?type=messages">Vedi tutto <?=$arrow?></a>
      </header>
      <?php if ($messages): ?>
        <ul class="list">
          <?php foreach ($messages as $m): ?>
            <li class="list-row stack-sm">
              <b><?=e($m['name'])?></b>
              <small><?=e(mb_substr((string)$m['message'], 0, 90))?><?=mb_strlen((string)$m['message']) > 90 ? '…' : ''?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="empty">Nessun messaggio ricevuto.</p>
      <?php endif; ?>
    </section>

    <?php if ((int)$stats['reviews'] > 0): ?>
      <section class="card card-alert">
        <p class="eyebrow">Da approvare</p>
        <h3><?= (int)$stats['reviews'] ?> recensioni in attesa</h3>
        <p class="muted">Pubblicatele per farle comparire sul sito.</p>
        <a class="abtn line sm" href="/admin/avis">Modera le recensioni <?=$arrow?></a>
      </section>
    <?php endif; ?>
  </div>
</div>
