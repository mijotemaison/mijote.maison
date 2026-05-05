# CODEX.md - Guide de travail Codex

## Role du projet

Secure Recipes GRETA 92 est le projet final d'une formation GRETA 92 cybersécurité. Son nom public est Mijoté Maison. Il doit demontrer la capacite a construire une application web PHP/MySQL complete, lisible, documentee et securisee.

## Resume du sujet

Le site propose des recettes de cuisine au public et un back-office reserve aux administrateurs. Le public consulte uniquement. Les administrateurs authentifies gerent les recettes et les comptes administrateurs. Les protections XSS, SQLi, CSRF, brute force, upload securise, sessions securisees et CSP doivent etre visibles dans le code et expliquees dans la documentation.

## Stack

- PHP natif structure.
- MySQL.
- PDO obligatoire.
- HTML.
- JavaScript vanilla.
- Tailwind CSS en remplacement de Bootstrap.
- Markdown/HTML/PDF pour la documentation.

## Architecture

- `public/` contient les pages publiques et les assets.
- `admin/` contient les pages reservees aux administrateurs.
- `app/config/` contient la configuration.
- `app/helpers/` contient les fonctions transverses.
- `app/security/` contient les protections.
- `app/repositories/` contient les requetes PDO.
- `app/validation/` contient les validations serveur.
- `docs/` contient le rapport.

## Conventions de code

- PHP clair, fonctions courtes, noms explicites.
- Pas de framework lourd.
- Pas de Bootstrap.
- Pas de secret en dur.
- Toujours echapper les donnees affichees avec `e()`.
- Toujours utiliser PDO `prepare()` puis `execute()`.
- Toujours verifier le CSRF avant une action sensible.
- Les suppressions passent par POST uniquement.
- Les mots de passe ne sont jamais affiches et jamais stockes en clair.

## Regles de securite

- `password_hash()` pour creer un mot de passe.
- `password_verify()` pour verifier un mot de passe.
- `session_regenerate_id(true)` apres connexion.
- Cookies de session `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS.
- Headers de securite centralises dans `app/security/headers.php`.
- CSP stricte compatible avec Tailwind compile localement.
- Upload limite aux images `jpg`, `jpeg`, `png`, `webp`.
- Verification MIME avec `finfo_file()`.
- Nom de fichier aleatoire via `random_bytes()`.
- Protection brute force via table `login_attempts`.

## Installation locale

```bash
npm install
npm run build-css
mysql -u root -p < database.sql
php -S localhost:8000 -t public
```

## Variables d'environnement

Copier `.env.example` vers `.env` si l'environnement local le permet, ou exporter les variables :

```bash
DB_HOST=127.0.0.1
DB_NAME=secure_recipes_greta92
DB_USER=root
DB_PASSWORD=
APP_ENV=local
APP_URL=http://localhost:8000
```

## Commandes Tailwind

```bash
npm run build-css
npm run watch-css
```

## Checklist de tests

- Accueil accessible.
- Liste recettes depuis MySQL.
- Detail recette accessible.
- Login admin valide.
- Login invalide refuse.
- Acces admin sans session redirige.
- CRUD recettes.
- CRUD administrateurs.
- Suppression dernier admin refusee.
- Upload image valide accepte.
- Upload `.php` refuse.
- CSRF invalide refuse.
- XSS affiche sous forme de texte.
- SQLi login sans effet.
- Brute force declenche le blocage.
- CSP presente.
- Presentation fonctionnelle.

## Checklist avant push

- `npm run build-css`.
- `find . -name "*.php" -print0 | xargs -0 -n1 php -l`.
- Verifier `git status`.
- Ne pas commiter `.env`.
- Completer `PLAN.md`.
- Generer le PDF du rapport.
- Commit clair.

## Regles Git

- Ne pas ecraser les changements utilisateur.
- Commiter uniquement un projet coherent.
- Detecter la branche principale avant push.
- Message final attendu : `Final GRETA cybersecurity recipes project`.
