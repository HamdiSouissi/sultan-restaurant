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
CREATE TABLE stats(day DATE PRIMARY KEY, visits INT DEFAULT 0);

INSERT INTO settings VALUES
('name','Sultan'),('tagline','Cuisine méditerranéenne · Street food tunisienne'),
('description','Couscous, tajines, grillades et sandwichs de rue préparés chaque jour, entre Tunis et Istanbul.'),
('phone','+39 379 210 7500'),('whatsapp','393792107500'),('email',''),
('address','Strada Massimo D''Azeglio, 77/A, 43125 Parma PR, Italie'),('lat','44.80'),('lng','10.33'),
('maps_embed','https://www.google.com/maps?q=Strada+Massimo+D%27Azeglio+77%2FA+43125+Parma&output=embed');
INSERT INTO categories(id,name,slug,sort) VALUES
(1,'Sandwichs','c1',1),
(2,'Entrées & salades','c2',2),
(3,'Plats tunisiens','c3',3),
(4,'Grillades','c4',4),
(5,'Couscous','c5',5),
(6,'Pâtes & riz','c6',6),
(7,'Tajines & bastila','c7',7),
(8,'Mijotés du jour','c8',8);
INSERT INTO dishes(category_id,name,description,price,allergens,featured,image) VALUES
(1,'Makloub thon','Galette, thon, crudités',6.5,'gluten, poisson',0,NULL),
(1,'Makloub merguez','Galette, merguez',7.5,'gluten',0,NULL),
(1,'Makloub poulet','Galette, poulet grillé',7.5,'gluten',0,NULL),
(1,'Makloub Sultan','Garniture signature de la maison',8.5,'gluten',0,NULL),
(1,'Baguette farcie thon','Thon, œuf, olives, harissa',7.5,'gluten, poisson, œufs',1,'/assets/img/baguette.jpg'),
(1,'Baguette farcie viande hachée','Viande hachée, sauces',8.5,'gluten',0,NULL),
(1,'Soufflet poulet','Pâte soufflée dorée, poulet',7.5,'gluten',0,NULL),
(1,'Panini kafteji','Panini, kafteji tunisien',6,'gluten',0,NULL),
(1,'Mlawi merguez','Mlawi feuilleté, merguez grillées',6,'gluten',1,'/assets/img/mlawi.jpg'),
(1,'Chapati omelette','Chapati, omelette, fromage',5,'gluten, œufs, lait',1,'/assets/img/chapati.jpg'),
(1,'Tacos XL','3 viandes, 2 tortillas',9.5,'gluten, lait',0,NULL),
(2,'Brik œuf thon','Feuille de brick, œuf coulant',2.5,'gluten, œufs, poisson',1,'/assets/img/brik.jpg'),
(2,'Fricassé','Beignet salé farci thon, œuf, olives',2.5,'gluten, œufs, poisson',1,'/assets/img/fricasse.jpg'),
(2,'Tajine (1 pièce)','Flan tunisien aux œufs',2,'œufs',0,NULL),
(2,'Salade tunisienne','Légumes frais, huile d’olive',4,'',0,NULL),
(2,'Salade méchouia','Poivrons grillés, thon, œuf',4,'œufs, poisson',0,'/assets/img/mechouia.jpg'),
(2,'Carottes à la vapeur (houria)','Carottes, harissa, cumin',4,'',0,NULL),
(3,'Ojja merguez','Œufs, merguez, sauce piquante',8.5,'œufs',1,'/assets/img/ojja.jpg'),
(3,'Ojja poulet','Œufs, poulet, sauce piquante',8.5,'œufs',0,NULL),
(3,'Plat tunisien','Assortiment du jour',7.5,'gluten, œufs',0,NULL),
(3,'Plat kafteji','Légumes frits, œuf, merguez',7.5,'œufs',0,NULL),
(3,'Lablabi','Pois chiches, pain, œuf, câpres',6,'gluten, œufs',0,'/assets/img/lablabi.jpg'),
(4,'Grillade poulet','2 pièces',9.5,'',0,NULL),
(4,'Grillade viande hachée','3 pièces',10,'',0,NULL),
(4,'Grillade merguez','3 pièces',10,'',0,NULL),
(4,'Côtelettes d’agneau','2 pièces',12,'',0,'/assets/img/cotelettes.jpg'),
(4,'Grillade mixte','Sélection de viandes',15,'',0,NULL),
(4,'Grillade de poisson','Poisson du jour',12,'poisson',0,NULL),
(5,'Couscous agneau','Semoule, légumes, agneau',9,'gluten',1,'/assets/img/couscous.jpg'),
(5,'Couscous poulet','Semoule, légumes, poulet',8,'gluten',0,NULL),
(5,'Couscous besbes','Semoule, sauce piquante',8,'gluten',0,NULL),
(5,'Couscous poisson','Semoule, légumes, poisson',10,'gluten, poisson',0,NULL),
(5,'Couscous calamars farcis','Semoule, calamars farcis',12,'gluten, mollusques',0,NULL),
(6,'Pâtes fruits de mer royale','Pâtes, fruits de mer',13,'gluten, crustacés, mollusques',0,NULL),
(6,'Pâtes poulpe','Pâtes, poulpe',10,'gluten, mollusques',0,NULL),
(6,'Pâtes agneau-bœuf','Pâtes, viande mijotée',9,'gluten',0,NULL),
(6,'Riz aux légumes (jerbi)','Riz parfumé, légumes',8,'',0,NULL),
(6,'Riz calamars farcis','Riz, calamars farcis',12,'mollusques',0,NULL),
(6,'Riz agneau-bœuf','Riz parfumé, viande',9,'',0,NULL),
(7,'Tajine barkouk','Bœuf, pruneaux',10,'œufs',0,NULL),
(7,'Tajine poulet citron olives','Poulet, citron, olives',8,'œufs',0,NULL),
(7,'Tajine poisson','Merlu, pommes de terre, carottes',9,'poisson',0,NULL),
(7,'Bastila poisson','Feuilleté croustillant',6,'gluten, poisson',0,NULL),
(7,'Bastila poulet','Feuilleté croustillant',5,'gluten',0,NULL),
(8,'Mloukhia bœuf','Mijoté de mloukhia',10,'',0,NULL),
(8,'Petits pois aux seiches','Mijoté du jour',10,'mollusques',0,NULL),
(8,'Petits pois agneau','Mijoté du jour',9,'',0,NULL),
(8,'Haricots blancs bœuf','Mijoté du jour',9,'',0,NULL),
(8,'Kamounia agneau-bœuf','Cumin, viande fondante',9,'',0,NULL),
(8,'Scarpetta (mosli) agneau','Sauce mosli',9,'',0,NULL),
(8,'Scarpetta (mosli) poisson','Sauce mosli',10,'poisson',0,NULL);
INSERT INTO hours VALUES (1,'11:30','15:00','18:30','23:00',0),(2,'11:30','15:00','18:30','23:00',0),(3,'11:30','15:00','18:30','23:00',0),(4,'11:30','15:00','18:30','23:00',0),(5,'11:30','15:00','18:30','23:30',0),(6,'11:30','15:00','18:30','23:30',0),(7,'11:30','15:00','18:30','23:00',0);
INSERT INTO gallery(path,alt,sort) VALUES
('/assets/img/festin.jpg','Festin d’agneau grillé, mezzés et thé',0),
('/assets/img/brik.jpg','Brik à l’œuf coulant et thon',1),
('/assets/img/couscous.jpg','Couscous à l’agneau',2),
('/assets/img/lablabi.jpg','Lablabi aux pois chiches',3),
('/assets/img/mechouia.jpg','Salade méchouia au thon',4),
('/assets/img/istanbul.jpg','Table dressée face au Bosphore',5),
('/assets/img/terrasse.jpg','Terrasse blanche tunisienne',6),
('/assets/img/nuit.jpg','Table du soir, lanternes et mosaïques',7),
('/assets/img/cotelettes.jpg','Côtelettes d’agneau grillées',8),
('/assets/img/plateau-the.jpg','Plateau de thé en laiton',9),
('/assets/img/feuilletes.jpg','Feuilletés à l’œuf',10),
('/assets/img/figues.jpg','Figues et pistaches',11),
('/assets/img/mechoui.jpg','Épaule d’agneau rôtie au laiton',12),
('/assets/img/lablabi.jpg','Lablabi, pois chiches et œuf poché',13),
('/assets/img/ojja-rue.jpg','Ojja de rue aux merguez',14),
('/assets/img/mlawi.jpg','Mlawi grillé au thon',15),
('/assets/img/chapati.jpg','Chapati garni',16),
('/assets/img/baguette.jpg','Baguette farcie thon',17),
('/assets/img/fricasse.jpg','Fricassés dorés',18),
('/assets/img/medina.jpg','Cour de médina aux zelliges',19),
('/assets/img/moderne.jpg','Salle blanche et bleue',20),
('/assets/img/traditionnel.jpg','Salle aux lanternes et bois sculpté',21);
INSERT INTO socials(network,url) VALUES('instagram','https://www.instagram.com/sultan__parma/'),('google','https://share.google/CJmpqGV76S8BhzZOz');
-- Admin : php -r "echo password_hash('MOT_DE_PASSE',PASSWORD_DEFAULT);" puis INSERT INTO admins(email,password_hash) VALUES(...)
