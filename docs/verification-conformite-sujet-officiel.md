# Verification de conformite au sujet officiel

Projet : **Mijote Maison - Secure Recipes GRETA 92**  
Date de verification : **26 mai 2026**  
Objectif : verifier que le projet repond aux exigences de base du sujet officiel et a la grille d'evaluation.

## Synthese

Le projet repond globalement aux exigences de base du sujet : front-office public, back-office admin, CRUD recettes, CRUD administrateurs, authentification, PDO, CSRF, XSS/CSP, brute force, upload securise, MySQL, Bootstrap, documentation et rapport securite.

## Constats verifies

| Critere | Etat | Verification |
|---|---|---|
| Architecture generale | Conforme | `public/index.php` est le point d'entree unique, AltoRouter centralise les routes publiques et admin, MVC present avec `src/Controller`, `src/Model`, `src/Repository`, `src/Vues`. |
| Stack technique | Conforme | PHP, HTML, CSS, JavaScript, Bootstrap local, MySQL et PDO sont utilises. Aucune dependance Tailwind active n'a ete detectee dans le code applicatif. |
| Base de donnees | Conforme | `database.sql` s'importe correctement dans une instance MySQL temporaire. |
| Tables minimales | Conforme | Tables `admins`, `recipes`, `login_attempts` presentes, avec tables complementaires `security_logs`, `recipe_ratings`, `recipe_comments`. |
| Donnees de demonstration | Conforme | Import SQL verifie avec 1 administrateur initial et 20 recettes publiees. |
| Front-office | Conforme | Accueil, liste recettes, detail recette, connexion, presentation, stack, conformite, pages legales. |
| Back-office | Conforme | Dashboard admin, CRUD recettes, CRUD administrateurs, commentaires, journal securite. |
| Authentification | Conforme | `password_verify`, hash admin, sessions securisees, `session_regenerate_id(true)`, protection `require_admin()`. |
| SQLi | Conforme | Acces SQL centralises dans les repositories PDO avec `prepare()` et `execute()`. |
| XSS / CSP | Conforme | Echappement centralise avec `e()`, CSP et headers securite centralises. |
| CSRF | Conforme | Tokens CSRF sur les formulaires sensibles et verification serveur. |
| Brute force | Conforme | Table `login_attempts`, blocage apres echecs repetes, journalisation. |
| Upload securise | Conforme | Extensions limitees, verification MIME, taille limitee, nom aleatoire, `.htaccess` anti-execution dans les uploads. |
| Documentation | Conforme | `README.md`, `CODEX.md`, `docs/rapport-securite.md`, PDF securite, pages `/presentation`, `/stack`, `/conformite`. |

## Tests executes

| Test | Resultat |
|---|---|
| `composer validate --strict` | OK |
| Lint PHP complet | OK |
| `npm run check-assets` | OK, Bootstrap local et `app.css` presents |
| Import MySQL temporaire | OK |
| `composer test` avec MySQL temporaire | OK, 12 tests / 25 assertions |
| Routes publiques principales | OK, HTTP 200 |
| `/admin/dashboard` sans session | OK, redirection 302 vers `/connexion` |
| Login admin local `admin@example.com` / `Admin123!` | OK, redirection vers dashboard |
| Brute force apres 5 echecs | OK, blocage declenche |
| Headers securite | OK, CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy presents |

## Routes verifiees

- `/`
- `/recettes`
- `/recettes?category=entrees`
- `/recettes?q=chocolat`
- `/recette/fondant-au-chocolat`
- `/connexion`
- `/presentation`
- `/stack`
- `/conformite`
- `/mentions-legales`
- `/politique-confidentialite`
- `/admin/dashboard`

## Points a surveiller

- Le dossier local peut contenir `.env` et `.DS_Store`, mais ils ne doivent pas etre ajoutes au depot Git.
- Les uploads sont stockes dans `public/uploads/recipes`, proteges par `.htaccess`. C'est acceptable avec Apache/MAMP, meme si le sujet indique que le stockage hors repertoire executable est preferable quand c'est possible.
- La conformite en production depend aussi de la base Railway : si Railway n'a pas les memes donnees que `database.sql`, l'affichage public peut differer.

## Conclusion

Le projet est en phase avec le sujet officiel de base et la grille des 40 points. Aucun correctif bloquant n'a ete identifie pendant cette verification.
