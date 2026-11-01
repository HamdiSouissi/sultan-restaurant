<?php
/**
 * Crée un compte d'administration ou remplace son mot de passe.
 * Usage : php tools/admin-password.php email@exemple.com 'mot-de-passe'
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(403); exit("Ce script s'exécute uniquement en ligne de commande.\n"); }

require __DIR__.'/../app/Database.php';

$email = $argv[1] ?? '';
$pass  = $argv[2] ?? '';

if ($email === '' || $pass === '') {
  fwrite(STDERR, "Usage : php tools/admin-password.php email@exemple.com 'mot-de-passe'\n");
  exit(1);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  fwrite(STDERR, "Adresse e-mail invalide.\n");
  exit(1);
}
if (strlen($pass) < 10) {
  fwrite(STDERR, "Le mot de passe doit contenir au moins 10 caractères.\n");
  exit(1);
}

try {
  Database::run(
    'INSERT INTO admins(email, password_hash) VALUES(?, ?) ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)',
    [mb_strtolower($email), password_hash($pass, PASSWORD_DEFAULT)]
  );
} catch (Throwable $e) {
  fwrite(STDERR, 'Erreur : '.$e->getMessage()."\n");
  exit(2);
}

echo "Compte administrateur prêt : ".mb_strtolower($email)."\n";
echo "Connexion : /admin/login\n";
