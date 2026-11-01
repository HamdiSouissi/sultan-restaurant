-- =====================================================================
--  Sultan — base esistente : passaggio dei contenuti all'italiano
--  Da eseguire una sola volta su un database già installato (phpMyAdmin).
--
--  Les mises à jour ciblent les valeurs françaises d'origine : si vous
--  avez déjà renommé une catégorie ou un plat, votre texte est conservé.
-- =====================================================================
USE sultan;

-- Catégories
UPDATE categories SET name = CASE name
  WHEN 'Sandwichs' THEN 'Panini & street food'
  WHEN 'Entrées & salades' THEN 'Antipasti & insalate'
  WHEN 'Plats tunisiens' THEN 'Piatti tunisini'
  WHEN 'Grillades' THEN 'Grigliate'
  WHEN 'Pâtes & riz' THEN 'Pasta & riso'
  WHEN 'Tajines & bastila' THEN 'Tajine & bastila'
  WHEN 'Mijotés du jour' THEN 'Stufati del giorno'
  ELSE name END
WHERE name IN ('Sandwichs', 'Entrées & salades', 'Plats tunisiens', 'Grillades', 'Pâtes & riz', 'Tajines & bastila', 'Mijotés du jour');

-- Plats : nom, description et allergènes
UPDATE dishes SET
  name = CASE name
    WHEN 'Makloub thon' THEN 'Makloub al tonno'
    WHEN 'Makloub merguez' THEN 'Makloub con merguez'
    WHEN 'Makloub poulet' THEN 'Makloub al pollo'
    WHEN 'Makloub Sultan' THEN 'Makloub Sultan'
    WHEN 'Baguette farcie thon' THEN 'Baguette farcita al tonno'
    WHEN 'Baguette farcie viande hachée' THEN 'Baguette farcita con carne macinata'
    WHEN 'Soufflet poulet' THEN 'Soffiato al pollo'
    WHEN 'Panini kafteji' THEN 'Panino kafteji'
    WHEN 'Mlawi merguez' THEN 'Mlawi con merguez'
    WHEN 'Chapati omelette' THEN 'Chapati con omelette'
    WHEN 'Tacos XL' THEN 'Tacos XL'
    WHEN 'Brik œuf thon' THEN 'Brik uovo e tonno'
    WHEN 'Fricassé' THEN 'Fricassé'
    WHEN 'Tajine (1 pièce)' THEN 'Tajine (1 pezzo)'
    WHEN 'Salade tunisienne' THEN 'Insalata tunisina'
    WHEN 'Salade méchouia' THEN 'Insalata mechouia'
    WHEN 'Carottes à la vapeur (houria)' THEN 'Carote al vapore (houria)'
    WHEN 'Ojja merguez' THEN 'Ojja con merguez'
    WHEN 'Ojja poulet' THEN 'Ojja al pollo'
    WHEN 'Plat tunisien' THEN 'Piatto tunisino'
    WHEN 'Plat kafteji' THEN 'Piatto kafteji'
    WHEN 'Lablabi' THEN 'Lablabi'
    WHEN 'Grillade poulet' THEN 'Grigliata di pollo'
    WHEN 'Grillade viande hachée' THEN 'Grigliata di carne macinata'
    WHEN 'Grillade merguez' THEN 'Grigliata di merguez'
    WHEN 'Côtelettes d’agneau' THEN 'Costolette d’agnello'
    WHEN 'Grillade mixte' THEN 'Grigliata mista'
    WHEN 'Grillade de poisson' THEN 'Grigliata di pesce'
    WHEN 'Couscous agneau' THEN 'Couscous all’agnello'
    WHEN 'Couscous poulet' THEN 'Couscous al pollo'
    WHEN 'Couscous besbes' THEN 'Couscous besbes'
    WHEN 'Couscous poisson' THEN 'Couscous al pesce'
    WHEN 'Couscous calamars farcis' THEN 'Couscous con calamari ripieni'
    WHEN 'Pâtes fruits de mer royale' THEN 'Pasta ai frutti di mare royale'
    WHEN 'Pâtes poulpe' THEN 'Pasta al polpo'
    WHEN 'Pâtes agneau-bœuf' THEN 'Pasta agnello e manzo'
    WHEN 'Riz aux légumes (jerbi)' THEN 'Riso alle verdure (jerbi)'
    WHEN 'Riz calamars farcis' THEN 'Riso con calamari ripieni'
    WHEN 'Riz agneau-bœuf' THEN 'Riso agnello e manzo'
    WHEN 'Tajine barkouk' THEN 'Tajine barkouk'
    WHEN 'Tajine poulet citron olives' THEN 'Tajine pollo limone e olive'
    WHEN 'Tajine poisson' THEN 'Tajine di pesce'
    WHEN 'Bastila poisson' THEN 'Bastila di pesce'
    WHEN 'Bastila poulet' THEN 'Bastila di pollo'
    WHEN 'Mloukhia bœuf' THEN 'Mloukhia con manzo'
    WHEN 'Petits pois aux seiches' THEN 'Piselli con seppie'
    WHEN 'Petits pois agneau' THEN 'Piselli con agnello'
    WHEN 'Haricots blancs bœuf' THEN 'Fagioli bianchi con manzo'
    WHEN 'Kamounia agneau-bœuf' THEN 'Kamounia agnello e manzo'
    WHEN 'Scarpetta (mosli) agneau' THEN 'Scarpetta (mosli) con agnello'
    WHEN 'Scarpetta (mosli) poisson' THEN 'Scarpetta (mosli) con pesce'
    ELSE name END,
  description = CASE name
    WHEN 'Makloub thon' THEN 'Piadina, tonno, verdure crude'
    WHEN 'Makloub merguez' THEN 'Piadina, merguez'
    WHEN 'Makloub poulet' THEN 'Piadina, pollo alla griglia'
    WHEN 'Makloub Sultan' THEN 'Ripieno della casa'
    WHEN 'Baguette farcie thon' THEN 'Tonno, uovo, olive, harissa'
    WHEN 'Baguette farcie viande hachée' THEN 'Carne macinata, salse'
    WHEN 'Soufflet poulet' THEN 'Pasta sfogliata dorata, pollo'
    WHEN 'Panini kafteji' THEN 'Panino, kafteji tunisino'
    WHEN 'Mlawi merguez' THEN 'Mlawi sfogliato, merguez grigliate'
    WHEN 'Chapati omelette' THEN 'Chapati, omelette, formaggio'
    WHEN 'Tacos XL' THEN '3 carni, 2 tortillas'
    WHEN 'Brik œuf thon' THEN 'Sfoglia di brick, uovo morbido'
    WHEN 'Fricassé' THEN 'Frittella salata farcita con tonno, uovo, olive'
    WHEN 'Tajine (1 pièce)' THEN 'Sformato tunisino alle uova'
    WHEN 'Salade tunisienne' THEN 'Verdure fresche, olio d’oliva'
    WHEN 'Salade méchouia' THEN 'Peperoni grigliati, tonno, uovo'
    WHEN 'Carottes à la vapeur (houria)' THEN 'Carote, harissa, cumino'
    WHEN 'Ojja merguez' THEN 'Uova, merguez, salsa piccante'
    WHEN 'Ojja poulet' THEN 'Uova, pollo, salsa piccante'
    WHEN 'Plat tunisien' THEN 'Assortimento del giorno'
    WHEN 'Plat kafteji' THEN 'Verdure fritte, uovo, merguez'
    WHEN 'Lablabi' THEN 'Ceci, pane, uovo, capperi'
    WHEN 'Grillade poulet' THEN '2 pezzi'
    WHEN 'Grillade viande hachée' THEN '3 pezzi'
    WHEN 'Grillade merguez' THEN '3 pezzi'
    WHEN 'Côtelettes d’agneau' THEN '2 pezzi'
    WHEN 'Grillade mixte' THEN 'Selezione di carni'
    WHEN 'Grillade de poisson' THEN 'Pesce del giorno'
    WHEN 'Couscous agneau' THEN 'Semolino, verdure, agnello'
    WHEN 'Couscous poulet' THEN 'Semolino, verdure, pollo'
    WHEN 'Couscous besbes' THEN 'Semolino, salsa piccante'
    WHEN 'Couscous poisson' THEN 'Semolino, verdure, pesce'
    WHEN 'Couscous calamars farcis' THEN 'Semolino, calamari ripieni'
    WHEN 'Pâtes fruits de mer royale' THEN 'Pasta, frutti di mare'
    WHEN 'Pâtes poulpe' THEN 'Pasta, polpo'
    WHEN 'Pâtes agneau-bœuf' THEN 'Pasta, carne stufata'
    WHEN 'Riz aux légumes (jerbi)' THEN 'Riso profumato, verdure'
    WHEN 'Riz calamars farcis' THEN 'Riso, calamari ripieni'
    WHEN 'Riz agneau-bœuf' THEN 'Riso profumato, carne'
    WHEN 'Tajine barkouk' THEN 'Manzo, prugne secche'
    WHEN 'Tajine poulet citron olives' THEN 'Pollo, limone, olive'
    WHEN 'Tajine poisson' THEN 'Nasello, patate, carote'
    WHEN 'Bastila poisson' THEN 'Sfoglia croccante'
    WHEN 'Bastila poulet' THEN 'Sfoglia croccante'
    WHEN 'Mloukhia bœuf' THEN 'Stufato di mloukhia'
    WHEN 'Petits pois aux seiches' THEN 'Stufato del giorno'
    WHEN 'Petits pois agneau' THEN 'Stufato del giorno'
    WHEN 'Haricots blancs bœuf' THEN 'Stufato del giorno'
    WHEN 'Kamounia agneau-bœuf' THEN 'Cumino, carne morbida'
    WHEN 'Scarpetta (mosli) agneau' THEN 'Salsa mosli'
    WHEN 'Scarpetta (mosli) poisson' THEN 'Salsa mosli'
    ELSE description END,
  allergens = CASE name
    WHEN 'Makloub thon' THEN 'gluten, pesce'
    WHEN 'Makloub merguez' THEN 'gluten'
    WHEN 'Makloub poulet' THEN 'gluten'
    WHEN 'Makloub Sultan' THEN 'gluten'
    WHEN 'Baguette farcie thon' THEN 'gluten, pesce, uova'
    WHEN 'Baguette farcie viande hachée' THEN 'gluten'
    WHEN 'Soufflet poulet' THEN 'gluten'
    WHEN 'Panini kafteji' THEN 'gluten'
    WHEN 'Mlawi merguez' THEN 'gluten'
    WHEN 'Chapati omelette' THEN 'gluten, uova, latte'
    WHEN 'Tacos XL' THEN 'gluten, latte'
    WHEN 'Brik œuf thon' THEN 'gluten, uova, pesce'
    WHEN 'Fricassé' THEN 'gluten, uova, pesce'
    WHEN 'Tajine (1 pièce)' THEN 'uova'
    WHEN 'Salade tunisienne' THEN ''
    WHEN 'Salade méchouia' THEN 'uova, pesce'
    WHEN 'Carottes à la vapeur (houria)' THEN ''
    WHEN 'Ojja merguez' THEN 'uova'
    WHEN 'Ojja poulet' THEN 'uova'
    WHEN 'Plat tunisien' THEN 'gluten, uova'
    WHEN 'Plat kafteji' THEN 'uova'
    WHEN 'Lablabi' THEN 'gluten, uova'
    WHEN 'Grillade poulet' THEN ''
    WHEN 'Grillade viande hachée' THEN ''
    WHEN 'Grillade merguez' THEN ''
    WHEN 'Côtelettes d’agneau' THEN ''
    WHEN 'Grillade mixte' THEN ''
    WHEN 'Grillade de poisson' THEN 'pesce'
    WHEN 'Couscous agneau' THEN 'gluten'
    WHEN 'Couscous poulet' THEN 'gluten'
    WHEN 'Couscous besbes' THEN 'gluten'
    WHEN 'Couscous poisson' THEN 'gluten, pesce'
    WHEN 'Couscous calamars farcis' THEN 'gluten, molluschi'
    WHEN 'Pâtes fruits de mer royale' THEN 'gluten, crostacei, molluschi'
    WHEN 'Pâtes poulpe' THEN 'gluten, molluschi'
    WHEN 'Pâtes agneau-bœuf' THEN 'gluten'
    WHEN 'Riz aux légumes (jerbi)' THEN ''
    WHEN 'Riz calamars farcis' THEN 'molluschi'
    WHEN 'Riz agneau-bœuf' THEN ''
    WHEN 'Tajine barkouk' THEN 'uova'
    WHEN 'Tajine poulet citron olives' THEN 'uova'
    WHEN 'Tajine poisson' THEN 'pesce'
    WHEN 'Bastila poisson' THEN 'gluten, pesce'
    WHEN 'Bastila poulet' THEN 'gluten'
    WHEN 'Mloukhia bœuf' THEN ''
    WHEN 'Petits pois aux seiches' THEN 'molluschi'
    WHEN 'Petits pois agneau' THEN ''
    WHEN 'Haricots blancs bœuf' THEN ''
    WHEN 'Kamounia agneau-bœuf' THEN ''
    WHEN 'Scarpetta (mosli) agneau' THEN ''
    WHEN 'Scarpetta (mosli) poisson' THEN 'pesce'
    ELSE allergens END
