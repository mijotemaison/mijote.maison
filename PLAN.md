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
- `admin/` : back-office protege.
- `app/config/` : configuration application et base de donnees.
- `app/helpers/` : helpers d'affichage, flash messages et chemins.
- `app/security/` : headers, sessions, authentification, CSRF, brute force, upload.
- `app/repositories/` : acces base de donnees via PDO.
- `app/validation/` : validation serveur.
- `docs/` : rapport de securite source et PDF.
- `storage/logs/` : espace de logs applicatifs.

## Grille d'evaluation transformee en checklist

- [ ] Architecture generale claire front-office / back-office / app : 3 pts.
- [ ] Page d'accueil avec recettes, navigation et aucune action sensible publique : 2 pts.
- [ ] Page recette complete et lisible avec donnees base : 2 pts.
- [ ] Page de connexion fonctionnelle avec erreurs propres et acces restreint : 2 pts.
- [ ] CRUD recettes complet : 3 pts.
- [ ] CRUD administrateurs complet : 2 pts.
- [ ] Authentification securisee, mots de passe haches, sessions protegees : 4 pts.
- [ ] Protection SQLi par PDO prepare/execute partout : 4 pts.
- [ ] Protection XSS par validation, echappement et absence de HTML utilisateur : 4 pts.
- [ ] CSP coherente : 2 pts.
- [ ] Protection CSRF sur formulaires sensibles : 4 pts.
- [ ] Protection brute force avec journalisation et blocage temporaire : 3 pts.
- [ ] Upload securise : MIME, extension, taille, nom aleatoire, pas d'execution : 3 pts.
- [ ] Qualite du code : lisibilite, organisation, commentaires utiles : 3 pts.
- [ ] Documentation securite claire avec extraits reels : 4 pts.

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
- [x] Liste complete des recettes sur `/recipes.php`.
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

- [ ] Initialiser/configurer Git si necessaire.
- [ ] Ajouter le remote GitHub.
- [ ] Commit final.
- [ ] Push vers la branche principale detectee.

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
- [x] Acces `/admin/dashboard.php` sans session redirige vers `/login.php`.
- [x] Tests MySQL reels executes sur instance temporaire isolee `127.0.0.1:3307`.
- [x] Import `database.sql` verifie : tables `admins`, `recipes`, `login_attempts`, admin initial et recettes seed.
- [x] Front-office verifie depuis MySQL : accueil, detail recette, presentation, CSP.
- [x] Authentification verifiee : mauvais login refuse, SQLi login sans effet, login valide, regeneration de session.
- [x] Back-office verifie : dashboard protege, CRUD recettes, CRUD administrateurs, suppression dernier admin refusee.
- [x] Protections verifiees : CSRF invalide refuse, XSS echappee, upload image valide accepte, upload `.php` et fichier trop lourd refuses, brute force bloquee apres 5 echecs.
- [x] Resultat suite MySQL : 29 tests passes / 29.
- [x] Conformite PDF front-office verifiee : accueil `/`, liste recettes `/recipes.php`, detail separe `/recipe.php`, page de connexion nommee explicitement.
