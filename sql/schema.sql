CREATE DATABASE IF NOT EXISTS sultan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sultan;
CREATE TABLE settings(k VARCHAR(60) PRIMARY KEY, v TEXT NOT NULL);
CREATE TABLE categories(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(80) NOT NULL, slug VARCHAR(80) UNIQUE, sort INT DEFAULT 0);
CREATE TABLE dishes(id INT AUTO_INCREMENT PRIMARY KEY, category_id INT NOT NULL, name VARCHAR(120) NOT NULL, description VARCHAR(255) DEFAULT '', price DECIMAL(6,2) NOT NULL, image VARCHAR(255) DEFAULT NULL, ingredients VARCHAR(255) DEFAULT '', allergens VARCHAR(255) DEFAULT '', featured TINYINT(1) DEFAULT 0, available TINYINT(1) DEFAULT 1, FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE CASCADE);
CREATE TABLE hours(day TINYINT PRIMARY KEY, open1 TIME, close1 TIME, open2 TIME NULL, close2 TIME NULL, closed TINYINT(1) DEFAULT 0);
CREATE TABLE gallery(id INT AUTO_INCREMENT PRIMARY KEY, path VARCHAR(255), alt VARCHAR(160), sort INT DEFAULT 0);
CREATE TABLE socials(id INT AUTO_INCREMENT PRIMARY KEY, network VARCHAR(30), url VARCHAR(255));
CREATE TABLE reviews(id INT AUTO_INCREMENT PRIMARY KEY, author VARCHAR(80), rating TINYINT, body TEXT, source VARCHAR(20) DEFAULT 'google', approved TINYINT(1) DEFAULT 1);
CREATE TABLE reservations(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), phone VARCHAR(30), email VARCHAR(150), rdate DATE, rtime TIME, guests TINYINT, note VARCHAR(255), status ENUM('new','confirmed','cancelled') DEFAULT 'new', created TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE contacts(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100), email VARCHAR(150), message TEXT, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE newsletter(id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(150) UNIQUE, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE admins(id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(150) UNIQUE, password_hash VARCHAR(255));
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
CREATE TABLE IF NOT EXISTS login_attempts(
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(created)
);
CREATE TABLE stats(day DATE PRIMARY KEY, visits INT DEFAULT 0);

INSERT INTO settings VALUES
('name','Sultan'),('tagline','Cucina mediterranea · Street food tunisino'),
('description','Couscous, tajine, grigliate e panini di strada preparati ogni giorno, tra Tunisi e Istanbul.'),
('phone','+39 379 210 7500'),('whatsapp','393792107500'),('email',''),
('address','Strada Massimo D''Azeglio, 77/A, 43125 Parma PR, Italia'),('lat','44.80'),('lng','10.33'),
('maps_embed','https://www.google.com/maps?q=Strada+Massimo+D%27Azeglio+77%2FA+43125+Parma&output=embed'),
('about_text','Ispirata alle grandi case tunisine e ai palazzi ottomani, la nostra cucina racconta un paese di memoria: semolino arrotolato a mano, grigliate alla brace, brik dall’uovo morbido, tè servito prima dell’ordine.'),
('menu_note','Supplementi: uovo, tonno, salame, merguez, carne macinata, formaggio, patate fritte.'),
('quote_text','« A Tunisi non si contano i piatti, si contano gli ospiti. »');
INSERT INTO categories(id,name,slug,sort) VALUES
(1,'Panini & street food','c1',1),
(2,'Antipasti & insalate','c2',2),
(3,'Piatti tunisini','c3',3),
(4,'Grigliate','c4',4),
(5,'Couscous','c5',5),
(6,'Pasta & riso','c6',6),
(7,'Tajine & bastila','c7',7),
(8,'Stufati del giorno','c8',8);
INSERT INTO dishes(category_id,name,description,price,allergens,featured,image) VALUES
(1,'Makloub al tonno','Piadina, tonno, verdure crude',6.5,'gluten, pesce',0,NULL),
(1,'Makloub con merguez','Piadina, merguez',7.5,'gluten',0,NULL),
(1,'Makloub al pollo','Piadina, pollo alla griglia',7.5,'gluten',0,NULL),
(1,'Makloub Sultan','Ripieno della casa',8.5,'gluten',0,NULL),
(1,'Baguette farcita al tonno','Tonno, uovo, olive, harissa',7.5,'gluten, pesce, uova',1,'/assets/img/baguette.jpg'),
(1,'Baguette farcita con carne macinata','Carne macinata, salse',8.5,'gluten',0,NULL),
(1,'Soffiato al pollo','Pasta sfogliata dorata, pollo',7.5,'gluten',0,NULL),
(1,'Panino kafteji','Panino, kafteji tunisino',6,'gluten',0,NULL),
(1,'Mlawi con merguez','Mlawi sfogliato, merguez grigliate',6,'gluten',1,'/assets/img/mlawi.jpg'),
(1,'Chapati con omelette','Chapati, omelette, formaggio',5,'gluten, uova, latte',1,'/assets/img/chapati.jpg'),
(1,'Tacos XL','3 carni, 2 tortillas',9.5,'gluten, latte',0,NULL),
(2,'Brik uovo e tonno','Sfoglia di brick, uovo morbido',2.5,'gluten, uova, pesce',1,'/assets/img/brik.jpg'),
(2,'Fricassé','Frittella salata farcita con tonno, uovo, olive',2.5,'gluten, uova, pesce',1,'/assets/img/fricasse.jpg'),
(2,'Tajine (1 pezzo)','Sformato tunisino alle uova',2,'uova',0,NULL),
(2,'Insalata tunisina','Verdure fresche, olio d’oliva',4,'',0,NULL),
(2,'Insalata mechouia','Peperoni grigliati, tonno, uovo',4,'uova, pesce',0,'/assets/img/mechouia.jpg'),
(2,'Carote al vapore (houria)','Carote, harissa, cumino',4,'',0,NULL),
(3,'Ojja con merguez','Uova, merguez, salsa piccante',8.5,'uova',1,'/assets/img/ojja.jpg'),
(3,'Ojja al pollo','Uova, pollo, salsa piccante',8.5,'uova',0,NULL),
(3,'Piatto tunisino','Assortimento del giorno',7.5,'gluten, uova',0,NULL),
(3,'Piatto kafteji','Verdure fritte, uovo, merguez',7.5,'uova',0,NULL),
(3,'Lablabi','Ceci, pane, uovo, capperi',6,'gluten, uova',0,'/assets/img/lablabi.jpg'),
(4,'Grigliata di pollo','2 pezzi',9.5,'',0,NULL),
(4,'Grigliata di carne macinata','3 pezzi',10,'',0,NULL),
(4,'Grigliata di merguez','3 pezzi',10,'',0,NULL),
(4,'Costolette d’agnello','2 pezzi',12,'',0,'/assets/img/cotelettes.jpg'),
(4,'Grigliata mista','Selezione di carni',15,'',0,NULL),
(4,'Grigliata di pesce','Pesce del giorno',12,'pesce',0,NULL),
(5,'Couscous all’agnello','Semolino, verdure, agnello',9,'gluten',1,'/assets/img/couscous.jpg'),
(5,'Couscous al pollo','Semolino, verdure, pollo',8,'gluten',0,NULL),
(5,'Couscous besbes','Semolino, salsa piccante',8,'gluten',0,NULL),
(5,'Couscous al pesce','Semolino, verdure, pesce',10,'gluten, pesce',0,NULL),
(5,'Couscous con calamari ripieni','Semolino, calamari ripieni',12,'gluten, molluschi',0,NULL),
(6,'Pasta ai frutti di mare royale','Pasta, frutti di mare',13,'gluten, crostacei, molluschi',0,NULL),
(6,'Pasta al polpo','Pasta, polpo',10,'gluten, molluschi',0,NULL),
(6,'Pasta con agnello e manzo','Pasta, carne stufata',9,'gluten',0,NULL),
(6,'Riso alle verdure (jerbi)','Riso profumato, verdure',8,'',0,NULL),
(6,'Riso con calamari ripieni','Riso, calamari ripieni',12,'molluschi',0,NULL),
(6,'Riso con agnello e manzo','Riso profumato, carne',9,'',0,NULL),
(7,'Tajine barkouk','Manzo, prugne secche',10,'uova',0,NULL),
(7,'Tajine pollo limone e olive','Pollo, limone, olive',8,'uova',0,NULL),
(7,'Tajine di pesce','Nasello, patate, carote',9,'pesce',0,NULL),
(7,'Bastila di pesce','Sfoglia croccante',6,'gluten, pesce',0,NULL),
(7,'Bastila di pollo','Sfoglia croccante',5,'gluten',0,NULL),
(8,'Mloukhia con manzo','Stufato di mloukhia',10,'',0,NULL),
(8,'Piselli con seppie','Stufato del giorno',10,'molluschi',0,NULL),
(8,'Piselli con agnello','Stufato del giorno',9,'',0,NULL),
(8,'Fagioli bianchi con manzo','Stufato del giorno',9,'',0,NULL),
(8,'Kamounia agnello e manzo','Cumino, carne morbida',9,'',0,NULL),
(8,'Scarpetta (mosli) con agnello','Salsa mosli',9,'',0,NULL),
(8,'Scarpetta (mosli) con pesce','Salsa mosli',10,'pesce',0,NULL);
INSERT INTO hours VALUES (1,'11:30','15:00','18:30','23:00',0),(2,'11:30','15:00','18:30','23:00',0),(3,'11:30','15:00','18:30','23:00',0),(4,'11:30','15:00','18:30','23:00',0),(5,'11:30','15:00','18:30','23:30',0),(6,'11:30','15:00','18:30','23:30',0),(7,'11:30','15:00','18:30','23:00',0);
INSERT INTO gallery(path,alt,sort) VALUES
('/assets/img/festin.jpg','Banchetto d’agnello grigliato, meze e tè',0),
('/assets/img/brik.jpg','Brik dall’uovo morbido con tonno',1),
('/assets/img/couscous.jpg','Couscous all’agnello',2),
('/assets/img/lablabi.jpg','Lablabi con ceci',3),
('/assets/img/mechouia.jpg','Insalata mechouia con tonno',4),
('/assets/img/istanbul.jpg','Tavola apparecchiata davanti al Bosforo',5),
('/assets/img/terrasse.jpg','Terrazza bianca tunisina',6),
('/assets/img/nuit.jpg','Tavola della sera, lanterne e mosaici',7),
('/assets/img/cotelettes.jpg','Costolette d’agnello grigliate',8),
('/assets/img/plateau-the.jpg','Vassoio da tè in ottone',9),
('/assets/img/feuilletes.jpg','Sfoglie all’uovo',10),
('/assets/img/figues.jpg','Fichi e pistacchi',11),
('/assets/img/mechoui.jpg','Spalla d’agnello arrosto su ottone',12),
('/assets/img/lablabi.jpg','Lablabi, ceci e uovo in camicia',13),
('/assets/img/ojja-rue.jpg','Ojja di strada con merguez',14),
('/assets/img/mlawi.jpg','Mlawi grigliato con tonno',15),
('/assets/img/chapati.jpg','Chapati farcito',16),
('/assets/img/baguette.jpg','Baguette farcita al tonno',17),
('/assets/img/fricasse.jpg','Fricassé dorati',18),
('/assets/img/medina.jpg','Cortile della medina con zellige',19),
('/assets/img/moderne.jpg','Sala bianca e blu',20),
('/assets/img/traditionnel.jpg','Sala delle lanterne e legno intagliato',21);
INSERT INTO socials(network,url) VALUES('instagram','https://www.instagram.com/sultan__parma/'),('google','https://share.google/CJmpqGV76S8BhzZOz');
-- Amministrazione: aprite /admin nel browser per creare il primo account (procedura guidata).
-- Account da riga di comando: php tools/admin-password.php email@esempio.com 'password'
