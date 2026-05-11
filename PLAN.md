# PLAN.md - Secure Recipes GRETA 92

## Resume du sujet officiel

Le projet final GRETA 92 demande la creation d'un site web securise de recettes de cuisine. Le site doit proposer un front-office public pour consulter les recettes et un back-office reserve aux administrateurs authentifies. La securite applicative est une exigence centrale : authentification, sessions securisees, protection XSS, protection SQLi, protection CSRF, limitation brute force, upload securise et documentation technique.

Le sujet officiel PDF est present dans le dossier sous le nom `00-sujet-final-analyste-cybersécurite-r.pdf`. Le prompt projet mentionne aussi `00-sujet-final-analyste-cybersecurite-r.pdf`; la version locale avec accent est conservee.

## Stack retenue

- PHP natif structure, sans framework lourd.
- MySQL avec PDO et requetes preparees.
- HTML et JavaScript vanilla.
- Tailwind CSS a la place de Bootstrap, selon l'adaptation demandee.
- Sessions PHP securisees.
- Documentation Markdown et rapport PDF.

## Architecture attendue

- `public/` : front-office, connexion, deconnexion, presentation, assets publics.
- `public/router.php` : front controller leger pour les URLs propres.
- `public/.htaccess` : reecriture Apache/MAMP vers le routeur pour les URLs non-fichiers.
- `admin/` : back-office protege.
- `app/config/` : configuration application et base de donnees.
- `app/helpers/` : helpers d'affichage, flash messages et chemins.
- `app/security/` : headers, sessions, authentification, CSRF, brute force, upload.
- `app/repositories/` : acces base de donnees via PDO.
- `app/validation/` : validation serveur.
- `docs/` : rapport de securite source et PDF.
- `storage/logs/` : espace de logs applicatifs.

## Grille d'evaluation transformee en checklist

- [x] Architecture generale claire front-office / back-office / app : 3 pts.
- [x] Page d'accueil avec recettes, navigation et aucune action sensible publique : 2 pts.
- [x] Page recette complete et lisible avec donnees base : 2 pts.
- [x] Page de connexion fonctionnelle avec erreurs propres et acces restreint : 2 pts.
- [x] CRUD recettes complet : 3 pts.
- [x] CRUD administrateurs complet : 2 pts.
- [x] Authentification securisee, mots de passe haches, sessions protegees : 4 pts.
- [x] Protection SQLi par PDO prepare/execute partout : 4 pts.
- [x] Protection XSS par validation, echappement et absence de HTML utilisateur : 4 pts.
- [x] CSP coherente : 2 pts.
- [x] Protection CSRF sur formulaires sensibles : 4 pts.
- [x] Protection brute force avec journalisation et blocage temporaire : 3 pts.
- [x] Upload securise : MIME, extension, taille, nom aleatoire, pas d'execution : 3 pts.
- [x] Qualite du code : lisibilite, organisation, commentaires utiles : 3 pts.
- [x] Documentation securite claire avec extraits reels : 4 pts.

## Plan d'execution

### Phase 1 - Analyse et preparation

- [x] Verifier le contenu du dossier.
- [x] Verifier la presence du PDF local.
- [x] Creer l'arborescence principale.
- [x] Creer `PLAN.md`.
- [x] Creer `CODEX.md`.
- [x] Creer `SOUL.md`.
- [x] Creer `README.md` initial.

### Phase 2 - Base technique

- [x] Creer `database.sql`.
- [x] Creer `.env.example`, `.gitignore`, configuration Railway.
- [x] Creer connexion PDO.
- [x] Creer helpers communs.
- [x] Creer sessions securisees.
- [x] Creer headers securite et CSP.
- [x] Creer CSRF.
- [x] Creer validation recettes/admins.
- [x] Creer upload securise.
- [x] Creer brute force.

### Phase 3 - Front-office

- [x] Accueil public.
- [x] Liste complete des recettes sur `/recettes` (`/recipes.php` reste compatible).
- [x] Detail recette.
- [x] Design responsive.
- [x] Echappement systematique.

### Phase 4 - Authentification

- [x] Connexion admin.
- [x] Deconnexion.
- [x] Protection admin.
- [x] Brute force.

### Phase 5 - Back-office

- [x] Dashboard.
- [x] CRUD recettes.
- [x] CRUD administrateurs.
- [x] Upload image.
- [x] CSRF partout.
- [x] Messages flash.

### Phase 6 - Design premium

- [x] Harmoniser Tailwind.
- [x] Interface sombre cybersécurité.
- [x] UI formulaires/tableaux.
- [x] Responsive.

