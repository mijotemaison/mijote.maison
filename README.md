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
- JavaScript vanilla (3 fichiers : `presentation.js`, `recipes.js`, `admin.js`, `toasts.js`).
- Tailwind CSS 3.4 compilé localement.
- Google Fonts (Fraunces + Inter + JetBrains Mono) servis via une CSP nonce.

## Fonctionnalités front-office

- Page d'accueil `/` avec hero éditorial, aperçu des recettes et navigation.
- Page liste `/recipes.php` avec toutes les recettes, recherche live et filtres par catégorie.
- Page détail `/recipe.php` avec titre, image, description, ingrédients, étapes et **JSON-LD `Recipe`** + Open Graph + Twitter Card.
- Page de connexion administrateur.
- Page `/presentation.php` sous forme de carrousel avec :
  - **Mode présentateur** togglable (notes orales cachées par défaut, visibles uniquement quand activé).
  - **Chronomètre** auto-démarré au premier changement de slide.
  - **Plein écran** via `requestFullscreen()`.
  - Persistance localStorage du mode et du chrono.
  - Transitions fade entre slides + `prefers-reduced-motion` honoré.
- Données issues de la base échappées avec `e()`.
- Design éditorial premium AAA : palette parchment/tomato/saffron/herb, typographie Fraunces (display) + Inter (UI), grain papier subtil, ombres multi-couches, filets dorés, header en verre dépoli.

## Fonctionnalités back-office

- Dashboard admin avec compteurs, dernières recettes, tentatives échouées récentes.
- CRUD complet des recettes avec **recherche live** + **pagination 10/page**.
- Upload sécurisé des images de recettes.
- CRUD complet des administrateurs avec **recherche live** + **pagination**.
- Blocage de la suppression du dernier administrateur.
- **Blocage de l'auto-suppression** : un admin ne peut pas supprimer son propre compte (badge « Vous » côté UI + rejet serveur).
- **Modale de confirmation custom** pour toute action sensible (suppressions) : focus trap, Escape, Enter, accessible (`role="alertdialog"`, `aria-modal`).
- **Toasts auto-dismiss** pour les flash messages (4.5 s, slide-in, bouton ✕, `aria-live="polite"`).
- Actions sensibles en POST avec token CSRF.

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
- **JSON-LD Recipe** complet sur `/recipe.php` (name, image, description, ingredients[], instructions[], totalTime, recipeYield, author, recipeCategory) → éligible Google Rich Results.

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

> Note : si MySQL local tourne sur un autre port (ex. 3307 pour une instance dédiée au projet), ajuster `DB_PORT`.

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
php -S 0.0.0.0:$PORT -t public
```

Les vrais fichiers admin restent dans `admin/`. Les fichiers `public/admin/*` sont des wrappers qui chargent ces pages pour rendre les URLs `/admin/...` compatibles avec une racine web `public/`.

## Prochaines améliorations

- Forcer HTTPS en production.
- Ajouter tests PHPUnit (Repository + Security).
- Audit Lighthouse complet (cible : Perf > 90, A11y > 95, SEO > 95).
- Ajouter rotation/nettoyage planifié des tentatives de connexion anciennes.
- 2FA TOTP pour les admins.
- Audit log persistant (qui a fait quoi, quand).
- Argon2id en remplacement de bcrypt par défaut.
