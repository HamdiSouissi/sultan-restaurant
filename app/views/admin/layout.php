<?php
/** @var string $content @var string $title @var string $page @var string $shell @var array|null $admin @var string $brand */
$isGuest = ($shell ?? 'app') === 'guest';
$navItems = [
  ['admin/dashboard',      '/admin',           'Pannello di controllo'],
  ['admin/carte',          '/admin/carte',     'Il menù'],
  ['admin/categories',     '/admin/categories','Categorie'],
  ['admin/plat-du-jour',   '/admin/plat-du-jour','Piatto del giorno'],
  ['admin/infos',          '/admin/infos',     'Informazioni'],
  ['admin/galerie',        '/admin/galerie',   'Galleria'],
  ['admin/demandes',       '/admin/demandes',  'Richieste'],
  ['admin/avis',           '/admin/avis',      'Recensioni'],
  ['admin/compte',         '/admin/compte',    'Il mio account'],
];
$icons = [
  'admin/dashboard'     => '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M2.5 8.2 9 2.4l6.5 5.8V16h-4.8v-4.4H7.3V16H2.5z"/></svg>',
  'admin/carte'         => '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M3 4.2h12M3 9h12M3 13.8h7.5"/></svg>',
  'admin/categories'    => '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M2.8 2.8h5v5h-5zM10.2 2.8h5v5h-5zM2.8 10.2h5v5h-5zM10.2 10.2h5v5h-5z"/></svg>',
  'admin/plat-du-jour'  => '<svg viewBox="0 0 18 18" aria-hidden="true"><circle cx="9" cy="9" r="6.4"/><circle cx="9" cy="9" r="2.6"/></svg>',
  'admin/infos'         => '<svg viewBox="0 0 18 18" aria-hidden="true"><circle cx="9" cy="9" r="6.4"/><path d="M9 8.2v5M9 5.4v.4"/></svg>',
  'admin/galerie'       => '<svg viewBox="0 0 18 18" aria-hidden="true"><rect x="2.4" y="3.4" width="13.2" height="11.2"/><path d="m2.6 11.4 3.9-3.2 3 2.5 2.4-2 3.4 3"/></svg>',
  'admin/demandes'      => '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M2.8 4h12.4v8.4H6.6L3.8 15V4z"/></svg>',
  'admin/avis'          => '<svg viewBox="0 0 18 18" aria-hidden="true"><path d="m9 2.3 2.1 4.2 4.6.6-3.4 3.2.9 4.6L9 12.7l-4.2 2.2.9-4.6-3.4-3.2 4.6-.6z"/></svg>',
  'admin/compte'        => '<svg viewBox="0 0 18 18" aria-hidden="true"><circle cx="9" cy="6.4" r="3"/><path d="M3.4 15.4c0-2.9 2.5-4.5 5.6-4.5s5.6 1.6 5.6 4.5"/></svg>',
];
$arrow = '<svg class="ar" viewBox="0 0 12 12" width="11" height="11" aria-hidden="true"><path d="M2 10L10 2M4 2h6v6" fill="none" stroke="currentColor" stroke-width="1"/></svg>';
$cssVer = @filemtime(__DIR__.'/../../../public/assets/css/admin.css') ?: 1;
$jsVer  = @filemtime(__DIR__.'/../../../public/assets/js/admin.js') ?: 1;
?><!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<meta name="theme-color" content="#0e0d0c">
<title><?=e($title)?> · Amministrazione <?=e($brand)?></title>
<link rel="icon" href="/assets/img/logo.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/admin.css?v=<?=$cssVer?>">
</head>
<body class="adm<?=$isGuest ? ' is-guest' : ''?>">
<?php if ($isGuest): ?>
  <div class="guest-shell">
    <div class="guest-frame">
      <a class="guest-brand" href="/">
        <img src="/assets/img/logo.jpg" alt="" width="74" height="74">
        <span><b><?=e($brand)?></b><small>Spazio di gestione</small></span>
      </a>
      <div class="flash-zone">
        <?php foreach (flashes() as $f): ?>
          <div class="flash <?=$f['t'] === 'err' ? 'err' : 'ok'?>" role="status"><span><?=e($f['m'])?></span><button type="button" class="flash-x" aria-label="Chiudi">&times;</button></div>
        <?php endforeach; ?>
      </div>
      <?=$content?>
      <p class="guest-foot">Accesso riservato all’amministrazione · <?=date('Y')?></p>
    </div>
    <a class="guest-back" href="/">Torna al sito <?=$arrow?></a>
  </div>
<?php else: ?>
  <div class="adm-wrap">
    <aside class="sidebar" id="admSidebar">
      <div class="side-head">
        <a class="brand" href="/admin"><img src="/assets/img/logo.jpg" alt="" width="46" height="46"><span><b><?=e($brand)?></b><small>Amministrazione</small></span></a>
      </div>
      <nav class="navlist" aria-label="Navigazione dell’amministrazione">
        <?php foreach ($navItems as [$key, $href, $label]): $on = $page === $key; ?>
          <a class="navlink<?=$on ? ' on' : ''?>" href="<?=e($href)?>"<?=$on ? ' aria-current="page"' : ''?>> <?=$icons[$key] ?? ''?><span><?=e($label)?></span></a>
        <?php endforeach; ?>
      </nav>
      <div class="side-foot">
        <a class="site-link" href="/" target="_blank" rel="noopener">Vedi il sito <?=$arrow?></a>
        <div class="side-user">
          <span class="avatar"><?=e(mb_strtoupper(mb_substr((string)($admin['email'] ?? 'S'), 0, 1)))?></span>
          <span class="mail"><?=e($admin['email'] ?? '')?></span>
        </div>
        <form method="post" action="/admin/logout">
          <input type="hidden" name="csrf" value="<?=csrf()?>">
          <button class="logout">Esci</button>
        </form>
      </div>
    </aside>
    <div class="main">
      <header class="topbar">
        <button class="burger" id="admBurger" aria-label="Apri il menù" aria-expanded="false" aria-controls="admSidebar"><i></i><i></i><i></i></button>
        <div class="topbar-title">
          <p class="eyebrow">Amministrazione · <?=e($brand)?></p>
          <h1><?=e($title)?></h1>
        </div>
        <a class="abtn line sm hide-sm" href="/" target="_blank" rel="noopener">Vedi il sito <?=$arrow?></a>
      </header>
      <div class="flash-zone">
        <?php foreach (flashes() as $f): ?>
          <div class="flash <?=$f['t'] === 'err' ? 'err' : 'ok'?>" role="status"><span><?=e($f['m'])?></span><button type="button" class="flash-x" aria-label="Chiudi">&times;</button></div>
        <?php endforeach; ?>
      </div>
      <main class="content"><?=$content?></main>
      <footer class="adm-foot"><span><?=e($brand)?> · Spazio di gestione</span><span><?=date('Y')?></span></footer>
    </div>
    <div class="scrim" id="admScrim" hidden></div>
  </div>
<?php endif; ?>
<script src="/assets/js/admin.js?v=<?=$jsVer?>" defer></script>
</body>
</html>
