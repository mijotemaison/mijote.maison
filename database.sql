CREATE DATABASE IF NOT EXISTS secure_recipes_greta92
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE secure_recipes_greta92;

DROP TABLE IF EXISTS recipe_comments;
DROP TABLE IF EXISTS recipe_ratings;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS security_logs;
DROP TABLE IF EXISTS login_attempts;
DROP TABLE IF EXISTS admins;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  short_description VARCHAR(300) NOT NULL,
  description TEXT NOT NULL,
  ingredients TEXT NOT NULL,
  preparation_steps TEXT NOT NULL,
  image_path VARCHAR(255) NULL,
  category VARCHAR(40) NOT NULL DEFAULT 'plats',
  status ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  view_count INT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_recipes_slug (slug),
  INDEX idx_recipes_public (status, category, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS recipe_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  voter_hash CHAR(64) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_recipe_rating_voter (recipe_id, voter_hash),
  INDEX idx_recipe_ratings_recipe (recipe_id),
  CONSTRAINT fk_recipe_ratings_recipe FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
  CONSTRAINT chk_recipe_rating_value CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS recipe_comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  author_name VARCHAR(80) NOT NULL,
  content TEXT NOT NULL,
  status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  visitor_hash CHAR(64) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_recipe_comments_recipe_status (recipe_id, status, created_at),
  INDEX idx_recipe_comments_status (status, created_at),
  CONSTRAINT fk_recipe_comments_recipe FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NULL,
  ip_address VARCHAR(45) NOT NULL,
  user_agent VARCHAR(255) NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_login_attempts_lookup (email, ip_address, success, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS security_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_type VARCHAR(80) NOT NULL,
  actor_email VARCHAR(190) NULL,
  ip_address VARCHAR(45) NOT NULL,
  user_agent VARCHAR(255) NULL,
  details VARCHAR(1000) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_security_logs_type_date (event_type, created_at),
  INDEX idx_security_logs_actor_date (actor_email, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (username, email, password_hash, created_at, updated_at)
VALUES (
  'Administrateur GRETA',
  'admin@example.com',
  '$2y$12$orfrtA2Qpvk6uuEBqetcCOpupstnDAqga8nxp1m/yNNf67Dcw.odS',
  NOW(),
  NOW()
)
ON DUPLICATE KEY UPDATE username = VALUES(username);

INSERT INTO recipes (title, slug, short_description, description, ingredients, preparation_steps, image_path, category, status, published_at, view_count, created_at, updated_at)
VALUES
(
  'Veloute de potimarron',
  'veloute-de-potimarron',
  'Une entree douce et epicee pour lancer le repas tout en douceur.',
  'Un veloute de potimarron onctueux avec creme, graines grillees et pain croustillant. Une recette simple, chaude et parfaite pour une table familiale.',
  '1 potimarron\n1 oignon\n2 gousses d ail\n70 cl de bouillon\n10 cl de creme\nSel, poivre, muscade',
  'Laver et couper le potimarron.\nFaire revenir l oignon et l ail.\nAjouter le bouillon puis cuire 25 minutes.\nMixer, assaisonner et servir chaud.',
  'assets/img/recipes/veloute-potimarron.webp',
  'entrees',
  'published',
  NOW(),
  38,
  NOW(),
  NOW()
),
(
  'Poulet citron et herbes',
  'poulet-citron-et-herbes',
  'Un plat dore, parfume et rapide pour un diner de semaine.',
  'Des blancs de poulet saisis avec citron, miel et herbes fraiches. Le jus reduit donne une sauce brillante et tres gourmande.',
  '4 blancs de poulet\n2 citrons\n2 cuilleres de miel\n1 branche de thym\nHuile d olive\nSel et poivre',
  'Saisir le poulet dans une poele chaude.\nAjouter jus de citron, miel et thym.\nLaisser reduire doucement.\nServir avec riz ou legumes.',
  'assets/img/recipes/poulet-citron-herbes.webp',
  'plats',
  'published',
  NOW(),
  64,
  NOW(),
  NOW()
),
(
  'Tarte fine aux pommes',
  'tarte-fine-aux-pommes',
  'Un dessert croustillant, dore et tres simple a partager.',
  'Une pate feuilletee fine, des pommes coupees regulierement et une touche de cannelle. Le dessert familial qui marche toujours.',
  '1 pate feuilletee\n4 pommes\n30 g de beurre\n2 cuilleres de sucre\nCannelle',
  'Etaler la pate.\nDisposer les pommes en fines tranches.\nAjouter beurre, sucre et cannelle.\nCuire 25 minutes a 190 degres.',
  'assets/img/recipes/tarte-pommes-fine.webp',
  'desserts',
  'published',
  NOW(),
  52,
  NOW(),
  NOW()
),
(
  'Pates cremeuses aux champignons',
  'pates-cremeuses-aux-champignons',
  'Une assiette genereuse, cremeuse et prete en moins de trente minutes.',
  'Des pates enveloppees dans une sauce aux champignons, creme et parmesan. Une recette reconfortante pour les soirs presses.',
  '350 g de pates\n300 g de champignons\n20 cl de creme\n60 g de parmesan\n1 echalote\nPersil\nSel et poivre',
  'Cuire les pates al dente.\nFaire revenir echalote et champignons.\nAjouter creme et parmesan.\nMelanger avec les pates et servir avec persil.',
  'assets/img/recipes/pates-creme-champignons.webp',
  'plats',
  'published',
  NOW(),
  71,
  NOW(),
  NOW()
),
(
  'Salade mediterraneenne',
  'salade-mediterraneenne',
  'Une salade fraiche, coloree et pleine de soleil.',
  'Tomates, concombre, feta, olives et herbes fraiches pour une entree lumineuse ou un dejeuner leger.',
  '3 tomates\n1 concombre\n150 g de feta\nOlives noires\n1 oignon rouge\nHuile d olive\nCitron\nOrigan',
  'Couper les legumes.\nAjouter feta, olives et oignon rouge.\nAssaisonner avec huile, citron et origan.\nServir bien frais.',
  'assets/img/recipes/salade-mediterraneenne.webp',
  'entrees',
  'published',
  NOW(),
  46,
  NOW(),
  NOW()
),
(
  'Saumon au four et legumes',
  'saumon-au-four-et-legumes',
  'Un plat equilibre, colore et facile a preparer.',
  'Un saumon tendre cuit au four avec legumes rotis, citron et herbes. Ideal pour une assiette saine sans complication.',
  '4 paves de saumon\n2 courgettes\n2 carottes\n1 poivron\n1 citron\nAneth\nHuile d olive\nSel et poivre',
  'Couper les legumes et les assaisonner.\nAjouter le saumon, citron et aneth.\nCuire 20 minutes a 180 degres.\nServir chaud.',
  'assets/img/recipes/saumon-four-legumes.webp',
  'plats',
  'published',
  NOW(),
  59,
  NOW(),
  NOW()
),
(
  'Curry de legumes coco',
  'curry-de-legumes-coco',
  'Un curry vegetarien doux, parfume et tres reconfortant.',
  'Des legumes mijotes dans du lait de coco avec curry, gingembre et coriandre. A servir avec du riz basmati.',
  '1 patate douce\n2 carottes\n1 courgette\n200 g de pois chiches\n40 cl de lait de coco\nCurry\nGingembre\nCoriandre',
  'Faire revenir les epices.\nAjouter les legumes et le lait de coco.\nMijoter 25 minutes.\nAjouter coriandre et servir avec du riz.',
  'assets/img/recipes/curry-legumes-coco.webp',
  'vegetarien',
  'published',
  NOW(),
  68,
  NOW(),
  NOW()
),
(
  'Burger maison gourmand',
  'burger-maison-gourmand',
  'Un burger genereux avec pain brioche et garniture maison.',
  'Un burger de week-end avec steak saisi, fromage fondant, salade croquante et frites maison.',
  '4 pains burger\n4 steaks\n4 tranches de cheddar\nSalade\nTomate\nOignon rouge\nSauce maison\nFrites',
  'Toaster les pains.\nCuire les steaks et faire fondre le fromage.\nAssembler avec legumes et sauce.\nServir avec frites maison.',
  'assets/img/recipes/burger-maison.webp',
  'plats',
  'published',
  NOW(),
  83,
  NOW(),
  NOW()
),
(
  'Risotto parmesan et champignons',
  'risotto-parmesan-et-champignons',
  'Un risotto cremeux, elegant et plein de saveur.',
  'Du riz arborio nacre, un bouillon ajoute progressivement, des champignons dores et beaucoup de parmesan.',
  '320 g de riz arborio\n250 g de champignons\n1 oignon\n90 cl de bouillon\n80 g de parmesan\n10 cl de vin blanc\nBeurre',
  'Faire revenir oignon et riz.\nAjouter le vin puis le bouillon louche par louche.\nIncorporer champignons, beurre et parmesan.\nServir aussitot.',
  'assets/img/recipes/risotto-parmesan.webp',
  'plats',
  'published',
  NOW(),
  41,
  NOW(),
  NOW()
),
(
  'Fondant au chocolat',
  'fondant-au-chocolat',
  'Un dessert intense avec coeur coulant et vraie gourmandise.',
  'Un fondant au chocolat noir, croustillant dehors et coulant au centre. Parfait avec une boule de glace ou quelques fruits rouges.',
  '200 g de chocolat noir\n120 g de beurre\n3 oeufs\n90 g de sucre\n50 g de farine\n1 pincee de sel',
  'Faire fondre chocolat et beurre.\nFouetter oeufs et sucre.\nAjouter farine puis chocolat fondu.\nCuire 10 a 12 minutes a 200 degres.',
  'assets/img/recipes/fondant-chocolat.webp',
  'desserts',
  'published',
  NOW(),
  96,
  NOW(),
  NOW()
),
(
  'Couscous aux legumes',
  'couscous-aux-legumes',
  'Un plat vegetarien genereux, parfume et colore pour les grandes tables.',
  'Un couscous aux legumes fondants, pois chiches et semoule legere. Une recette conviviale qui apporte du soleil au repas familial.',
  '350 g de semoule\n3 carottes\n2 courgettes\n2 navets\n250 g de pois chiches\n1 oignon\nBouillon de legumes\nRas el hanout\nCoriandre',
  'Faire revenir l oignon avec les epices.\nAjouter les legumes et le bouillon puis mijoter 35 minutes.\nPreparer la semoule a part.\nServir les legumes sur la semoule avec coriandre fraiche.',
  'assets/img/recipes/couscous-legumes.webp',
  'vegetarien',
  'published',
  NOW(),
  88,
  NOW(),
  NOW()
),
(
  'Crepes sucrees',
  'crepes-sucrees',
  'Des crepes fines et moelleuses pour le gouter ou un dessert rapide.',
  'Une pate a crepes simple, doree a la poele et servie avec sucre, miel, chocolat ou fruits frais selon les envies.',
  '250 g de farine\n3 oeufs\n50 cl de lait\n30 g de beurre fondu\n1 cuillere de sucre\n1 pincee de sel\nVanille',
  'Melanger farine, sucre et sel.\nAjouter les oeufs puis le lait progressivement.\nIncorporer le beurre fondu.\nLaisser reposer puis cuire les crepes dans une poele chaude.',
  'assets/img/recipes/crepes-sucrees.webp',
  'desserts',
  'published',
  NOW(),
  74,
  NOW(),
  NOW()
),
(
  'Gaufres maison',
  'gaufres-maison',
  'Des gaufres dorees, croustillantes dehors et moelleuses dedans.',
  'Une recette de gaufres maison parfaite pour un brunch ou un gouter gourmand, avec fruits rouges, chantilly ou sucre glace.',
  '250 g de farine\n2 oeufs\n40 g de sucre\n50 cl de lait\n80 g de beurre fondu\n1 sachet de levure\n1 pincee de sel',
  'Melanger les ingredients secs.\nAjouter les oeufs, le lait puis le beurre fondu.\nLaisser reposer 20 minutes.\nCuire dans un gaufrier bien chaud jusqu a coloration.',
  'assets/img/recipes/gaufres-maison.webp',
  'desserts',
  'published',
  NOW(),
  69,
  NOW(),
  NOW()
),
(
  'Lasagnes bolognaise',
  'lasagnes-bolognaise',
  'Un grand classique familial avec sauce bolognaise et fromage gratine.',
  'Des couches de pates, sauce bolognaise maison, bechamel et fromage gratine au four. Un plat reconfortant et facile a partager.',
  '12 feuilles de lasagnes\n500 g de boeuf hache\n700 g de coulis de tomate\n1 oignon\n2 carottes\n50 cl de bechamel\nParmesan\nMozzarella',
  'Preparer la sauce bolognaise avec viande, legumes et tomate.\nMonter les couches de pates, sauce et bechamel.\nAjouter les fromages.\nCuire 40 minutes a 180 degres.',
  'assets/img/recipes/lasagnes-bolognaise.webp',
  'plats',
  'published',
  NOW(),
  102,
  NOW(),
  NOW()
),
(
  'Quiche lorraine maison',
  'quiche-lorraine-maison',
  'Une quiche doree et fondante, ideale avec une salade verte.',
  'Une pate croustillante garnie de lardons, oeufs, creme et fromage. Une recette simple pour un repas du soir ou un buffet.',
  '1 pate brisee\n200 g de lardons\n3 oeufs\n25 cl de creme\n10 cl de lait\n80 g de fromage rape\nMuscade\nPoivre',
  'Precuire legerement la pate.\nFaire dorer les lardons.\nMelanger oeufs, creme, lait et assaisonnement.\nVerser sur la pate et cuire 35 minutes a 180 degres.',
  'assets/img/recipes/quiche-lorraine-maison.webp',
  'plats',
  'published',
  NOW(),
  81,
  NOW(),
  NOW()
),
(
  'Ratatouille provencale',
  'ratatouille-provencale',
  'Des legumes du soleil mijotes doucement avec herbes et huile d olive.',
  'Une ratatouille familiale avec aubergines, courgettes, tomates et poivrons. Elle se sert chaude, tiede ou froide selon la saison.',
  '2 aubergines\n3 courgettes\n4 tomates\n2 poivrons\n1 oignon\n2 gousses d ail\nHuile d olive\nThym\nBasilic',
  'Couper les legumes en morceaux reguliers.\nFaire revenir chaque legume puis les rassembler.\nAjouter tomates, ail et herbes.\nMijoter 35 minutes a feu doux.',
  'assets/img/recipes/ratatouille-provencale.webp',
  'vegetarien',
  'published',
  NOW(),
  77,
  NOW(),
  NOW()
),
(
  'Salade nicoise',
  'salade-nicoise',
  'Une salade complete, fraiche et coloree pour les beaux jours.',
  'Tomates, haricots verts, oeufs, thon, olives et pommes de terre composent une salade genereuse et equilibree.',
  '4 tomates\n300 g de haricots verts\n4 oeufs\n250 g de thon\nOlives noires\nPommes de terre\nHuile d olive\nCitron',
  'Cuire les oeufs, haricots et pommes de terre.\nCouper les tomates.\nDisposer tous les ingredients dans un grand plat.\nAssaisonner avec huile d olive et citron.',
  'assets/img/recipes/salade-nicoise.webp',
  'entrees',
  'published',
  NOW(),
  66,
  NOW(),
  NOW()
),
(
  'Soupe lentilles corail',
  'soupe-lentilles-corail',
  'Une soupe veloutee, rapide et pleine de douceur.',
  'Des lentilles corail cuites avec carottes, epices douces et bouillon. Une soupe simple, nourrissante et tres pratique en semaine.',
  '250 g de lentilles corail\n2 carottes\n1 oignon\n1 litre de bouillon\nCumin\nCurcuma\nHuile d olive\nCoriandre',
  'Rincer les lentilles.\nFaire revenir oignon, carottes et epices.\nAjouter bouillon et lentilles puis cuire 20 minutes.\nMixer et servir avec coriandre.',
  'assets/img/recipes/soupe-lentilles-corail.webp',
  'entrees',
  'published',
  NOW(),
  58,
  NOW(),
  NOW()
),
(
  'Tajine poulet olives citron',
  'tajine-poulet-olives-citron',
  'Un plat mijote parfume avec olives vertes, citron et coriandre.',
  'Du poulet tendre cuit lentement avec epices, olives et citron. Un plat chaleureux qui se partage directement a table.',
  '4 cuisses de poulet\n200 g d olives vertes\n1 citron confit\n2 oignons\nAil\nCurcuma\nGingembre\nCoriandre\nHuile d olive',
  'Faire revenir oignons, ail et epices.\nAjouter le poulet et faire dorer.\nCouvrir et mijoter 40 minutes.\nAjouter olives et citron en fin de cuisson.',
  'assets/img/recipes/tajine-poulet-olives-citron.webp',
  'plats',
  'published',
  NOW(),
  93,
  NOW(),
  NOW()
),
(
  'Tiramisu classique',
  'tiramisu-classique',
  'Un dessert italien cremeux au cafe et cacao.',
  'Un tiramisu traditionnel avec mascarpone, biscuits imbibes de cafe et cacao. Il se prepare a l avance pour un resultat bien fondant.',
  '250 g de mascarpone\n3 oeufs\n80 g de sucre\nBiscuits cuillere\nCafe fort\nCacao non sucre\n1 pincee de sel',
  'Separer les blancs des jaunes.\nFouetter jaunes et sucre puis ajouter mascarpone.\nIncorporer les blancs montes.\nAlterner biscuits imbibes et creme puis reserver au frais.',
  'assets/img/recipes/tiramisu-classique.webp',
  'desserts',
  'published',
  NOW(),
  87,
  NOW(),
  NOW()
);

INSERT INTO recipe_ratings (recipe_id, rating, voter_hash, created_at, updated_at)
SELECT id, 5, SHA2(CONCAT(slug, '-seed-a'), 256), NOW(), NOW() FROM recipes WHERE slug IN ('fondant-au-chocolat', 'burger-maison-gourmand', 'pates-cremeuses-aux-champignons', 'lasagnes-bolognaise', 'tajine-poulet-olives-citron', 'tiramisu-classique');

INSERT INTO recipe_ratings (recipe_id, rating, voter_hash, created_at, updated_at)
SELECT id, 4, SHA2(CONCAT(slug, '-seed-b'), 256), NOW(), NOW() FROM recipes WHERE slug IN ('veloute-de-potimarron', 'tarte-fine-aux-pommes', 'saumon-au-four-et-legumes', 'curry-de-legumes-coco', 'couscous-aux-legumes', 'ratatouille-provencale', 'salade-nicoise', 'soupe-lentilles-corail');

INSERT INTO recipe_ratings (recipe_id, rating, voter_hash, created_at, updated_at)
SELECT id, 5, SHA2(CONCAT(slug, '-seed-c'), 256), NOW(), NOW() FROM recipes WHERE slug IN ('poulet-citron-et-herbes', 'salade-mediterraneenne', 'risotto-parmesan-et-champignons', 'crepes-sucrees', 'gaufres-maison', 'quiche-lorraine-maison');

INSERT INTO recipe_comments (recipe_id, author_name, content, status, visitor_hash, created_at, updated_at)
SELECT id, 'Claire', 'Recette tres claire, parfaite pour un repas en famille.', 'approved', SHA2(CONCAT(slug, '-comment-claire'), 256), NOW(), NOW()
FROM recipes WHERE slug IN ('veloute-de-potimarron', 'fondant-au-chocolat', 'couscous-aux-legumes', 'quiche-lorraine-maison', 'crepes-sucrees');

INSERT INTO recipe_comments (recipe_id, author_name, content, status, visitor_hash, created_at, updated_at)
SELECT id, 'Nadia', 'J ai suivi les etapes sans difficulte, le resultat etait vraiment gourmand.', 'approved', SHA2(CONCAT(slug, '-comment-nadia'), 256), NOW(), NOW()
FROM recipes WHERE slug IN ('poulet-citron-et-herbes', 'pates-cremeuses-aux-champignons', 'lasagnes-bolognaise', 'tajine-poulet-olives-citron', 'tiramisu-classique');

-- Utilisateur MySQL recommande pour le principe du moindre privilege :
-- CREATE USER 'secure_recipes_user'@'%' IDENTIFIED BY 'mot_de_passe_fort_a_changer';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON secure_recipes_greta92.* TO 'secure_recipes_user'@'%';
-- FLUSH PRIVILEGES;
