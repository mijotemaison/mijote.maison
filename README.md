# Mijoté Maison - Secure Recipes GRETA 92

Projet final GRETA 92 cybersécurité : site web sécurisé de recettes de cuisine avec front-office public sous l'identité **Mijoté Maison**, back-office administrateur, CRUD recettes, CRUD administrateurs et protections applicatives documentées.

## Contexte

Le sujet demande une application PHP/MySQL exposée au public, avec une partie publique consultable et une partie admin réservée. La sécurité est prioritaire : XSS, SQLi, CSRF, brute force, upload sécurisé, sessions et documentation.

Adaptation demandée : Tailwind CSS remplace Bootstrap et le CSS classique. Le reste de la stack reste conforme au sujet.

## Stack

- PHP natif structuré.
- MySQL.
- PDO avec requêtes préparées.
- HTML.
- JavaScript vanilla (4 fichiers : `presentation.js`, `recipes.js`, `admin.js`, `toasts.js`).
- Tailwind CSS 3.4 compilé localement.
- Google Fonts (Fraunces + Inter + JetBrains Mono) servis via une CSP nonce.
- Front controller léger (`public/router.php`) + URLs propres compatibles Apache/MAMP.

## Fonctionnalités front-office

- Page d'accueil `/` avec hero éditorial, aperçu des recettes et navigation.
- Page liste `/recettes` avec recettes publiées, recherche serveur, filtres par catégorie, pagination et étoiles.
- Page détail `/recette/{slug}` avec titre, image, description, ingrédients, étapes et **JSON-LD `Recipe`** + Open Graph + Twitter Card.
- Section “recettes populaires” basée sur le nombre de vues.
- Bouton d'impression propre sur la page recette.
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
- Données issues de la base échappées avec `e()`.
- Design éditorial premium AAA : palette parchment/tomato/saffron/herb, typographie Fraunces (display) + Inter (UI), grain papier subtil, ombres multi-couches, filets dorés, header en verre dépoli.

## Fonctionnalités back-office

- Dashboard admin avec compteurs, dernières recettes, tentatives échouées récentes.
- CRUD complet des recettes avec **catégorie**, **statut brouillon/publié/archivé**, recherche live + pagination 10/page.
- Aperçu admin avant publication.
- Duplication d'une recette en brouillon.
- Modération des commentaires : approuver, refuser, supprimer.
- Journal sécurité complet `/admin/security-logs/index.php` avec filtres, pagination serveur et nettoyage des anciens événements.
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

- Mots de passe hachés avec `password_hash()`.
- Vérification avec `password_verify()`.
- Régénération de session après connexion (`session_regenerate_id(true)`).
- Cookies de session `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- Headers de sécurité et **CSP avec nonce par requête** dans `app/security/headers.php` :
  `default-src 'self'; script-src 'self' 'nonce-{nonce}'; style-src 'self' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; ...`
- Requêtes SQL préparées dans les repositories.
- Échappement HTML centralisé avec `e()`.
- Tokens CSRF centralisés dans `app/security/csrf.php`.
- Limitation brute force via table `login_attempts`.
- Upload limité aux images `jpg`, `jpeg`, `png`, `webp` avec contrôle MIME et taille 2 Mo.
- `.htaccess` dans le dossier upload pour bloquer l'exécution PHP sous Apache.
- Construction DOM par API (pas d'`innerHTML` côté JS) — modale custom 100 % résistante aux injections.
- Protection self-delete admin (UI + serveur).
- Timeout de session admin après 30 minutes d'inactivité.
- Journal de sécurité `security_logs` pour connexions et actions sensibles, avec page admin dédiée et nettoyage des anciennes tentatives.

## Accessibilité

- Skip link `Aller au contenu` visible au focus clavier.
- `aria-live="polite"` sur le compteur de slides et les toasts.
- Focus management sur le carrousel (`tabindex="-1"` sur l'`<article>` actif).
- Contrastes AA sur tous les textes (`text-ink` sur `bg-parchment`).
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
npm run build-css
mysql -u root -p < database.sql
cp .env.example .env
php -S 127.0.0.1:8888 -t public public/router.php
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

## MAMP / Apache / méthode du prof

Le projet suit la logique vue en cours sans refonte risquée :

- **DocumentRoot MAMP/Apache** : pointer vers `public/`.
- **Front controller** : `public/router.php` reçoit les URLs propres et charge la bonne page.
- **Réécriture URL** : `public/.htaccess` renvoie les URLs non-fichiers vers le routeur.
- **URLs principales** : `/`, `/recettes`, `/recette/{slug}`, `/connexion`, `/presentation`, `/stack`.
- **Compatibilité** : les anciennes URLs `.php` restent accessibles (`/recipes.php`, `/recipe.php?slug=...`, `/login.php`).
- **MVC adapté** : les repositories PDO jouent le rôle de Model, les pages PHP publiques/admin servent de contrôleurs légers et de vues, `app/security` regroupe les protections transversales.

Configuration MAMP recommandée :

```text
DocumentRoot : /Users/namto/Desktop/-- PROJET GRETA /public
MySQL host   : 127.0.0.1
MySQL port   : 8889
MySQL user   : root
MySQL pass   : root
Base         : secure_recipes_greta92
```

## Identifiants de démonstration

Usage local uniquement :

- Email : `admin@example.com`
- Mot de passe : `Admin123!`

Ces identifiants doivent être changés en production.

## Commandes utiles

```bash
npm run build-css           # compile Tailwind (~280 ms, output ~36 KB)
npm run watch-css           # recompile à la volée
find . -name "*.php" -print0 | xargs -0 -n1 php -l    # lint PHP
python3 docs/generate_security_pdf.py                 # régénère le rapport PDF
```

## Rapport de sécurité

- Source : `docs/rapport-securite.md`
- PDF final : `docs/rapport-securite-projet-final-greta92.pdf`

Le rapport contient les protections et des extraits réels du code du projet.

## Architecture des assets

```
public/assets/
├── css/
│   ├── input.css       # source Tailwind + design system AAA
│   └── output.css      # compilé minifié
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
php -S 0.0.0.0:$PORT -t public public/router.php
```

Les vrais fichiers admin restent dans `admin/`. Les fichiers `public/admin/*` sont des wrappers qui chargent ces pages pour rendre les URLs `/admin/...` compatibles avec une racine web `public/`.

## Prochaines améliorations

- Forcer HTTPS en production.
- Ajouter tests PHPUnit (Repository + Security).
- Audit Lighthouse complet (cible : Perf > 90, A11y > 95, SEO > 95).
- Automatiser le nettoyage des logs via cron Railway ou tâche serveur.
- 2FA TOTP pour les admins.
- Export CSV du journal sécurité pour audit externe.
- Argon2id en remplacement de bcrypt par défaut.
