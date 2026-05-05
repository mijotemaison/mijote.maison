# Mijoté Maison - Secure Recipes GRETA 92

Projet final GRETA 92 cybersécurité : site web securise de recettes de cuisine avec front-office public sous l'identite **Mijoté Maison**, back-office administrateur, CRUD recettes, CRUD administrateurs et protections applicatives documentees.

## Contexte

Le sujet demande une application PHP/MySQL exposee au public, avec une partie publique consultable et une partie admin reservee. La securite est prioritaire : XSS, SQLi, CSRF, brute force, upload securise, sessions et documentation.

Adaptation demandee : Tailwind CSS remplace Bootstrap et le CSS classique. Le reste de la stack reste conforme au sujet.

## Stack

- PHP natif structure.
- MySQL.
- PDO avec requetes preparees.
- HTML.
- JavaScript vanilla.
- Tailwind CSS compile localement.

## Fonctionnalites front-office

- Page d'accueil `/` avec presentation du site, aperçu des recettes et navigation.
- Page liste `/recipes.php` avec toutes les recettes, recherche et filtres.
- Page detail `/recipe.php` avec titre, image, description, ingredients et etapes.
- Page de connexion administrateur.
- Page `/presentation.php` sous forme de carrousel type PowerPoint.
- Donnees issues de la base echappees avec `e()`.
- Design public chaleureux inspire des sites de recettes grand public, avec 10 photos WebP et logo SVG original.

## Fonctionnalites back-office

- Dashboard admin.
- CRUD complet des recettes.
- Upload securise des images de recettes.
- CRUD complet des administrateurs.
- Blocage de la suppression du dernier administrateur.
- Actions sensibles en POST avec token CSRF.

## Securite appliquee

- Mots de passe haches avec `password_hash()`.
- Verification avec `password_verify()`.
- Regeneration de session apres connexion.
- Cookies de session `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- Headers de securite et CSP dans `app/security/headers.php`.
- Requetes SQL preparees dans les repositories.
- Echappement HTML centralise avec `e()`.
- Tokens CSRF centralises dans `app/security/csrf.php`.
- Limitation brute force via table `login_attempts`.
- Upload limite aux images `jpg`, `jpeg`, `png`, `webp` avec controle MIME et taille 2 Mo.
- `.htaccess` dans le dossier upload pour bloquer l'execution PHP sous Apache.

## Installation locale

```bash
npm install
npm run build-css
mysql -u root -p < database.sql
cp .env.example .env
php -S localhost:8000 -t public
```

Configurer `.env` selon votre MySQL :

```bash
APP_ENV=local
APP_URL=http://localhost:8000
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=secure_recipes_greta92
DB_USER=root
DB_PASSWORD=
```

## Identifiants de demonstration

Usage local uniquement :

- Email : `admin@example.com`
- Mot de passe : `Admin123!`

Ces identifiants doivent etre changes en production.

## Commandes utiles

```bash
npm run build-css
npm run watch-css
find . -name "*.php" -print0 | xargs -0 -n1 php -l
python3 docs/generate_security_pdf.py
```

## Rapport de securite

- Source : `docs/rapport-securite.md`
- PDF final : `docs/rapport-securite-projet-final-greta92.pdf`

Le rapport contient les protections et des extraits reels du code du projet.

## Railway

Le projet est prepare pour un deploiement Railway. Le lien de production sera ajoute apres deploiement. Les variables d'environnement necessaires sont decrites dans `.env.example`.

Le serveur doit pointer vers `public/`. `railway.json` et `Procfile` utilisent :

```bash
php -S 0.0.0.0:$PORT -t public
```

Les vrais fichiers admin restent dans `admin/`. Les fichiers `public/admin/*` sont des wrappers qui chargent ces pages pour rendre les URLs `/admin/...` compatibles avec une racine web `public/`.

## Prochaines ameliorations

- Forcer HTTPS en production.
- Ajouter tests PHPUnit.
- Ajouter journalisation admin detaillee.
- Ajouter rotation/nettoyage planifie des tentatives de connexion anciennes.
- Ajouter audit automatise de dependances et configuration serveur.
