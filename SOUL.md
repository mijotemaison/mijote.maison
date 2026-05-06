# SOUL.md - Identite du projet

## Vision

Mijoté Maison presente une application simple en apparence, un site de recettes de cuisine chaleureux et familial, traitee avec le serieux d'une application exposee a Internet. Le projet montre qu'une fonctionnalite classique peut devenir un support concret pour expliquer la securite applicative.

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

## Identité visuelle

- Nom public : Mijoté Maison.
- Logo : marmite, vapeur et cœur végétal.
- **Direction artistique éditoriale premium** (référence : *Bon Appétit*, *NYT Cooking*, *Eleven Madison Park*).
- **Palette élargie** : `parchment` (fond global chaud), `ivory` (cartes), `ink` (texte, jamais pur noir), `tomato` + `copper` (accents principaux), `saffron` (filets dorés), `herb` + `olive` (accents végétaux), `fog` (sépia clair).
- **Typographie double** : `Fraunces` (display, italique expressif) pour les titres ; `Inter` (humaniste UI) pour le corps ; `JetBrains Mono` pour les extraits de code.
- **Profondeur** : ombres multi-couches (`soft-1` à `soft-4`, `editorial`), grain papier subtil (4–6 % d'opacité via SVG `<feTurbulence>`), filets dorés horizontaux et verticaux.
- **Header** sticky en verre dépoli (`backdrop-blur-xl`), filet doré en bas, lien actif souligné par un dégradé tomato → saffron.
- **Cartes recettes** en lévitation au hover (-6 px translate + ombre `editorial` + saturation image +8 %).
- **Boutons signature** : dégradé `tomato → copper` avec ombre colorée et lift au hover.
- **Dashboard admin** : fond `ink` avec radial chaud désaturé, sidebar verre dépoli, cartes `panel-card` avec halo subtil.
- **Notes orales de présentation** étiquetées « En quelques mots » (libellé discret, ne révèle pas le rôle de prompteur), masquées par défaut hors mode présentateur.
- Formulaires propres et messages d'erreur visibles via toasts auto-dismiss.
- Expérience responsive sur mobile et desktop.
- **Accessibilité** : skip link, focus visible, `prefers-reduced-motion`, contrastes AA.

## Experience utilisateur

Le public doit avoir l'impression d'etre sur un vrai site de recettes : accueil chaleureux, recherche visuelle, categories, photos appetissantes et fiches recettes tres lisibles. Aucune action sensible ne doit apparaitre dans la zone publique.

## Experience admin

L'administrateur doit acceder rapidement aux indicateurs, aux recettes, aux comptes admins et aux actions CRUD. Les erreurs doivent etre explicites sans divulguer d'information sensible.

## Message au jury

Ce projet montre une application complete, structurée et securisee. Chaque protection importante est visible dans le code, documentee avec des extraits reels et reliee a une menace concrete.
