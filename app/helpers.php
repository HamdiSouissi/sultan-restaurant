<?php
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_ok(): bool { return isset($_POST['csrf'],$_SESSION['csrf']) && hash_equals($_SESSION['csrf'],$_POST['csrf']); }
function json(array $d, int $code=200): never { http_response_code($code); header('Content-Type: application/json'); echo json_encode($d,JSON_UNESCAPED_UNICODE); exit; }
function throttle(string $k, int $max, int $sec): bool {
  $_SESSION['t'][$k] = array_filter($_SESSION['t'][$k] ?? [], fn($t)=>$t>time()-$sec);
  if (count($_SESSION['t'][$k]) >= $max) return false;
  $_SESSION['t'][$k][] = time(); return true;
}

/* ------------------------------------------------------------------ *
 *  Administration — utilitaires partagés
 * ------------------------------------------------------------------ */

function redirect(string $to, int $code = 303): never {
  $to = preg_replace('/[\r\n]+/', '', $to);
  if (!headers_sent()) header('Location: '.$to, true, $code);
  exit;
}

function flash(string $type, string $message): void {
  $_SESSION['flash'][] = ['t' => $type, 'm' => $message];
}

function flashes(): array {
  $list = $_SESSION['flash'] ?? [];
  unset($_SESSION['flash']);
  return is_array($list) ? $list : [];
}

/** Garde la saisie d'un formulaire refusé le temps d'une redirection. */
function keep_old(array $data): void { $_SESSION['old'] = $data; }

function old(string $key, $default = '') {
  $v = $_SESSION['old'][$key] ?? $default;
  return is_scalar($v) ? (string)$v : $default;
}

function forget_old(): void { unset($_SESSION['old']); }

function money($v): string {
  return number_format((float)$v, 2, ',', ' ').' €';
}

function it_date(?string $date, bool $withYear = false): string {
  if (!$date || $date === '0000-00-00') return '';
  $ts = strtotime($date);
  if (!$ts) return '';
  $days = ['domenica','lunedì','martedì','mercoledì','giovedì','venerdì','sabato'];
  $months = [1=>'gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];
  $out = $days[(int)date('w', $ts)].' '.date('j', $ts).' '.$months[(int)date('n', $ts)];
  return $withYear ? $out.' '.date('Y', $ts) : $out;
}

/** « 1 piatto », « 3 piatti » : plurale italiano corretto per i contatori. */
function it_count(int $n, string $singular, string $plural): string {
  return $n.' '.($n === 1 ? $singular : $plural);
}

function slugify(string $text): string {
  $map = ['à'=>'a','á'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','æ'=>'ae','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
          'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','œ'=>'oe',
          'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','ß'=>'ss'];
  $slug = strtr(mb_strtolower(trim($text), 'UTF-8'), $map);
  $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
  $slug = trim($slug, '-');
  return $slug !== '' ? $slug : 'categoria';
}

function admin_url(string $path = ''): string {
  $path = trim($path, '/');
  return '/admin'.($path !== '' ? '/'.$path : '');
}

/**
 * Valide un chemin d'image : fichier du site (/assets/…) ou URL http(s).
 * Empêche toute injection dans un attribut style/src.
 */
function clean_path(?string $path): ?string {
  $path = trim((string)$path);
  if ($path === '') return null;
  if (preg_match('#^/assets/[A-Za-z0-9._%/\-]+$#', $path)) return $path;
  if (preg_match('#^https://[A-Za-z0-9._~:/?\#\[\]@!$&()*+,;=%-]+$#i', $path)) return $path;
  return null;
}

/**
 * Téléverse une image depuis $_FILES[$field] et renvoie son chemin public.
 * Lève une RuntimeException lisible en cas de problème.
 */
function upload_image(string $field): ?string {
  if (!isset($_FILES[$field]) || !is_array($_FILES[$field])) return null;
  $f = $_FILES[$field];
  $err = (int)($f['error'] ?? UPLOAD_ERR_NO_FILE);
  if ($err === UPLOAD_ERR_NO_FILE) return null;
  if ($err !== UPLOAD_ERR_OK) throw new RuntimeException('Invio dell’immagine non riuscito (codice '.$err.').');
  if (!is_uploaded_file($f['tmp_name'])) throw new RuntimeException('File temporaneo non valido.');
  if (($f['size'] ?? 0) > 5 * 1024 * 1024) throw new RuntimeException('Immagine troppo pesante: 5 MB al massimo.');
  $info = @getimagesize($f['tmp_name']);
  $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif'];
  if (!$info || !isset($types[$info[2]])) throw new RuntimeException('Formato non supportato (JPG, PNG o WEBP).');
  $dir = dirname(__DIR__).'/public/assets/uploads';
  if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) throw new RuntimeException('Cartella di caricamento non accessibile.');
  $name = bin2hex(random_bytes(10)).'.'.$types[$info[2]];
  if (!move_uploaded_file($f['tmp_name'], $dir.'/'.$name)) throw new RuntimeException('Impossibile salvare l’immagine.');
  @chmod($dir.'/'.$name, 0644);
  return '/assets/uploads/'.$name;
}

/** Supprime un fichier téléversé (uniquement dans /assets/uploads/). */
function remove_upload(?string $path): void {
  if (!$path || !str_starts_with($path, '/assets/uploads/')) return;
  $dir = realpath(dirname(__DIR__).'/public/assets/uploads');
  $file = realpath(dirname(__DIR__).'/public'.$path);
  if ($dir && $file && str_starts_with($file, $dir.DIRECTORY_SEPARATOR) && is_file($file)) @unlink($file);
}
