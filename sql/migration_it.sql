-- =====================================================================
--  Sultan — base già installata: passaggio dei contenuti all'italiano
--  Da eseguire una sola volta (phpMyAdmin). Adatta il nome del database
--  nella riga USE se non è « sultan ».
--
--  ATTENZIONE (bug corretto): ogni colonna è aggiornata in un'ISTRUZIONE
--  SEPARATA. In MySQL le assegnazioni di uno stesso UPDATE sono valutate da
--  sinistra a destra e le successive vedono i valori GIÀ modificati: con
--  « SET name = CASE name …, description = CASE name … » i CASE di
--  descrizione e allergeni confrontavano i nuovi nomi italiani con le chiavi
--  francesi e le descrizioni restavano in francese. Non riunire queste righe.
--
--  Il file è ri-eseguibile e non distrugge il vostro lavoro: ogni
--  aggiornamento è condizionato al valore francese d'origine, quindi un
--  piatto o un testo che avete già riscritto resta com'è.
-- =====================================================================
USE sultan;

-- Categorie ------------------------------------------------------------
UPDATE categories SET name = CASE name
  WHEN 'Sandwichs' THEN 'Panini & street food'
  WHEN 'Entrées & salades' THEN 'Antipasti & insalate'
  WHEN 'Plats tunisiens' THEN 'Piatti tunisini'
  WHEN 'Grillades' THEN 'Grigliate'
  WHEN 'Couscous' THEN 'Couscous'
  WHEN 'Pâtes & riz' THEN 'Pasta & riso'
  WHEN 'Tajines & bastila' THEN 'Tajine & bastila'
  WHEN 'Mijotés du jour' THEN 'Stufati del giorno'
  ELSE name END
WHERE name IN ('Sandwichs',
  'Entrées & salades',
  'Plats tunisiens',
  'Grillades',
  'Couscous',
  'Pâtes & riz',
  'Tajines & bastila',
  'Mijotés du jour');

-- Piatti: descrizioni (chiave = nome francese d'origine) --------------
-- La seconda condizione — il testo deve essere ancora quello francese —
-- protegge le descrizioni che avete già riscritto di persona.
UPDATE dishes SET description = CASE name
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
  ELSE description END
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
  'Scarpetta (mosli) poisson')
AND description IN ('Galette, thon, crudités',
  'Galette, merguez',
  'Galette, poulet grillé',
  'Garniture signature de la maison',
  'Thon, œuf, olives, harissa',
  'Viande hachée, sauces',
  'Pâte soufflée dorée, poulet',
  'Panini, kafteji tunisien',
  'Mlawi feuilleté, merguez grillées',
  'Chapati, omelette, fromage',
  '3 viandes, 2 tortillas',
  'Feuille de brick, œuf coulant',
  'Beignet salé farci thon, œuf, olives',
  'Flan tunisien aux œufs',
  'Légumes frais, huile d’olive',
  'Poivrons grillés, thon, œuf',
  'Carottes, harissa, cumin',
  'Œufs, merguez, sauce piquante',
  'Œufs, poulet, sauce piquante',
  'Assortiment du jour',
  'Légumes frits, œuf, merguez',
  'Pois chiches, pain, œuf, câpres',
  '2 pièces',
  '3 pièces',
  'Sélection de viandes',
  'Poisson du jour',
  'Semoule, légumes, agneau',
  'Semoule, légumes, poulet',
  'Semoule, sauce piquante',
  'Semoule, légumes, poisson',
  'Semoule, calamars farcis',
  'Pâtes, fruits de mer',
  'Pâtes, poulpe',
  'Pâtes, viande mijotée',
  'Riz parfumé, légumes',
  'Riz, calamars farcis',
  'Riz parfumé, viande',
  'Bœuf, pruneaux',
  'Poulet, citron, olives',
  'Merlu, pommes de terre, carottes',
  'Feuilleté croustillant',
  'Mijoté de mloukhia',
  'Mijoté du jour',
  'Cumin, viande fondante',
  'Sauce mosli');

-- Piatti: allergeni ----------------------------------------------------
-- Stessa protezione: solo gli allergeni ancora nella versione francese.
UPDATE dishes SET allergens = CASE name
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
  'Scarpetta (mosli) poisson')
