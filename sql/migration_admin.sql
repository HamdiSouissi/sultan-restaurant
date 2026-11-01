-- =====================================================================
--  Sultan — migration « espace d'administration »
--  À exécuter une seule fois sur une base déjà installée (phpMyAdmin).
--  Sans risque : toutes les créations sont conditionnelles, les textes
--  déjà présents dans `settings` ne sont jamais écrasés.
-- =====================================================================
USE sultan;

-- Comptes d'administration
CREATE TABLE IF NOT EXISTS admins(
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) UNIQUE,
  password_hash VARCHAR(255)
);

-- Historique des tentatives de connexion (limitation de la force brute)
CREATE TABLE IF NOT EXISTS login_attempts(
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(created)
);

-- Plat du jour (une seule ligne, id = 1)
CREATE TABLE IF NOT EXISTS daily_special(
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description VARCHAR(255) NOT NULL DEFAULT '',
  ingredients VARCHAR(400) NOT NULL DEFAULT '',
  allergens VARCHAR(255) NOT NULL DEFAULT '',
  price DECIMAL(6,2) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  served_on DATE DEFAULT NULL,
  updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Textes éditables depuis l'administration
INSERT IGNORE INTO settings(k, v) VALUES
('about_text','Ispirata alle grandi case tunisine e ai palazzi ottomani, la nostra cucina racconta un paese di memoria: semolino arrotolato a mano, grigliate alla brace, brik dall’uovo morbido, tè servito prima dell’ordine.'),
('menu_note','Supplementi: uovo, tonno, salame, merguez, carne macinata, formaggio, patate fritte.'),
('quote_text','« A Tunisi non si contano i piatti, si contano gli ospiti. »');

-- Ensuite : ouvrez /admin dans le navigateur pour créer le premier compte.
-- Ligne de commande équivalente : php tools/admin-password.php email@exemple.com 'motdepasse'
