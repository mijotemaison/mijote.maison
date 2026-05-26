# Mijoté Maison - Secure Recipes GRETA 92

Projet final GRETA 92 cybersécurité : site web sécurisé de recettes de cuisine avec front-office public sous l'identité **Mijoté Maison**, back-office administrateur, CRUD recettes, CRUD administrateurs et protections applicatives documentées.

## Contexte

Le sujet demande une application PHP/MySQL exposée au public, avec une partie publique consultable et une partie admin réservée. La sécurité est prioritaire : XSS, SQLi, CSRF, brute force, upload sécurisé, sessions et documentation.

La stack est alignée avec le sujet officiel : PHP, HTML, CSS, JavaScript, Bootstrap et MySQL. Bootstrap est chargé localement, sans CDN, pour rester compatible avec la CSP.

## Stack

- PHP natif structuré.
- MySQL.
- PDO avec requêtes préparées.
- HTML.
- JavaScript vanilla (4 fichiers : `presentation.js`, `recipes.js`, `admin.js`, `toasts.js`).
- Bootstrap 5 auto-hébergé dans `public/assets/vendor/bootstrap/`.
- CSS applicatif dans `public/assets/css/app.css`.
- Google Fonts (Fraunces + Inter + JetBrains Mono) servis via une CSP nonce.
- Point d'entrée unique AltoRouter (`public/index.php`) + URLs propres compatibles Apache/MAMP, XAMPP, LAMP et Railway.
- Architecture MVC classique demandée en cours : `src/Controller`, `src/Model`, `src/Vues`.

## Fonctionnalités front-office

- Page d'accueil `/` avec hero éditorial, aperçu des recettes et navigation.
- Page liste `/recettes` avec recettes publiées, recherche serveur, filtres par catégorie, pagination et étoiles.
- Page détail `/recette/{slug}` avec titre, image, description, ingrédients, étapes et **JSON-LD `Recipe`** + Open Graph + Twitter Card.
- Page impression dédiée `/recette/{slug}/impression`, sans JavaScript, pensée pour Ctrl+P ou le menu imprimer du navigateur.
- Section “recettes populaires” basée sur le nombre de vues.
- Lien “Version imprimable” depuis chaque page recette.
- Page `/a-propos` orientée présentation du site de recettes.
- Notes lecteurs sur 5 étoiles, avec un vote par empreinte visiteur/session.
- Commentaires publics affichés seulement après modération.
- Page de connexion administrateur `/connexion`.
- Page `/presentation` sous forme de carrousel avec :
  - **Mode présentateur** togglable (notes orales cachées par défaut, visibles uniquement quand activé).
  - **Chronomètre** auto-démarré au premier changement de slide.
  - **Plein écran** via `requestFullscreen()`.
  - Persistance localStorage du mode et du chrono.
  - Transitions fade entre slides + `prefers-reduced-motion` honoré.
- Page `/conformite` : grille officielle sans colonne points, avec réponse du projet, fichiers concernés, extraits réels du code et explications pour le jury.
- Données issues de la base échappées avec `e()`.
- Design éditorial premium AAA : fond blanc bleuté très léger, accents tomate/cuisine et bleu doux, typographie Fraunces (display) + Inter (UI), cartes Bootstrap personnalisées, ombres propres et header respirant.

## Fonctionnalités back-office

- Dashboard admin avec compteurs, dernières recettes, tentatives échouées récentes.
- CRUD complet des recettes avec **catégorie**, **statut brouillon/publié/archivé**, recherche live + pagination 10/page.
- Aperçu admin avant publication.
- Duplication d'une recette en brouillon.
- Modération des commentaires : approuver, refuser, supprimer.
- Journal sécurité complet `/admin/journal-securite` avec filtres, plage de dates, pagination serveur et nettoyage des anciens événements.
- Export CSV du journal sécurité avec les mêmes filtres que l'écran, y compris la plage de dates.
- Upload sécurisé des images de recettes.
- CRUD complet des administrateurs avec **recherche live** + **pagination**.
- Blocage de la suppression du dernier administrateur.
- **Blocage de l'auto-suppression** : un admin ne peut pas supprimer son propre compte (badge « Vous » côté UI + rejet serveur).
- **Modale de confirmation custom** pour toute action sensible (suppressions) : focus trap, Escape, Enter, accessible (`role="alertdialog"`, `aria-modal`).
- **Toasts auto-dismiss** pour les flash messages (4.5 s, slide-in, bouton ✕, `aria-live="polite"`).
- Actions sensibles en POST avec token CSRF.

## Fonctionnalités additionnelles du support prof

