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

CREATE TABLE IF NOT EXISTS recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  short_description VARCHAR(300) NOT NULL,
  description TEXT NOT NULL,
  ingredients TEXT NOT NULL,
  preparation_steps TEXT NOT NULL,
  image_path VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_recipes_slug (slug)
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

INSERT INTO recipes (title, slug, short_description, description, ingredients, preparation_steps, image_path, created_at, updated_at)
VALUES
(
  'Veloute de potimarron protege',
  'veloute-de-potimarron-protege',
  'Une entree douce et epicee, servie avec une hygiene de donnees irreprochable.',
  'Un veloute de potimarron onctueux, ideal pour presenter une recette simple dans une interface securisee.',
  '1 potimarron\n1 oignon\n2 gousses d ail\n70 cl de bouillon\n10 cl de creme\nSel, poivre, muscade',
  'Laver et couper le potimarron.\nFaire revenir l oignon et l ail.\nAjouter le bouillon puis cuire 25 minutes.\nMixer, assaisonner et servir chaud.',
  NULL,
  NOW(),
  NOW()
),
(
  'Poulet citron et journalisation',
  'poulet-citron-et-journalisation',
  'Un plat vif et parfume inspire par la traçabilite des actions sensibles.',
  'Le poulet citron associe cuisson maitrisee et sauce reduite. Dans le projet, il illustre l affichage public de donnees echappees.',
  '4 blancs de poulet\n2 citrons\n2 cuilleres de miel\n1 branche de thym\nHuile d olive\nSel et poivre',
  'Saisir le poulet dans une poele chaude.\nAjouter jus de citron, miel et thym.\nLaisser reduire doucement.\nServir avec riz ou legumes.',
  NULL,
  NOW(),
  NOW()
),
(
  'Tarte fine anti-injection',
  'tarte-fine-anti-injection',
  'Une tarte croustillante qui rappelle que les entrees utilisateur doivent etre parametrees.',
  'Cette tarte aux pommes est volontairement simple pour mettre en valeur la lisibilite des fiches recettes.',
  '1 pate feuilletee\n4 pommes\n30 g de beurre\n2 cuilleres de sucre\nCannelle',
  'Etaler la pate.\nDisposer les pommes en fines tranches.\nAjouter beurre, sucre et cannelle.\nCuire 25 minutes a 190 degres.',
  NULL,
  NOW(),
  NOW()
);

-- Utilisateur MySQL recommande pour le principe du moindre privilege :
-- CREATE USER 'secure_recipes_user'@'%' IDENTIFIED BY 'mot_de_passe_fort_a_changer';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON secure_recipes_greta92.* TO 'secure_recipes_user'@'%';
-- FLUSH PRIVILEGES;
