<?php
declare(strict_types=1);
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]);
session_start();
header('X-Content-Type-Options: nosniff'); header('X-Frame-Options: SAMEORIGIN'); header('Referrer-Policy: strict-origin-when-cross-origin');
$path = (string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// Serveur de développement PHP : les fichiers statiques sont servis tels quels.
if (PHP_SAPI === 'cli-server' && $path !== '/') {
  $file = realpath(__DIR__.$path);
  if ($file && is_file($file) && str_starts_with($file, __DIR__.DIRECTORY_SEPARATOR)) return false;
}

foreach (['Database','helpers','Controllers/HomeController','Controllers/FormController','Controllers/AdminController'] as $f) require __DIR__."/../app/$f.php";

// Espace d'administration (session dédiée, pages non indexées).
if (str_starts_with($path, '/admin')) { header('Cache-Control: no-store, private'); (new AdminController)->dispatch($path); exit; }

$post = $_SERVER['REQUEST_METHOD']==='POST';
switch (true) {
  case $path==='/' && !$post: (new HomeController)->index(); break;
  case $path==='/reserver' && $post: (new FormController)->reserve(); break;
  case $path==='/contact' && $post: (new FormController)->contact(); break;
  case $path==='/newsletter' && $post: (new FormController)->newsletter(); break;
  case $path==='/robots.txt': header('Content-Type: text/plain'); echo "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: https://".$_SERVER['HTTP_HOST']."/sitemap.xml\n"; break;
  case $path==='/sitemap.xml': header('Content-Type: application/xml'); echo '<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://'.e($_SERVER['HTTP_HOST']).'/</loc></url></urlset>'; break;
  default: http_response_code(404); echo 'Pagina non trovata.';
}