AND allergens IN ('gluten, poisson',
  'gluten',
  'gluten, poisson, œufs',
  'gluten, œufs, lait',
  'gluten, lait',
  'gluten, œufs, poisson',
  'œufs',
  '',
  'œufs, poisson',
  'gluten, œufs',
  'poisson',
  'gluten, mollusques',
  'gluten, crustacés, mollusques',
  'mollusques');

-- Piatti: nomi ---------------------------------------------------------
-- Per ultimo: descrizioni e allergeni sono già stati tradotti.
UPDATE dishes SET name = CASE name
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
  WHEN 'Pâtes agneau-bœuf' THEN 'Pasta con agnello e manzo'
  WHEN 'Riz aux légumes (jerbi)' THEN 'Riso alle verdure (jerbi)'
  WHEN 'Riz calamars farcis' THEN 'Riso con calamari ripieni'
  WHEN 'Riz agneau-bœuf' THEN 'Riso con agnello e manzo'
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
  ELSE name END
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

-- Riparazione ----------------------------------------------------------
-- Database che ha già eseguito la versione precedente di questo file: i
-- piatti hanno il nome italiano ma descrizioni o allergeni ancora francesi.
-- Qui si riconosce il testo francese d'origine e lo si sostituisce; un testo
-- riscritto da voi non corrisponde alla lista e resta intatto.
UPDATE dishes SET description = CASE name
  WHEN 'Makloub al tonno' THEN 'Piadina, tonno, verdure crude'
  WHEN 'Makloub con merguez' THEN 'Piadina, merguez'
  WHEN 'Makloub al pollo' THEN 'Piadina, pollo alla griglia'
  WHEN 'Makloub Sultan' THEN 'Ripieno della casa'
  WHEN 'Baguette farcita al tonno' THEN 'Tonno, uovo, olive, harissa'
  WHEN 'Baguette farcita con carne macinata' THEN 'Carne macinata, salse'
  WHEN 'Soffiato al pollo' THEN 'Pasta sfogliata dorata, pollo'
  WHEN 'Panino kafteji' THEN 'Panino, kafteji tunisino'
  WHEN 'Mlawi con merguez' THEN 'Mlawi sfogliato, merguez grigliate'
  WHEN 'Chapati con omelette' THEN 'Chapati, omelette, formaggio'
  WHEN 'Tacos XL' THEN '3 carni, 2 tortillas'
  WHEN 'Brik uovo e tonno' THEN 'Sfoglia di brick, uovo morbido'
  WHEN 'Fricassé' THEN 'Frittella salata farcita con tonno, uovo, olive'
  WHEN 'Tajine (1 pezzo)' THEN 'Sformato tunisino alle uova'
  WHEN 'Insalata tunisina' THEN 'Verdure fresche, olio d’oliva'
  WHEN 'Insalata mechouia' THEN 'Peperoni grigliati, tonno, uovo'
  WHEN 'Carote al vapore (houria)' THEN 'Carote, harissa, cumino'
  WHEN 'Ojja con merguez' THEN 'Uova, merguez, salsa piccante'
  WHEN 'Ojja al pollo' THEN 'Uova, pollo, salsa piccante'
  WHEN 'Piatto tunisino' THEN 'Assortimento del giorno'
  WHEN 'Piatto kafteji' THEN 'Verdure fritte, uovo, merguez'
  WHEN 'Lablabi' THEN 'Ceci, pane, uovo, capperi'
  WHEN 'Grigliata di pollo' THEN '2 pezzi'
  WHEN 'Grigliata di carne macinata' THEN '3 pezzi'
  WHEN 'Grigliata di merguez' THEN '3 pezzi'
  WHEN 'Costolette d’agnello' THEN '2 pezzi'
  WHEN 'Grigliata mista' THEN 'Selezione di carni'
  WHEN 'Grigliata di pesce' THEN 'Pesce del giorno'
  WHEN 'Couscous all’agnello' THEN 'Semolino, verdure, agnello'
  WHEN 'Couscous al pollo' THEN 'Semolino, verdure, pollo'
  WHEN 'Couscous besbes' THEN 'Semolino, salsa piccante'
  WHEN 'Couscous al pesce' THEN 'Semolino, verdure, pesce'
  WHEN 'Couscous con calamari ripieni' THEN 'Semolino, calamari ripieni'
  WHEN 'Pasta ai frutti di mare royale' THEN 'Pasta, frutti di mare'
  WHEN 'Pasta al polpo' THEN 'Pasta, polpo'
  WHEN 'Pasta con agnello e manzo' THEN 'Pasta, carne stufata'
  WHEN 'Riso alle verdure (jerbi)' THEN 'Riso profumato, verdure'
  WHEN 'Riso con calamari ripieni' THEN 'Riso, calamari ripieni'
  WHEN 'Riso con agnello e manzo' THEN 'Riso profumato, carne'
  WHEN 'Tajine barkouk' THEN 'Manzo, prugne secche'
  WHEN 'Tajine pollo limone e olive' THEN 'Pollo, limone, olive'
  WHEN 'Tajine di pesce' THEN 'Nasello, patate, carote'
  WHEN 'Bastila di pesce' THEN 'Sfoglia croccante'
  WHEN 'Bastila di pollo' THEN 'Sfoglia croccante'
  WHEN 'Mloukhia con manzo' THEN 'Stufato di mloukhia'
  WHEN 'Piselli con seppie' THEN 'Stufato del giorno'
  WHEN 'Piselli con agnello' THEN 'Stufato del giorno'
  WHEN 'Fagioli bianchi con manzo' THEN 'Stufato del giorno'
  WHEN 'Kamounia agnello e manzo' THEN 'Cumino, carne morbida'
  WHEN 'Scarpetta (mosli) con agnello' THEN 'Salsa mosli'
  WHEN 'Scarpetta (mosli) con pesce' THEN 'Salsa mosli'
  ELSE description END
