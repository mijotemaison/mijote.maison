# SOUL.md - Identite du projet

## Vision

Secure Recipes GRETA 92 presente une application simple en apparence, un site de recettes de cuisine, traitee avec le serieux d'une application exposee a Internet. Le projet montre qu'une fonctionnalite classique peut devenir un support concret pour expliquer la securite applicative.

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

Le projet combine cuisine et cybersécurité avec un vocabulaire professionnel : recettes protegees, back-office verrouille, controles serveur, traces de connexion, hygiene des entrees. L'identite doit rester sobre, credible et presentable devant un jury.

## Identite visuelle

- Theme sombre premium.
- Couleurs principales : bleu nuit, cyan, violet, gris fonce.
- Cartes nettes, contrastes lisibles, badges securite.
- Dashboard admin dense mais clair.
- Formulaires propres et messages d'erreur visibles.
- Experience responsive sur mobile et desktop.

## Experience utilisateur

Le public doit pouvoir parcourir les recettes sans friction. Les recettes doivent etre lisibles, illustrees, avec une navigation simple. Aucune action sensible ne doit apparaitre dans la zone publique.

## Experience admin

L'administrateur doit acceder rapidement aux indicateurs, aux recettes, aux comptes admins et aux actions CRUD. Les erreurs doivent etre explicites sans divulguer d'information sensible.

## Message au jury

Ce projet montre une application complete, structurée et securisee. Chaque protection importante est visible dans le code, documentee avec des extraits reels et reliee a une menace concrete.