### Phase 7 - Page presentation

- [x] Creer carrousel type PowerPoint.
- [x] Ajouter les 16 slides.
- [x] Ajouter navigation clavier.

### Phase 8 - Documentation

- [x] Completer README.
- [x] Completer PLAN.
- [x] Creer rapport Markdown.
- [x] Inclure extraits reels du code.
- [x] Generer le PDF.

### Phase 9 - Tests et corrections

- [x] Verifier syntaxe PHP.
- [x] Verifier generation Tailwind.
- [x] Verifier routes principales.
- [x] Verifier protections de securite.
- [x] Corriger les erreurs detectees.

### Phase 10 - Git

- [x] Initialiser/configurer Git si necessaire.
- [x] Ajouter le remote GitHub.
- [x] Commit final.
- [x] Push vers la branche principale detectee.

## Choix techniques

- Tailwind sera compile localement via `npm run build-css` pour eviter le CDN en production et permettre une CSP plus stricte.
- Les actions sensibles seront exclusivement en POST.
- Les images seront stockees dans `public/uploads/recipes` avec `.htaccess` pour bloquer l'execution PHP en contexte Apache.
- Les identifiants de base de donnees viendront de variables d'environnement, avec valeurs locales de developpement.
- Un administrateur de demonstration sera cree dans `database.sql` avec un hash `password_hash`.

## Suivi des protections securite