- Recherche publique sécurisée côté serveur avec requêtes préparées PDO.
- Filtres publics par catégorie : entrées, plats, desserts, végétarien.
- Pagination publique des recettes publiées.
- Statut de recette côté admin : brouillon, publié, archivé.
- Les brouillons et archives ne sont pas visibles dans le front-office.
- Notes en étoiles stockées dans `recipe_ratings`.
- Commentaires publics stockés dans `recipe_comments` et publiés uniquement après validation admin.
- Recettes populaires par compteur `view_count`.
- Impression recette optimisée via CSS `@media print`.
- Aperçu avant publication et duplication en brouillon côté back-office.
- Journal sécurité consultable et filtrable par type d'événement, recherche libre, IP ou acteur.

## Sécurité appliquée

- Mots de passe hachés avec `password_hash()` en Argon2id si disponible, avec fallback compatible.
- Vérification avec `password_verify()`.
- Réhachage automatique des anciens hashes admin au login quand l'algorithme courant est plus fort.
- Régénération de session après connexion (`session_regenerate_id(true)`).
- Cookies de session `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- Redirection HTTPS forcée quand `APP_ENV=production`.
- Headers de sécurité et **CSP avec nonce par requête** dans `src/Utils/Security/headers.php` :
  `default-src 'self'; script-src 'self' 'nonce-{nonce}'; style-src 'self' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; ...`
- Requêtes SQL préparées dans les repositories.
- Échappement HTML centralisé avec `e()`.
- Tokens CSRF centralisés dans `src/Utils/Security/csrf.php`.
- Limitation brute force via table `login_attempts`.
- Upload limité aux images `jpg`, `jpeg`, `png`, `webp` avec contrôle MIME et taille 2 Mo.
- `.htaccess` dans le dossier upload pour bloquer l'exécution PHP sous Apache.
- Construction DOM par API (pas d'`innerHTML` côté JS) — modale custom 100 % résistante aux injections.
- Protection self-delete admin (UI + serveur).
- Timeout de session admin après 30 minutes d'inactivité.
- Journal de sécurité `security_logs` pour connexions et actions sensibles, avec page admin dédiée, filtres par dates, export CSV et nettoyage des anciennes tentatives.

## Accessibilité

- Skip link `Aller au contenu` visible au focus clavier.
- `aria-live="polite"` sur le compteur de slides et les toasts.
- Focus management sur le carrousel (`tabindex="-1"` sur l'`<article>` actif).
- Contrastes AA sur les textes principaux avec fond clair `#f8fbff` et surfaces blanches.
- `prefers-reduced-motion: reduce` désactive transitions, hover-lift et fade slides.
- Navigation clavier complète : ←/→ pour slides, Esc pour modale, Tab pour focus trap.

## SEO & partage social

- `<title>` et `<meta name="description">` par page.
- **Open Graph** complet (`og:type/title/description/image/url/site_name`).
- **Twitter Card** `summary_large_image`.
- **JSON-LD Recipe** complet sur `/recette/{slug}` (name, image, description, ingredients[], instructions[], totalTime, recipeYield, author, recipeCategory) → éligible Google Rich Results.

## Installation locale

```bash
npm install
npm run check-assets
mysql -u root -p < database.sql
cp .env.example .env
php -S 127.0.0.1:8888 -t public public/index.php
```

Configurer `.env` selon votre MySQL :

```bash
APP_ENV=local
APP_URL=http://localhost:8888
DB_HOST=127.0.0.1
DB_PORT=8889
DB_NAME=secure_recipes_greta92
DB_USER=root
DB_PASSWORD=root
```

> MAMP utilise généralement MySQL sur le port `8889` avec `root/root`. Avec un MySQL local classique, utiliser souvent `3306`. Avec l'instance temporaire de test du projet, utiliser `3307`.

Si la base existe déjà et que l'on veut seulement ajouter les recettes de démonstration complémentaires sans réimport complet :

```bash
php scripts/seed_additional_recipes.php
```

## MAMP / Apache / méthode du prof

Le projet suit maintenant la logique vue en cours :

- **DocumentRoot MAMP/Apache** : pointer vers `public/`.
- **Front controller** : `public/index.php` reçoit les URLs propres et les envoie vers les contrôleurs.
- **Réécriture URL** : `public/.htaccess` renvoie les URLs non-fichiers vers `public/index.php`.
- **URLs principales** : `/`, `/recettes`, `/recette/{slug}`, `/connexion`, `/presentation`, `/conformite`, `/stack`, `/admin/dashboard`.
- **Page conformité** : `/conformite` justifie chaque critère du sujet avec preuves de code.
- **MVC classique** : `src/Controller` et `src/Controller/Admin` préparent les données, `src/Model` appelle les repositories PDO, `src/Vues` affiche le HTML.
- **Back-office routé** : l'admin passe par AltoRouter (`/admin/dashboard`, `/admin/recettes`, `/admin/administrateurs`) et non par des fichiers PHP directs.
- **Repositories conservés** : `src/Repository` garde les requêtes PDO préparées pour ne pas dupliquer l'accès SQL.

