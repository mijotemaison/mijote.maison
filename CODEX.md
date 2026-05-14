# CODEX.md - Guide de travail Codex

## Rôle du projet

Secure Recipes GRETA 92 est le projet final d'une formation GRETA 92 cybersécurité. Son nom public est Mijoté Maison. Il doit démontrer la capacité à construire une application web PHP/MySQL complète, lisible, documentée et sécurisée.

## Résumé du sujet

Le site propose des recettes de cuisine au public et un back-office réservé aux administrateurs. Le public consulte uniquement. Les administrateurs authentifiés gèrent les recettes et les comptes administrateurs. Les protections XSS, SQLi, CSRF, brute force, upload sécurisé, sessions sécurisées et CSP doivent être visibles dans le code et expliquées dans la documentation.

## Stack

- PHP natif structuré.
- MySQL.
- PDO obligatoire.
- HTML.
- JavaScript vanilla (DOM API, **pas** d'`innerHTML` avec contenu dynamique).
- Bootstrap 5 local, sans CDN, comme demandé par le sujet.
- CSS applicatif dans `public/assets/css/app.css`.
- Google Fonts (Fraunces + Inter + JetBrains Mono) servis via CSP nonce.
- Markdown / HTML / PDF pour la documentation.

## Architecture

- `public/` contient uniquement le front controller, `.htaccess`, les assets publics, les uploads et le favicon.
- `public/index.php` est le point d'entrée unique AltoRouter pour le front-office et le back-office.
- `public/.htaccess` permet a Apache/MAMP de renvoyer les URLs non-fichiers vers `public/index.php`.
- `src/Controller/` contient les controleurs MVC publics et les controleurs admin.
- `src/Model/` contient les modeles MVC publics qui appellent les repositories PDO.
- `src/Vues/` contient les vues PHP publiques et admin.
- `app/config/` contient la configuration.
- `app/helpers/` contient les fonctions transverses (`e()`, `public_header()`, `nav_link()`, `render_flash()`, etc.).
- `app/security/` contient les protections (auth, CSRF, brute force, upload, headers + nonce CSP).
- `app/repositories/` contient les requêtes PDO.
- `app/validation/` contient les validations serveur.
- `docs/` contient le rapport.

## Methode du prof / MVC classique

- URLs principales : `/`, `/recettes`, `/recette/{slug}`, `/recette/{slug}/impression`, `/connexion`, `/presentation`, `/conformite`, `/stack`, `/admin/dashboard`, `/admin/recettes`, `/admin/administrateurs`.
- Les anciennes URLs `.php` publiques et admin ont été supprimées : tout passe par `public/index.php`.
- Controller = `src/Controller/*` pour le front-office public et `src/Controller/Admin/*` pour le back-office.
- Model = `src/Model/*`, avec delegation vers `app/repositories/*` pour les requetes PDO prepare/execute.
- Vue = `src/Vues/*.tpl.php` et `src/Vues/admin/*.tpl.php`.
- Front controller = `public/index.php` avec AltoRouter.
- Rewrite Apache/MAMP = `public/.htaccess`.
- DocumentRoot MAMP/Apache = dossier `public/`.
- Environnements cites par le prof : macOS = MAMP, Windows = WAMP/XAMPP/Laragon, Linux = LAMP.
- Page `/conformite` = preuve jury : grille officielle sans colonne points, reponse du projet, fichiers, extraits reels du code et explications.

## Conventions de code

- PHP clair, fonctions courtes, noms explicites.
- Pas de framework lourd.
- Ne pas réintroduire l’ancien framework CSS utilitaire.
- Pas de secret en dur.
- Toujours échapper les données affichées avec `e()`.
- Toujours utiliser PDO `prepare()` puis `execute()`.
- Toujours vérifier le CSRF avant une action sensible.
- Les suppressions passent par POST uniquement.
- Les mots de passe ne sont jamais affichés et jamais stockés en clair.
- **Aucun `<script>` inline** sans nonce CSP — toujours préférer un fichier externe `/assets/js/*.js`.
- Pour le JSON-LD ou autre script inline indispensable : `<script ... nonce="<?= e(csp_nonce()) ?>">`.
- **Pas d'`innerHTML`** côté JS quand le contenu inclut une donnée dynamique — utiliser `document.createElement()` + `textContent`. Voir `public/assets/js/admin.js` pour le pattern.

## Conventions UI / design

- **Pattern `data-confirm="<message>"`** sur tout `<form>` ou `<button>` qui exécute une action sensible. La modale custom est rendue automatiquement par `admin.js`.
- Flash messages → `render_flash()` produit un `.flash-stack` de toasts auto-dismiss. Auto-fermeture 4.5 s, bouton ✕, accessible (`aria-live="polite"`, `role="alert"`).
- Tableaux admin → `<table data-table="<key>" data-page-size="10">` + lignes `<tr data-search="<haystack>">` + toolbar `[data-table-toolbar="<key>"]` avec `[data-table-search]`, `[data-table-prev]`, `[data-table-next]`, `[data-table-indicator]`. Activation automatique par `admin.js`.
- Skip link → injecté en première ligne du `<body>` par `public_header()` (`<a class="skip-link" href="#main">`).
- Open Graph & SEO → `public_header($title, $og)` accepte un tableau optionnel `['type' => 'article', 'title' => '...', 'description' => '...', 'image' => '/path/relatif']`. URL et image sont normalisées en absolu automatiquement.
- JSON-LD → uniquement sur `/recette/{slug}`, généré depuis les colonnes `ingredients` et `preparation_steps`, encodé avec `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES`, injecté avec nonce CSP.
- Classes design AAA dans `public/assets/css/app.css` (`.lux-card`, `.recipe-card`, `.flash-toast`, `.modal-confirm`, `.presenter-bar`, etc.). Ne **pas** redéfinir ces classes en inline.

## Règles de sécurité

- `password_hash()` pour créer un mot de passe.
- `password_verify()` pour vérifier un mot de passe.
- `session_regenerate_id(true)` après connexion.
- Cookies de session `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- Headers de sécurité centralisés dans `app/security/headers.php`.
- **CSP avec nonce par requête** : `script-src 'self' 'nonce-{nonce}'`. Helper `csp_nonce()` génère/cache la valeur.
- `style-src` autorise `https://fonts.googleapis.com`. `font-src` autorise `data:` + `https://fonts.gstatic.com`.
- Upload limité aux images `jpg`, `jpeg`, `png`, `webp`.
- Vérification MIME avec `finfo_file()`.
- Nom de fichier aléatoire via `random_bytes()`.
- Protection brute force via table `login_attempts`.
- **Self-delete admin bloqué** côté UI (badge « Vous » via `current_admin_id()`) ET côté serveur (rejet dans `src/Controller/Admin/AdminUserController.php`).
- **Suppression du dernier admin bloquée**.

## Installation locale

```bash
npm install
npm run check-assets
mysql -u root -p < database.sql
php -S 127.0.0.1:8888 -t public public/index.php
```

Avec les URLs propres, utiliser de preference :

```bash
php -S 127.0.0.1:8888 -t public public/index.php
```

## Variables d'environnement

Copier `.env.example` vers `.env` si l'environnement local le permet :

```bash
DB_HOST=127.0.0.1
DB_PORT=8889        # MAMP par defaut. Utiliser 3306 ou 3307 selon le MySQL local.
DB_NAME=secure_recipes_greta92
DB_USER=root
DB_PASSWORD=root
APP_ENV=local
APP_URL=http://localhost:8888
```

## Commandes assets

```bash
npm run check-assets    # vérifie Bootstrap local + app.css
```

## Checklist de tests

- Accueil accessible.
- Liste recettes depuis MySQL.
- Détail recette accessible.
- Login admin valide.
- Login invalide refusé.
- Accès admin sans session redirige.
- CRUD recettes.
- CRUD administrateurs.
- Suppression dernier admin refusée.
- **Suppression de soi-même refusée** (UI + serveur).
- Modale de confirmation s'affiche pour toute action sensible.
- Toast de flash apparaît en haut-droite et disparaît après 4.5 s.
- Recherche admin filtre les lignes en live.
- Pagination admin fonctionnelle (10/page).
- Mode présentateur masque/révèle les notes orales.
- Chrono démarre au premier changement de slide.
- Plein écran fonctionne (⛶).
- Upload image valide accepté.
- Upload `.php` refusé.
- CSRF invalide refusé.
- XSS affichée sous forme de texte.
- SQLi login sans effet.
- Brute force déclenche le blocage.
- CSP présente avec nonce différent à chaque requête.
- JSON-LD `Recipe` valide sur `/recette/{slug}` ([validator.schema.org](https://validator.schema.org/)).
- Open Graph présent (vérifier dans la source HTML).
- Skip link visible au focus clavier (Tab depuis la barre d'adresse).
- `prefers-reduced-motion` honoré (animations désactivées).
- Présentation fonctionnelle.

## Checklist avant push

- `npm run check-assets`.
- `find . -name "*.php" -print0 | xargs -0 -n1 php -l`.
- Vérifier `git status`.
- Ne pas commiter `.env`.
- Générer le PDF du rapport.
- Commit clair.

## Règles Git

- Ne pas écraser les changements utilisateur.
- Commiter uniquement un projet cohérent.
- Détecter la branche principale avant push.
- Message final attendu : `Final GRETA cybersecurity recipes project`.
