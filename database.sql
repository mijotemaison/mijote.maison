CREATE DATABASE IF NOT EXISTS secure_recipes_greta92
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE secure_recipes_greta92;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS recipes;

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

CREATE TABLE IF NOT EXISTS login_attempts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NULL,
  ip_address VARCHAR(45) NOT NULL,
  user_agent VARCHAR(255) NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_login_attempts_lookup (email, ip_address, success, created_at)
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

TRUNCATE TABLE recipes;

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
);

-- Utilisateur MySQL recommande pour le principe du moindre privilege :
-- CREATE USER 'secure_recipes_user'@'%' IDENTIFIED BY 'mot_de_passe_fort_a_changer';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON secure_recipes_greta92.* TO 'secure_recipes_user'@'%';
-- FLUSH PRIVILEGES;