WHERE name IN ('Makloub thon',
  'Makloub merguez',
  'Makloub poulet',
  'Makloub Sultan',
  'Baguette farcie thon',
  'Baguette farcie viande hachée',
  'Soufflet poulet',
  'Panini kafteji',
  'Mlawi merguez',
  'Chapati omelette',
  'Tacos XL',
  'Brik œuf thon',
  'Fricassé',
  'Tajine (1 pièce)',
  'Salade tunisienne',
  'Salade méchouia',
  'Carottes à la vapeur (houria)',
  'Ojja merguez',
  'Ojja poulet',
  'Plat tunisien',
  'Plat kafteji',
  'Lablabi',
  'Grillade poulet',
  'Grillade viande hachée',
  'Grillade merguez',
  'Côtelettes d’agneau',
  'Grillade mixte',
  'Grillade de poisson',
  'Couscous agneau',
  'Couscous poulet',
  'Couscous besbes',
  'Couscous poisson',
  'Couscous calamars farcis',
  'Pâtes fruits de mer royale',
  'Pâtes poulpe',
  'Pâtes agneau-bœuf',
  'Riz aux légumes (jerbi)',
  'Riz calamars farcis',
  'Riz agneau-bœuf',
  'Tajine barkouk',
  'Tajine poulet citron olives',
  'Tajine poisson',
  'Bastila poisson',
  'Bastila poulet',
  'Mloukhia bœuf',
  'Petits pois aux seiches',
  'Petits pois agneau',
  'Haricots blancs bœuf',
  'Kamounia agneau-bœuf',
  'Scarpetta (mosli) agneau',
  'Scarpetta (mosli) poisson');

