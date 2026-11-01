<?php
/** @var array $stats @var array $visits @var int $maxVisits @var array $reservations @var array $messages @var array $categories @var array|null $daily @var string $today */
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$statusLabel = ['new' => 'Nouvelle', 'confirmed' => 'Confirmée', 'cancelled' => 'Annulée'];
?>
<section class="pg-head">
  <div>
    <p class="eyebrow"><?=e(fr_date($today, true))?></p>
    <h2 class="pg-title">La maison,<br><em>en un regard.</em></h2>
    <p class="pg-sub">Tout ce qui fait vivre le Sultan : la carte, le plat du jour et les demandes de vos hôtes.</p>
  </div>
  <div class="pg-acts">
    <a class="abtn gold" href="/admin/carte?new=1">Ajouter un plat</a>
    <a class="abtn line" href="/admin/plat-du-jour">Plat du jour</a>
  </div>
</section>

<div class="stat-grid">
  <a class="stat" href="/admin/carte">
    <span class="stat-label">Plats à la carte</span>
    <b class="stat-value"><?= (int)$stats['dishes'] ?></b>
    <span class="stat-hint"><?= (int)$stats['dishes_on'] ?> disponibles · <?= (int)$stats['categories'] ?> catégories</span>
  </a>
  <a class="stat" href="/admin/demandes?type=reservations">
    <span class="stat-label">Réservations à traiter</span>
    <b class="stat-value"><?= (int)$stats['res_new'] ?></b>
    <span class="stat-hint">nouvelles demandes</span>
  </a>
  <a class="stat" href="/admin/demandes?type=messages">
    <span class="stat-label">Messages reçus</span>
    <b class="stat-value"><?= (int)$stats['messages'] ?></b>
    <span class="stat-hint"><?= (int)$stats['newsletter'] ?> inscrits à la lettre</span>
  </a>
  <a class="stat" href="/admin/demandes?type=newsletter">
    <span class="stat-label">Visites · 7 jours</span>
    <b class="stat-value"><?= (int)$stats['visits_week'] ?></b>
    <span class="stat-hint"><?= (int)$stats['visits_today'] ?> aujourd’hui</span>
  </a>
</div>

<div class="dash-grid">
  <div class="stack">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Fréquentation</p><h3>Deux semaines de visites</h3></div>
        <span class="badge-soft"><?= count($visits) ?> jours</span>
      </header>
      <?php if ($visits): ?>
        <div class="chart" role="img" aria-label="Visites des quatorze derniers jours">
          <?php foreach ($visits as $v): $h = (int)round(((int)$v['visits'] / $maxVisits) * 100); ?>
            <div class="chart-col" title="<?=e(fr_date($v['day']))?> · <?= (int)$v['visits'] ?> visites">
              <span class="chart-bar" style="height:<?=max(4, $h)?>%"></span>
              <small><?= (int)date('j', strtotime($v['day'])) ?></small>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="empty">Les statistiques apparaîtront dès les premières visites du site.</p>
      <?php endif; ?>
    </section>

    <section class="card card-daily">
      <header class="card-head">
        <div><p class="eyebrow">Aujourd’hui à la table</p><h3>Plat du jour</h3></div>
        <a class="lnk sm" href="/admin/plat-du-jour">Gérer <?=$arrow?></a>
      </header>
      <?php if ($daily && !empty($daily['name'])): ?>
        <div class="daily-row">
          <?php if (!empty($daily['image'])): ?>
            <img class="daily-img" src="<?=e($daily['image'])?>" alt="">
          <?php else: ?>
            <span class="daily-img empty-img">Plat</span>
          <?php endif; ?>
          <div class="daily-body">
            <p class="daily-meta">
              <?php if ($daily['active']): ?><span class="tag ok">En ligne</span><?php else: ?><span class="tag off">Hors ligne</span><?php endif; ?>
              <span><?=e(fr_date($daily['served_on'] ?? ''))?></span>
            </p>
            <h4><?=e($daily['name'])?></h4>
            <p class="daily-ing"><?=e($daily['ingredients'] ?? '')?></p>
            <b class="daily-price"><?=e(money($daily['price']))?></b>
          </div>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <p>Aucun plat du jour n’est défini. Mettez en avant la création du Chef dès aujourd’hui.</p>
          <a class="abtn gold sm" href="/admin/plat-du-jour">Définir le plat du jour</a>
        </div>
      <?php endif; ?>
    </section>

    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Équilibre</p><h3>Répartition de la carte</h3></div>
        <a class="lnk sm" href="/admin/categories">Catégories <?=$arrow?></a>
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
      <?php if (!$categories): ?><p class="empty">Créez votre première catégorie pour structurer la carte.</p><?php endif; ?>
    </section>
  </div>

  <div class="stack">
    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Service</p><h3>Dernières réservations</h3></div>
        <a class="lnk sm" href="/admin/demandes?type=reservations">Tout voir <?=$arrow?></a>
      </header>
      <?php if ($reservations): ?>
        <ul class="list">
          <?php foreach ($reservations as $r): ?>
            <li class="list-row">
              <div>
                <b><?=e($r['name'])?></b>
                <small><?=e(fr_date($r['rdate']))?> · <?=e(substr((string)$r['rtime'], 0, 5))?> · <?= (int)$r['guests'] ?> pers.</small>
              </div>
              <span class="tag <?=$r['status'] === 'new' ? 'warn' : ($r['status'] === 'confirmed' ? 'ok' : 'off')?>"><?=e($statusLabel[$r['status']] ?? $r['status'])?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="empty">Aucune réservation pour le moment.</p>
      <?php endif; ?>
    </section>

    <section class="card">
      <header class="card-head">
        <div><p class="eyebrow">Correspondance</p><h3>Derniers messages</h3></div>
        <a class="lnk sm" href="/admin/demandes?type=messages">Tout voir <?=$arrow?></a>
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
        <p class="empty">Aucun message reçu.</p>
      <?php endif; ?>
    </section>

    <?php if ((int)$stats['reviews'] > 0): ?>
      <section class="card card-alert">
        <p class="eyebrow">À valider</p>
        <h3><?= (int)$stats['reviews'] ?> avis en attente</h3>
        <p class="muted">Publiez-les pour les faire apparaître sur le site.</p>
        <a class="abtn line sm" href="/admin/avis">Modérer les avis <?=$arrow?></a>
      </section>
    <?php endif; ?>
  </div>
</div>