WHERE name IN ('Makloub al tonno',
  'Makloub con merguez',
  'Makloub al pollo',
  'Makloub Sultan',
  'Baguette farcita al tonno',
  'Baguette farcita con carne macinata',
  'Soffiato al pollo',
  'Panino kafteji',
  'Mlawi con merguez',
  'Chapati con omelette',
  'Tacos XL',
  'Brik uovo e tonno',
  'Fricassé',
  'Tajine (1 pezzo)',
  'Insalata tunisina',
  'Insalata mechouia',
  'Carote al vapore (houria)',
  'Ojja con merguez',
  'Ojja al pollo',
  'Piatto tunisino',
  'Piatto kafteji',
  'Lablabi',
  'Grigliata di pollo',
  'Grigliata di carne macinata',
  'Grigliata di merguez',
  'Costolette d’agnello',
  'Grigliata mista',
  'Grigliata di pesce',
  'Couscous all’agnello',
  'Couscous al pollo',
  'Couscous besbes',
  'Couscous al pesce',
  'Couscous con calamari ripieni',
  'Pasta ai frutti di mare royale',
  'Pasta al polpo',
  'Pasta con agnello e manzo',
  'Riso alle verdure (jerbi)',
  'Riso con calamari ripieni',
  'Riso con agnello e manzo',
  'Tajine barkouk',
  'Tajine pollo limone e olive',
  'Tajine di pesce',
  'Bastila di pesce',
  'Bastila di pollo',
  'Mloukhia con manzo',
  'Piselli con seppie',
  'Piselli con agnello',
  'Fagioli bianchi con manzo',
  'Kamounia agnello e manzo',
  'Scarpetta (mosli) con agnello',
  'Scarpetta (mosli) con pesce')
