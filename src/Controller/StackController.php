<?php

declare(strict_types=1);

namespace App\Controller;

final class StackController extends AbstractController
{
    public function index(): void
    {
        $stackItems = [
            ['PHP natif', 'Construit les pages, traite les formulaires, démarre les sessions et appelle les protections.'],
            ['MySQL', 'Stocke les administrateurs, recettes, catégories, notes, commentaires, vues, logs et tentatives de connexion.'],
            ['PDO', 'Relie PHP et MySQL avec des requêtes préparées pour éviter les injections SQL.'],
            ['HTML', 'Structure les pages publiques, les formulaires et les tableaux du back-office.'],
            ['Bootstrap', 'Apporte la grille responsive, les composants UI, les cartes, formulaires, tableaux, alertes et boutons.'],
            ['CSS applicatif', 'Personnalise Bootstrap pour obtenir une identité claire, premium et adaptée à un site de recettes.'],
            ['JavaScript vanilla', 'Ajoute la recherche, les filtres, le carrousel, les confirmations admin et la copie des extraits de code.'],
            ['Sessions PHP', 'Gardent l’état de connexion admin avec cookies HttpOnly, SameSite et Secure si HTTPS.'],
            ['Front controller', 'public/index.php centralise les URLs propres avec AltoRouter.'],
            ['AltoRouter', 'Associe une URL et une méthode HTTP à une méthode de contrôleur, comme dans la logique du cours.'],
            ['Apache / .htaccess', 'Sous MAMP, XAMPP ou LAMP, redirige les URLs propres vers public/index.php quand le fichier demandé n’existe pas.'],
            ['MAMP / XAMPP / LAMP', 'Environnements locaux cités dans le cours : MAMP sur Mac, XAMPP/WAMP/Laragon sur Windows, LAMP sur Linux.'],
            ['Docker local', 'Permet de lancer un environnement reproductible avec PHP et MySQL sans dépendre de MAMP.'],
            ['src/Controller', 'Prépare les données et choisit la vue à afficher.'],
            ['src/Model', 'Fournit des modèles métier simples qui appellent les repositories PDO existants.'],
            ['src/Vues', 'Contient les templates PHP qui affichent le HTML reçu du contrôleur.'],
            ['src/Utils/Security', 'Regroupe CSRF, authentification, brute force, upload sécurisé et headers HTTP.'],
        ];

        $responsibilities = [
            ['Front-office', 'Pages publiques pour consulter les recettes : accueil, liste, détail, impression et présentation.'],
            ['Back-office', 'Zone admin protégée pour gérer recettes, administrateurs, commentaires et journal sécurité.'],
            ['Router', 'public/index.php utilise AltoRouter pour envoyer chaque URL vers un contrôleur.'],
            ['Base de données', 'Tables admins, recipes, recipe_ratings, recipe_comments, security_logs et login_attempts.'],
            ['Audit', 'La table security_logs garde les connexions et actions sensibles; la page admin permet filtrage, export CSV et nettoyage.'],
            ['Repositories', 'Classes PHP qui exécutent les requêtes SQL préparées avec PDO.'],
            ['Validation', 'Contrôles serveur sur les champs recettes et administrateurs avant écriture en base.'],
            ['Sécurité', 'Protection XSS, SQLi, CSRF, brute force, sessions et upload image.'],
            ['Tests', 'PHPUnit vérifie les validations, protections, repositories de logs et nettoyage automatique.'],
            ['Assets', 'Images WebP, logo SVG, Bootstrap local, CSS applicatif et JavaScript local.'],
            ['Documentation', 'README, CODEX, rapport sécurité, présentation et conformité au sujet officiel.'],
            ['Améliorations V2', 'Validation email admin avec Mailtrap/Symfony Mailer et protections anti-DDoS via proxy/WAF.'],
        ];

        $this->renderPublic('Explication de la stack', 'stack', compact('stackItems', 'responsibilities'));
    }
}
