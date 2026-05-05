# SOUL.md - Identite du projet

## Vision

Mijoté & Protégé presente une application simple en apparence, un site de recettes de cuisine chaleureux et familial, traitee avec le serieux d'une application exposee a Internet. Le projet montre qu'une fonctionnalite classique peut devenir un support concret pour expliquer la securite applicative.

## Objectif pedagogique

Le but n'est pas seulement de livrer des pages PHP fonctionnelles. Le projet doit prouver que les vulnérabilités web principales sont comprises, que les protections sont implementees au bon endroit, et que le code reste lisible pour un jury ou un formateur.

## Securite centrale

La securite est integree dans la structure :

- Repositories PDO pour reduire le risque SQLi.
- Helpers d'echappement pour limiter XSS.
- Tokens CSRF pour les actions sensibles.
- Session admin protegee.
- Journalisation et blocage brute force.
- Upload image strictement controle.
- Headers de securite et CSP.

## Positionnement cybersécurité

Le projet combine cuisine et cybersécurité avec une identite plus grand public : recettes maison, table conviviale, visuels appetissants, et protections discrètes. Le back-office conserve un vocabulaire professionnel : acces verrouille, controles serveur, traces de connexion, hygiene des entrees.

## Identite visuelle

- Nom public : Mijoté & Protégé.
- Logo : marmite, vapeur et bouclier/cadenas.
- Couleurs publiques : creme, tomate, vert herbes, ambre doux.
- Cartes recettes gourmandes avec grandes photos.
- Dashboard admin dense mais clair.
- Formulaires propres et messages d'erreur visibles.
- Experience responsive sur mobile et desktop.

## Experience utilisateur

Le public doit avoir l'impression d'etre sur un vrai site de recettes : accueil chaleureux, recherche visuelle, categories, photos appetissantes et fiches recettes tres lisibles. Aucune action sensible ne doit apparaitre dans la zone publique.

## Experience admin

L'administrateur doit acceder rapidement aux indicateurs, aux recettes, aux comptes admins et aux actions CRUD. Les erreurs doivent etre explicites sans divulguer d'information sensible.

## Message au jury

Ce projet montre une application complete, structurée et securisee. Chaque protection importante est visible dans le code, documentee avec des extraits reels et reliee a une menace concrete.