AND description IN ('Galette, thon, crudités',
  'Galette, merguez',
  'Galette, poulet grillé',
  'Garniture signature de la maison',
  'Thon, œuf, olives, harissa',
  'Viande hachée, sauces',
  'Pâte soufflée dorée, poulet',
  'Panini, kafteji tunisien',
  'Mlawi feuilleté, merguez grillées',
  'Chapati, omelette, fromage',
  '3 viandes, 2 tortillas',
  'Feuille de brick, œuf coulant',
  'Beignet salé farci thon, œuf, olives',
  'Flan tunisien aux œufs',
  'Légumes frais, huile d’olive',
  'Poivrons grillés, thon, œuf',
  'Carottes, harissa, cumin',
  'Œufs, merguez, sauce piquante',
  'Œufs, poulet, sauce piquante',
  'Assortiment du jour',
  'Légumes frits, œuf, merguez',
  'Pois chiches, pain, œuf, câpres',
  '2 pièces',
  '3 pièces',
  'Sélection de viandes',
  'Poisson du jour',
  'Semoule, légumes, agneau',
  'Semoule, légumes, poulet',
  'Semoule, sauce piquante',
  'Semoule, légumes, poisson',
  'Semoule, calamars farcis',
  'Pâtes, fruits de mer',
  'Pâtes, poulpe',
  'Pâtes, viande mijotée',
  'Riz parfumé, légumes',
  'Riz, calamars farcis',
  'Riz parfumé, viande',
  'Bœuf, pruneaux',
  'Poulet, citron, olives',
  'Merlu, pommes de terre, carottes',
  'Feuilleté croustillant',
  'Mijoté de mloukhia',
  'Mijoté du jour',
  'Cumin, viande fondante',
  'Sauce mosli');

UPDATE dishes SET allergens = CASE name
  WHEN 'Makloub al tonno' THEN 'gluten, pesce'
  WHEN 'Makloub con merguez' THEN 'gluten'
  WHEN 'Makloub al pollo' THEN 'gluten'
  WHEN 'Makloub Sultan' THEN 'gluten'
  WHEN 'Baguette farcita al tonno' THEN 'gluten, pesce, uova'
  WHEN 'Baguette farcita con carne macinata' THEN 'gluten'
  WHEN 'Soffiato al pollo' THEN 'gluten'
  WHEN 'Panino kafteji' THEN 'gluten'
  WHEN 'Mlawi con merguez' THEN 'gluten'
  WHEN 'Chapati con omelette' THEN 'gluten, uova, latte'
  WHEN 'Tacos XL' THEN 'gluten, latte'
  WHEN 'Brik uovo e tonno' THEN 'gluten, uova, pesce'
  WHEN 'Fricassé' THEN 'gluten, uova, pesce'
  WHEN 'Tajine (1 pezzo)' THEN 'uova'
  WHEN 'Insalata tunisina' THEN ''
  WHEN 'Insalata mechouia' THEN 'uova, pesce'
  WHEN 'Carote al vapore (houria)' THEN ''
  WHEN 'Ojja con merguez' THEN 'uova'
  WHEN 'Ojja al pollo' THEN 'uova'
  WHEN 'Piatto tunisino' THEN 'gluten, uova'
  WHEN 'Piatto kafteji' THEN 'uova'
  WHEN 'Lablabi' THEN 'gluten, uova'
  WHEN 'Grigliata di pollo' THEN ''
  WHEN 'Grigliata di carne macinata' THEN ''
  WHEN 'Grigliata di merguez' THEN ''
  WHEN 'Costolette d’agnello' THEN ''
  WHEN 'Grigliata mista' THEN ''
  WHEN 'Grigliata di pesce' THEN 'pesce'
  WHEN 'Couscous all’agnello' THEN 'gluten'
  WHEN 'Couscous al pollo' THEN 'gluten'
  WHEN 'Couscous besbes' THEN 'gluten'
  WHEN 'Couscous al pesce' THEN 'gluten, pesce'
  WHEN 'Couscous con calamari ripieni' THEN 'gluten, molluschi'
  WHEN 'Pasta ai frutti di mare royale' THEN 'gluten, crostacei, molluschi'
  WHEN 'Pasta al polpo' THEN 'gluten, molluschi'
  WHEN 'Pasta con agnello e manzo' THEN 'gluten'
  WHEN 'Riso alle verdure (jerbi)' THEN ''
  WHEN 'Riso con calamari ripieni' THEN 'molluschi'
  WHEN 'Riso con agnello e manzo' THEN ''
  WHEN 'Tajine barkouk' THEN 'uova'
  WHEN 'Tajine pollo limone e olive' THEN 'uova'
  WHEN 'Tajine di pesce' THEN 'pesce'
  WHEN 'Bastila di pesce' THEN 'gluten, pesce'
  WHEN 'Bastila di pollo' THEN 'gluten'
  WHEN 'Mloukhia con manzo' THEN ''
  WHEN 'Piselli con seppie' THEN 'molluschi'
  WHEN 'Piselli con agnello' THEN ''
  WHEN 'Fagioli bianchi con manzo' THEN ''
  WHEN 'Kamounia agnello e manzo' THEN ''
  WHEN 'Scarpetta (mosli) con agnello' THEN ''
  WHEN 'Scarpetta (mosli) con pesce' THEN 'pesce'
  ELSE allergens END