- [x] Authentification : `password_hash`, `password_verify`, `session_regenerate_id(true)`.
- [x] Sessions : cookies `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- [x] XSS : helper `e()` et validation serveur.
- [x] CSP : `app/security/headers.php`.
- [x] SQLi : repositories PDO prepares.
- [x] CSRF : `app/security/csrf.php`.
- [x] Brute force : `login_attempts` et `app/security/brute_force.php`.
- [x] Upload : `app/security/upload.php`.
- [x] Acces admin : `require_admin()`.

## Transparence

Tout point non termine ou non testable localement sera indique ici et dans le README avant livraison.

## Tests effectues le 5 mai 2026

- [x] `npm install`.
- [x] `npm run build-css`.
- [x] Lint PHP sur tous les fichiers `.php`.
- [x] Generation du PDF de securite.
- [x] Rendu PNG du PDF via `pdftoppm` et inspection visuelle.
- [x] Serveur local `php -S 127.0.0.1:8000 -t public`.
- [x] Accueil accessible et headers securite presents.
- [x] Page presentation accessible avec `Slide 1 / 16`.
- [x] Acces `/admin/dashboard.php` sans session redirige vers `/connexion`.
- [x] Tests MySQL reels executes sur instance temporaire isolee `127.0.0.1:3307`.
- [x] Import `database.sql` verifie : tables `admins`, `recipes`, `login_attempts`, admin initial et recettes seed.
- [x] Front-office verifie depuis MySQL : accueil, detail recette, presentation, CSP.
- [x] Authentification verifiee : mauvais login refuse, SQLi login sans effet, login valide, regeneration de session.
- [x] Back-office verifie : dashboard protege, CRUD recettes, CRUD administrateurs, suppression dernier admin refusee.
- [x] Protections verifiees : CSRF invalide refuse, XSS echappee, upload image valide accepte, upload `.php` et fichier trop lourd refuses, brute force bloquee apres 5 echecs.
- [x] Resultat suite MySQL : 29 tests passes / 29.
- [x] Conformite PDF front-office verifiee : accueil `/`, liste recettes `/recipes.php`, detail separe `/recipe.php`, page de connexion nommee explicitement.
- [x] Conformite PDF front-office maintenue avec URLs propres : accueil `/`, liste `/recettes`, detail `/recette/{slug}`, connexion `/connexion`.

## Phase 12 - Alignement avec la methode du prof (MAMP / Apache / front controller)

- [x] Analyse du support `jour11/03-start.md` : MAMP/WAMP/LAMP si besoin de `mod_rewrite`, point d'entree unique, `.htaccess`, organisation MVC.
- [x] Ajout de `public/router.php` comme front controller leger.
- [x] Ajout de `public/.htaccess` pour Apache/MAMP : reecriture des URLs propres vers le routeur.
- [x] URLs propres principales : `/recettes`, `/recette/{slug}`, `/connexion`, `/presentation`, `/stack`.
- [x] Anciennes URLs `.php` conservees pour eviter les regressions.
- [x] Documentation MAMP ajoutee : DocumentRoot `public/`, MySQL MAMP `127.0.0.1:8889`, user/pass `root/root`.
- [x] Equivalence MVC documentee : repositories = Model, pages PHP = controllers/vues, `app/security` = protections transversales.
- [x] `presentation.php` enrichie avec une slide architecture selon la methode du prof.
- [x] `stack.php` enrichie avec front controller, router, `.htaccess`, MAMP/Apache et MVC adapte.
- [x] Tests effectues avec `php -S 127.0.0.1:8888 -t public public/router.php`.
- [x] Routes propres verifiees : `/`, `/recettes`, `/recette/veloute-de-potimarron`, `/connexion`, `/presentation`, `/stack`.
- [x] Compatibilite verifiee : `/recipes.php`, `/recipe.php?slug=veloute-de-potimarron`, `/presentation.php`, `/stack.php`.
- [x] Securite verifiee apres routing : CSP presente, acces admin sans session redirige, login admin OK.

## Phase 13 - Premier lot de fonctionnalites additionnelles du prof

- [x] Ajout de champs recettes : `category`, `status`, `published_at`, `view_count`.
- [x] Recherche publique serveur sur titre, description courte et ingredients avec PDO prepare/execute.
- [x] Filtres publics par categorie : entrees, plats, desserts, vegetarien.
- [x] Pagination publique sur `/recettes` avec 6 recettes par page.
- [x] Statuts admin : brouillon, publie, archive.
- [x] Front-office limite aux recettes `published`.
- [x] Formulaire admin recette enrichi avec categorie et statut.
- [x] Tests MySQL : import `database.sql`, verification categories/statuts, recherche `q=chocolat`, filtre `category=desserts`, pagination page 2.

## Phase 14 - Notes etoiles et commentaires moderes

- [x] Ajout table `recipe_ratings` avec note 1 a 5, empreinte visiteur et cle unique par recette/visiteur.
- [x] Ajout table `recipe_comments` avec statut `pending`, `approved`, `rejected`.
- [x] Affichage des etoiles sur les cartes recettes et la page detail.
- [x] Formulaire public de notation protege par CSRF.
- [x] Formulaire public de commentaire protege par CSRF, validation serveur, honeypot anti-spam simple et moderation obligatoire.
- [x] Page admin `/admin/comments/index.php` pour approuver, refuser ou supprimer les commentaires.
- [x] Dashboard admin enrichi avec compteur de commentaires en attente.
- [x] Tests MySQL/HTTP : note ajoutee, commentaire cree en attente, moderation admin approuvee, affichage public verifie.

## Phase 15 - Populaires, impression, a propos, preview, duplication, logs

- [x] Compteur `view_count` incrementé sur consultation d'une recette publiee.
- [x] Section accueil "Recettes populaires" triee par nombre de vues.
- [x] Bouton impression sur page recette + CSS `@media print`.
- [x] Page publique `/a-propos` ajoutee et routee par `public/router.php`.
- [x] Apercu admin avant publication : `/admin/recipes/preview.php`.
- [x] Duplication admin d'une recette en brouillon : `/admin/recipes/duplicate.php`.
- [x] Timeout session admin apres 30 minutes d'inactivite.
- [x] Table `security_logs` + journalisation login, commentaires, duplication et suppression recette.
- [x] Dashboard enrichi avec journal securite recent.
- [x] Tests : vue incremente `view_count`, page a propos OK, preview admin OK, duplication cree un brouillon, log securite cree.

## Phase 16 - Journal securite complet et nettoyage

- [x] Page admin `/admin/security-logs/index.php` ajoutee.
- [x] Filtres serveur par type d'evenement et recherche libre.
- [x] Pagination serveur du journal securite avec requetes preparees.
- [x] Lien "Journal" ajoute au menu admin.
- [x] Dashboard relie vers le journal complet.
- [x] Nettoyage manuel protege par CSRF des logs et tentatives login de plus de 90 jours.
- [x] Nettoyage journalise dans `security_logs` via `security_cleanup`.
- [x] Tests finaux : lint PHP, build Tailwind, navigation admin journal, PDF regenere, commit/push.

## Phase 17 - Durcissement production et export audit

- [x] Redirection HTTPS forcee si `APP_ENV=production` et requete non HTTPS.
- [x] Requetes POST non HTTPS refusees en production au lieu d'etre redirigees.
- [x] Hachage admin centralise dans `admin_password_hash()`.
- [x] Argon2id utilise pour les nouveaux mots de passe admin quand PHP le supporte.
- [x] Rehash automatique des anciens hashes admin apres login valide si l'algorithme courant est plus fort.
- [x] Export CSV du journal securite avec les filtres courants.
- [x] Tests finaux : Argon2id, HTTPS prod, export CSV, lint, build CSS, PDF, commit/push.

## Phase 11 — Refonte design AAA + UX premium + SEO/A11y (2026-05-06)

### Design system éditorial

- [x] Tokens élargis dans `tailwind.config.js` : palette parchment/ivory/ink/copper/saffron/olive/fog/embers, fontFamily double Fraunces+Inter+JetBrains Mono, ombres `soft-1..4` + `editorial` + `glow-*`, backgrounds décoratifs (grain, paper, warm-radial, gold-hairline), keyframes `fade-up`/`shimmer`/`pulse-warm`.
- [x] Refonte complète de `public/assets/css/input.css` : base éditoriale, composants AAA (boutons signature avec dégradés et lift, cartes recipe en lévitation, kickers à point doré, code-panel premium, header verre dépoli, footer dégradé sépia, admin shell verre dépoli sur ink-radial), utilities (text-balance, smallcaps, tabular, gold-line).
- [x] Création de `public/assets/img/textures/grain.svg` et `paper.svg` (SVG `<feTurbulence>` < 1 KB).
- [x] Helpers PHP : `<body>` public en `bg-parchment text-ink font-sans`, `nav_link` raffiné, footer enrichi avec logo signature en small-caps.
- [x] Mémoire `project_mijote_maison_design.md` mise à jour avec la grammaire complète.

### Sécurité & UX confirmations

- [x] Nouveau helper `current_admin_id()` dans `app/security/auth.php`.
- [x] Pattern `data-confirm="<message>"` sur tous les `<form>` admin sensibles.
- [x] Modale de confirmation custom (focus trap, Escape, Enter, accessible) construite par DOM API dans `public/assets/js/admin.js` (pas d'`innerHTML` dynamique).
- [x] Self-delete admin bloqué côté UI (badge « Vous » sur la ligne du compte courant) ET côté serveur.

### Toasts auto-dismiss

- [x] `render_flash()` produit un `.flash-stack` de toasts : slide-in 420 ms, auto-fermeture 4.5 s, bouton ✕, barre de progression, `aria-live="polite"`, `role="alert"`.
- [x] Fichier `public/assets/js/toasts.js` (auto-dismiss + Escape pour fermer le dernier).

### Pagination + recherche admin

- [x] Sur `/admin/recipes/index.php` et `/admin/admins/index.php` : barre de recherche live + pagination 10/page (100 % JS, dégrade gracefully si JS off).
- [x] Pattern réutilisable : `<table data-table="<key>" data-page-size="10">` + `<tr data-search="<haystack>">` + toolbar `[data-table-toolbar="<key>"]`.

### Mode présentateur

- [x] Toggle `🎙️ Mode présentateur` sur `/presentation.php` (notes orales cachées par défaut, visibles en mode ON).
- [x] Chronomètre auto-démarré au premier changement de slide.
- [x] Bouton plein écran (`requestFullscreen()`).
- [x] Bouton reset chrono.
- [x] Persistance dans `localStorage`.
- [x] Transitions fade entre slides (200 ms / 250 ms).
- [x] Focus management (tabindex="-1" + .focus() sur l'`<article>` actif).
- [x] `aria-live="polite"` sur le compteur de slides.

### Sécurité CSP

- [x] Nonce CSP par requête (`csp_nonce()` dans `app/security/headers.php`, génération via `random_bytes(16)`).
- [x] CSP étendue pour autoriser Google Fonts (`style-src 'self' https://fonts.googleapis.com`, `font-src 'self' data: https://fonts.gstatic.com`).
- [x] Aucun `<script>` inline sans nonce.

### SEO + a11y

- [x] Skip link `Aller au contenu` dans `public_header()` (visible au focus).
- [x] `public_header(string $title, ?array $og = null)` : signature étendue rétro-compatible.
- [x] Open Graph complet (`og:type/title/description/image/url/site_name`) sur toutes les pages publiques.
- [x] Twitter Card `summary_large_image`.
- [x] JSON-LD `Recipe` complet sur `/recipe.php` (name, image, description, ingredients[], instructions[], totalTime, recipeYield, author, recipeCategory) avec nonce CSP.
- [x] `<meta name="description">` dynamique par page.
- [x] `prefers-reduced-motion: reduce` honoré (transitions désactivées globalement).

### Configuration locale

- [x] Création de `.env` pointant vers MySQL local sur port 3307 (instance dédiée au projet déjà en place sur la machine).