-- Textes de la maison (uniquement s'ils sont encore en français)
UPDATE settings SET v = 'Cucina mediterranea · Street food tunisino' WHERE k = 'tagline' AND v = 'Cuisine méditerranéenne · Street food tunisienne';
UPDATE settings SET v = 'Couscous, tajine, grigliate e panini di strada preparati ogni giorno, tra Tunisi e Istanbul.' WHERE k = 'description' AND v = 'Couscous, tajines, grillades et sandwichs de rue préparés chaque jour, entre Tunis et Istanbul.';
UPDATE settings SET v = 'Ispirata alle grandi case tunisine e ai palazzi ottomani, la nostra cucina racconta un paese di memoria: semolino arrotolato a mano, grigliate alla brace, brik dall’uovo morbido, tè servito prima dell’ordine.' WHERE k = 'about_text' AND v = 'Inspirée des grandes maisons tunisiennes et des palais ottomans, notre cuisine raconte un pays de mémoire : semoule roulée à la main, grillades à la braise, brik à l’œuf coulant, thé servi avant la commande.';
UPDATE settings SET v = 'Supplementi: uovo, tonno, salame, merguez, carne macinata, formaggio, patate fritte.' WHERE k = 'menu_note' AND v = 'Suppléments : œuf, thon, salami, merguez, viande hachée, fromage, pommes de terre frites.';
UPDATE settings SET v = '« A Tunisi non si contano i piatti, si contano gli ospiti. »' WHERE k = 'quote_text' AND v = '« À Tunis, on ne compte pas les plats, on compte les convives. »';
UPDATE settings SET v = REPLACE(v, 'Italie', 'Italia') WHERE k = 'address' AND v LIKE '%Italie%';

-- Légendes de la galerie
UPDATE gallery SET alt = CASE alt
  WHEN 'Festin d’agneau grillé, mezzés et thé' THEN 'Banchetto d’agnello grigliato, meze e tè'
  WHEN 'Brik à l’œuf coulant et thon' THEN 'Brik dall’uovo morbido con tonno'
  WHEN 'Couscous à l’agneau' THEN 'Couscous all’agnello'
  WHEN 'Lablabi aux pois chiches' THEN 'Lablabi con ceci'
  WHEN 'Salade méchouia au thon' THEN 'Insalata mechouia con tonno'
  WHEN 'Table dressée face au Bosphore' THEN 'Tavola apparecchiata davanti al Bosforo'
  WHEN 'Terrasse blanche tunisienne' THEN 'Terrazza bianca tunisina'
  WHEN 'Table du soir, lanternes et mosaïques' THEN 'Tavola della sera, lanterne e mosaici'
  WHEN 'Côtelettes d’agneau grillées' THEN 'Costolette d’agnello grigliate'
  WHEN 'Plateau de thé en laiton' THEN 'Vassoio da tè in ottone'
  WHEN 'Feuilletés à l’œuf' THEN 'Sfoglie all’uovo'
  WHEN 'Figues et pistaches' THEN 'Fichi e pistacchi'
  WHEN 'Épaule d’agneau rôtie au laiton' THEN 'Spalla d’agnello arrosto su ottone'
  WHEN 'Lablabi, pois chiches et œuf poché' THEN 'Lablabi, ceci e uovo in camicia'
  WHEN 'Ojja de rue aux merguez' THEN 'Ojja di strada con merguez'
  WHEN 'Mlawi grillé au thon' THEN 'Mlawi grigliato con tonno'
  WHEN 'Chapati garni' THEN 'Chapati farcito'
  WHEN 'Baguette farcie thon' THEN 'Baguette farcita al tonno'
  WHEN 'Fricassés dorés' THEN 'Fricassé dorati'
  WHEN 'Cour de médina aux zelliges' THEN 'Cortile della medina con zellige'
  WHEN 'Salle blanche et bleue' THEN 'Sala bianca e blu'
  WHEN 'Salle aux lanternes et bois sculpté' THEN 'Sala delle lanterne e legno intagliato'
  ELSE alt END
WHERE alt IN ('Festin d’agneau grillé, mezzés et thé',
  'Brik à l’œuf coulant et thon',
  'Couscous à l’agneau',
  'Lablabi aux pois chiches',
  'Salade méchouia au thon',
  'Table dressée face au Bosphore',
  'Terrasse blanche tunisienne',
  'Table du soir, lanternes et mosaïques',
  'Côtelettes d’agneau grillées',
  'Plateau de thé en laiton',
  'Feuilletés à l’œuf',
  'Figues et pistaches',
  'Épaule d’agneau rôtie au laiton',
  'Lablabi, pois chiches et œuf poché',
  'Ojja de rue aux merguez',
  'Mlawi grillé au thon',
  'Chapati garni',
  'Baguette farcie thon',
  'Fricassés dorés',
  'Cour de médina aux zelliges',
  'Salle blanche et bleue',
  'Salle aux lanternes et bois sculpté');