WHERE name IN ('Makloub al tonno',
  'Makloub con merguez',
  'Makloub al pollo',
  'Makloub Sultan',
  'Baguette farcita al tonno',
  'Baguette farcita con carne macinata',
  'Soffiato al pollo',
  'Panino kafteji',
  'Mlawi con merguez',
  'Chapati con omelette',
  'Tacos XL',
  'Brik uovo e tonno',
  'Fricassé',
  'Tajine (1 pezzo)',
  'Insalata tunisina',
  'Insalata mechouia',
  'Carote al vapore (houria)',
  'Ojja con merguez',
  'Ojja al pollo',
  'Piatto tunisino',
  'Piatto kafteji',
  'Lablabi',
  'Grigliata di pollo',
  'Grigliata di carne macinata',
  'Grigliata di merguez',
  'Costolette d’agnello',
  'Grigliata mista',
  'Grigliata di pesce',
  'Couscous all’agnello',
  'Couscous al pollo',
  'Couscous besbes',
  'Couscous al pesce',
  'Couscous con calamari ripieni',
  'Pasta ai frutti di mare royale',
  'Pasta al polpo',
  'Pasta con agnello e manzo',
  'Riso alle verdure (jerbi)',
  'Riso con calamari ripieni',
  'Riso con agnello e manzo',
  'Tajine barkouk',
  'Tajine pollo limone e olive',
  'Tajine di pesce',
  'Bastila di pesce',
  'Bastila di pollo',
  'Mloukhia con manzo',
  'Piselli con seppie',
  'Piselli con agnello',
  'Fagioli bianchi con manzo',
  'Kamounia agnello e manzo',
  'Scarpetta (mosli) con agnello',
  'Scarpetta (mosli) con pesce')
AND allergens IN ('gluten, poisson',
  'gluten',
  'gluten, poisson, œufs',
  'gluten, œufs, lait',
  'gluten, lait',
  'gluten, œufs, poisson',
  'œufs',
  '',
  'œufs, poisson',
  'gluten, œufs',
  'poisson',
  'gluten, mollusques',
  'gluten, crustacés, mollusques',
  'mollusques');

-- Testi della casa (solo se sono ancora in francese) -------------------
UPDATE settings SET v = 'Cucina mediterranea · Street food tunisino' WHERE k = 'tagline' AND v = 'Cuisine méditerranéenne · Street food tunisienne';
UPDATE settings SET v = 'Couscous, tajine, grigliate e panini di strada preparati ogni giorno, tra Tunisi e Istanbul.' WHERE k = 'description' AND v = 'Couscous, tajines, grillades et sandwichs de rue préparés chaque jour, entre Tunis et Istanbul.';
UPDATE settings SET v = 'Ispirata alle grandi case tunisine e ai palazzi ottomani, la nostra cucina racconta un paese di memoria: semolino arrotolato a mano, grigliate alla brace, brik dall’uovo morbido, tè servito prima dell’ordine.' WHERE k = 'about_text' AND v = 'Inspirée des grandes maisons tunisiennes et des palais ottomans, notre cuisine raconte un pays de mémoire : semoule roulée à la main, grillades à la braise, brik à l’œuf coulant, thé servi avant la commande.';
UPDATE settings SET v = 'Supplementi: uovo, tonno, salame, merguez, carne macinata, formaggio, patate fritte.' WHERE k = 'menu_note' AND v = 'Suppléments : œuf, thon, salami, merguez, viande hachée, fromage, pommes de terre frites.';
UPDATE settings SET v = '« A Tunisi non si contano i piatti, si contano gli ospiti. »' WHERE k = 'quote_text' AND v = '« À Tunis, on ne compte pas les plats, on compte les convives. »';
UPDATE settings SET v = REPLACE(v, 'Italie', 'Italia') WHERE k = 'address' AND v LIKE '%Italie%';

-- Didascalie della galleria
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
