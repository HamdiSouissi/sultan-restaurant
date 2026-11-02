<?php
/**
 * Controlla che i contenuti del database siano in italiano.
 * Segnala i piatti, le categorie, i testi e le didascalie che contengono
 * ancora parole francesi (tipico di una migrazione parziale).
 *
 * Uso : php tools/check-italian.php
 * Uscita: 0 se tutto è in italiano, 1 se restano dei testi francesi.
 */
declare(strict_types=1);

/* Refus d'exécution depuis le web (Apache, FPM, CGI…). */
if (in_array(PHP_SAPI, ['apache2handler', 'fpm-fcgi', 'cgi-fcgi', 'litespeed', 'cgi'], true)) {
  http_response_code(403);
  exit("Questo script si esegue solo da riga di comando.\n");
}

require __DIR__.'/../app/Database.php';

/* Parole che in italiano non esistono: se compaiono, il testo è francese. */
$mots = [
  'avec', 'vous', 'nous', 'votre', 'nos', 'pour', 'dans', 'sur', 'des', 'du', 'aux', 'une', 'et',
  'grillé', 'grillée', 'grillés', 'grillées', 'rôti', 'rôtie', 'haché', 'hachée', 'farcie', 'farci',
  'farcis', 'fromage', 'viande', 'poisson', 'œuf', 'oeuf', 'œufs', 'riz', 'semoule', 'huile', 'pain',
  'citron', 'oignon', 'oignons', 'persil', 'ail', 'beurre', 'poivre', 'crevettes', 'agneau', 'bœuf',
  'veau', 'poulet', 'jambon', 'frites', 'épices', 'miel', 'yaourt', 'amandes', 'figues', 'dattes',
  'menthe', 'salade', 'boisson', 'café', 'crème', 'carottes', 'navet', 'chiches', 'saucisse',
  'confit', 'feuilleté', 'beignet', 'flan', 'sélection', 'pièces', 'assortiment', 'suppléments',
  'maison', 'fraîche', 'sèches', 'blanche', 'croustillant', 'mijoté', 'garni', 'garnie', 'dorée',
  'nature', 'sauce', 'dressée', 'côtelettes', 'poivrons', 'légumes', 'assiette', 'carte', 'plat',
  'thon', 'crudités', 'galette', 'soupe', 'gratin', 'brochette', 'chèvre', 'pomme', 'terre',
  'julienne', 'blanquette', 'bouillon', 'sorbet', 'glace', 'tarte', 'gâteau', 'confiture',
];
$regex = '/(?<![\p{L}’\'])(?:'.implode('|', array_map('preg_quote', $mots)).')(?![\p{L}’\'])/iu';

$rows = [];
$add = function (string $table, string $column, $id, string $label) use (&$rows, $regex): void {
  if ($label === '') return;
  if (preg_match_all($regex, $label, $m)) {
    $rows[] = ['table' => $table, 'column' => $column, 'id' => $id, 'label' => $label, 'mots' => array_unique($m[0])];
  }
};

try {
  foreach (Database::all('SELECT id, name, description, allergens FROM dishes') as $d) {
    $add('dishes', 'name', $d['id'], (string)$d['name']);
    $add('dishes', 'description', $d['id'], (string)$d['description']);
    $add('dishes', 'allergens', $d['id'], (string)$d['allergens']);
  }
  foreach (Database::all('SELECT id, name FROM categories') as $c) {
    $add('categories', 'name', $c['id'], (string)$c['name']);
  }
  foreach (Database::all('SELECT k, v FROM settings') as $s) {
    $add('settings', 'v ('.$s['k'].')', $s['k'], (string)$s['v']);
  }
  foreach (Database::all('SELECT id, alt FROM gallery') as $g) {
    $add('gallery', 'alt', $g['id'], (string)$g['alt']);
  }
  try {
    foreach (Database::all('SELECT id, name, description, ingredients FROM daily_special') as $p) {
      $add('daily_special', 'name', $p['id'], (string)$p['name']);
      $add('daily_special', 'description', $p['id'], (string)$p['description']);
      $add('daily_special', 'ingredients', $p['id'], (string)$p['ingredients']);
    }
  } catch (Throwable $e) {
    // Table absente : l'installation n'a pas encore de plat du jour.
  }
} catch (Throwable $e) {
  fwrite(STDERR, 'Base de données inaccessible : '.$e->getMessage()."\n");
  exit(2);
}

if (!$rows) {
  echo "Tutto il contenuto è in italiano.\n";
  exit(0);
}

echo count($rows)." testo/i ancora in francese:\n\n";
foreach ($rows as $r) {
  printf("  %-14s %-18s #%-4s %s\n     → %s\n", $r['table'], $r['column'], $r['id'], $r['label'], implode(', ', $r['mots']));
}
echo "\nCorrezione automatica: importare sql/migration_it.sql (phpMyAdmin).\n";
echo "Controllo manuale: ogni singolo piatto è modificabile in /admin/carte.\n";
exit(1);