Configuration MAMP recommandée :

```text
DocumentRoot : /Users/namto/Desktop/-- PROJET GRETA /public
MySQL host   : 127.0.0.1
MySQL port   : 8889
MySQL user   : root
MySQL pass   : root
Base         : secure_recipes_greta92
```

Équivalents cités dans le cours :

- macOS : MAMP.
- Windows : WAMP, XAMPP ou Laragon.
- Linux : LAMP.
- Apache : activer `mod_rewrite` et autoriser `.htaccess` avec `AllowOverride`.

## Identifiants de démonstration

Usage local uniquement :

- Email : `admin@example.com`
- Mot de passe : `Admin123!`

Ces identifiants doivent être changés en production.

## Commandes utiles

```bash
npm run check-assets        # vérifie Bootstrap local + app.css
find . -name "*.php" -print0 | xargs -0 -n1 php -l    # lint PHP
python3 docs/generate_security_pdf.py                 # régénère le rapport PDF
```

## Rapport de sécurité

- Source : `docs/rapport-securite.md`
- PDF final : `docs/rapport-securite-projet-final-greta92.pdf`
- Vérification conformité sujet officiel : `docs/verification-conformite-sujet-officiel.md`

Le rapport contient les protections et des extraits réels du code du projet.

## Architecture des assets

```
public/assets/
├── css/
│   └── app.css         # design Bootstrap personnalisé
├── vendor/
│   └── bootstrap/      # bootstrap.min.css + bootstrap.bundle.min.js locaux
├── js/
│   ├── presentation.js # carrousel + mode présentateur + chrono
│   ├── recipes.js      # recherche/filtres front-office
│   ├── admin.js        # modale confirm + recherche/pagination admin
│   └── toasts.js       # auto-dismiss flashes (4.5 s)
└── img/
    ├── textures/       # grain.svg + paper.svg (vectoriel)
    ├── recipes/        # photos WebP
    └── logo-mijote-maison.svg
```

## Railway

Le projet est préparé pour un déploiement Railway. Les variables d'environnement nécessaires sont décrites dans `.env.example`.

Le serveur doit pointer vers `public/`. `railway.json` et `Procfile` utilisent :

```bash
php -S 0.0.0.0:$PORT -t public public/index.php
```

Pour la base de données Railway :

- Ajouter un service **MySQL** dans le projet Railway.
- Importer `database.sql` dans MySQL.
- Si `database.sql` a déjà été importé avant l'ajout de nouvelles recettes, lancer `php scripts/seed_additional_recipes.php` avec les variables Railway configurées.
- Vérifier les variables côté service web :
  - `APP_ENV=production`
  - `APP_URL=https://mijotemaison.up.railway.app`
  - `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD` peuvent reprendre les valeurs Railway `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`.
  - `DB_NAME` doit correspondre à la base importée. Avec le fichier `database.sql` actuel : `secure_recipes_greta92`.

Le code accepte aussi les variables natives Railway `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` en fallback si les variables `DB_*` ne sont pas définies.

Le dossier `public/` ne contient plus de pages PHP directes hors `index.php` : le front-office et le back-office passent par AltoRouter.

## Maintenance sécurité

Le nettoyage des anciens logs peut être lancé en ligne de commande ou via une tâche planifiée :

```bash
php scripts/cleanup_security_data.php --days=90
php scripts/cleanup_security_data.php --days=90 --dry-run
```

Le script supprime les entrées anciennes de `security_logs` et `login_attempts`, puis journalise l'action avec l'événement `maintenance_cleanup`. La valeur par défaut vient de `LOG_RETENTION_DAYS`.

## Tests automatisés

Le projet contient une suite PHPUnit couvrant les validations, la sécurité et les repositories d'audit :

```bash
composer install
composer test
```

La suite vérifie notamment `admin_password_hash()`, `password_verify()`, le rehash Argon2id, les tokens CSRF, les validations recettes/admins, le filtrage `security_logs` et le nettoyage des anciennes tentatives de connexion.

## Prochaines améliorations

- Audit Lighthouse complet (cible : Perf > 90, A11y > 95, SEO > 95).
- 2FA TOTP pour les admins.
- Journal externe type SIEM pour une vraie production.
