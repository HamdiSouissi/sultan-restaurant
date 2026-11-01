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
