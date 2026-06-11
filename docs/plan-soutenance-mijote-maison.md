# Plan de soutenance - Mijote Maison

Objectif : répartir une soutenance de 30 minutes entre trois intervenants, avec 10 minutes par personne.

## Découpage retenu

| Intervenant | Pages de la présentation | Durée | Rôle |
|---|---:|---:|---|
| Intervenant 1 | Pages 1 à 6 | 10 min | Présenter le sujet, la stack, l'architecture et la partie publique. |
| Intervenant 2 | Pages 7 à 11 | 10 min | Présenter le back-office et les protections centrales. |
| Intervenant 3 | Pages 12 à 16 | 10 min | Présenter le durcissement sécurité, les tests, les livrables et conclure. |

## Partie 1 - Intervenant 1

Pages du support : 1 à 6.

Sujet PDF couvert :
- Page 1 : contexte général du projet final.
- Page 2 : site de recettes, objectif front-end/back-end, pages publiques attendues.
- Page 4 : contraintes techniques PHP, HTML, CSS, JavaScript, Bootstrap, MySQL.
- Page 6 : fonctionnalités front-office complémentaires présentes dans le projet.

Messages à faire passer :
- Le projet répond au besoin : un vrai site public de recettes avec une administration sécurisée.
- La stack respecte le sujet : PHP, MySQL, Bootstrap, JavaScript, PDO.
- L'architecture est organisée autour d'un point d'entrée unique, de routes propres et d'une séparation MVC.
- La partie publique répond aux attentes : accueil, liste, détail recette, recherche, filtres, pagination, notes, commentaires et impression.

Timing conseillé :
- Pages 1-2 : 2 min.
- Pages 3-4 : 2 min.
- Page 5 : 3 min.
- Page 6 : 3 min.

Transition vers l'intervenant 2 : "Maintenant que la partie publique et l'architecture sont posées, on passe à la partie réservée aux administrateurs et aux protections prioritaires."

## Partie 2 - Intervenant 2

Pages du support : 7 à 11.

Sujet PDF couvert :
- Page 3 : back-office réservé aux administrateurs, CRUD recettes, CRUD administrateurs.
- Page 3 : authentification, protection XSS, injection SQL, CSRF.
- Page 5 : critères d'évaluation liés au CRUD, à l'authentification, SQLi, XSS, CSP et CSRF.

Messages à faire passer :
- Le back-office est strictement réservé aux administrateurs connectés.
- Les actions sensibles passent par des formulaires protégés.
- Les mots de passe sont hashés et les sessions sont régénérées.
- Les accès SQL utilisent des requêtes préparées.
- Les affichages sont échappés et la CSP limite l'exécution de scripts.
- Le CSRF est vérifié avant les actions sensibles.

Timing conseillé :
- Page 7 : 2 min.
- Page 8 : 2 min.
- Page 9 : 2 min.
- Page 10 : 2 min.
- Page 11 : 2 min.

Transition vers l'intervenant 3 : "On a couvert les protections centrales. Il reste à montrer les mécanismes de durcissement, la validation, les tests et les livrables."

## Partie 3 - Intervenant 3

Pages du support : 12 à 16.

Sujet PDF couvert :
- Page 4 : brute force, upload sécurisé, livrables attendus.
- Page 5 : grille d'évaluation restante : force brute, upload, qualité du code, documentation sécurité.
- Page 7 : sécurité avancée : logs, timeout de session, headers HTTP, CSP avancée, protection des fichiers.

Messages à faire passer :
- Le login limite les essais répétés et journalise les tentatives.
- L'upload vérifie taille, extension, MIME et empêche l'exécution de fichiers dangereux.
- La validation serveur refuse les données incohérentes même si le navigateur est contourné.
- Les tests et documents prouvent que le projet est vérifiable.
- La conclusion doit relier le produit final au sujet officiel et à la grille des 40 points.

Timing conseillé :
- Page 12 : 2 min.
- Page 13 : 2 min.
- Page 14 : 2 min.
- Page 15 : 2 min.
- Page 16 : 2 min.

Conclusion à dire : "Le projet n'est pas seulement fonctionnel : il est structuré, sécurisé, documenté et chaque protection demandée est visible dans le code."

## Points à garder pour les questions

- La page `/conformite` peut servir de grille de justification face au jury.
- La page `/stack` peut servir à expliquer l'organisation technique.
- Le rapport de sécurité détaille les vulnérabilités étudiées et les protections mises en place.
- Les fonctionnalités bonus ne doivent pas remplacer les exigences principales : elles servent seulement à renforcer la démonstration.
