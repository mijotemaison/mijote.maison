<?php

declare(strict_types=1);

return [
    [
        'number' => 1,
        'speaker' => 'Intervenant 1',
        'title' => 'Sujet, architecture et partie publique',
        'slides' => 'Pages 1 à 6',
        'start' => 1,
        'end' => 6,
        'duration' => '10 min',
        'subject' => 'PDF pages 1 à 2, contraintes techniques page 4, compléments front-office page 6.',
        'objective' => 'Expliquer ce qui était demandé, la stack retenue, l’architecture MVC et la réponse côté visiteur.',
        'focus' => ['Contexte du sujet', 'Stack PHP, MySQL, Bootstrap, JavaScript', 'Architecture MVC', 'Accueil, liste, détail recette, recherche, filtres, pagination'],
    ],
    [
        'number' => 2,
        'speaker' => 'Intervenant 2',
        'title' => 'Back-office et protections centrales',
        'slides' => 'Pages 7 à 11',
        'start' => 7,
        'end' => 11,
        'duration' => '10 min',
        'subject' => 'PDF page 3 et grille page 5 : back-office, CRUD, authentification, SQLi, XSS, CSP et CSRF.',
        'objective' => 'Montrer comment l’administration est réservée aux admins et comment les failles prioritaires sont traitées.',
        'focus' => ['CRUD recettes et administrateurs', 'Connexion admin et sessions', 'Requêtes préparées PDO', 'Echappement XSS, CSP et tokens CSRF'],
    ],
    [
        'number' => 3,
        'speaker' => 'Intervenant 3',
        'title' => 'Durcissement, preuves et conclusion',
        'slides' => 'Pages 12 à 16',
        'start' => 12,
        'end' => 16,
        'duration' => '10 min',
        'subject' => 'PDF pages 4 à 7 : force brute, upload, validation, livrables, tests et fonctionnalités bonus.',
        'objective' => 'Prouver que le projet est testable, documenté et défendable face aux critères de sécurité restants.',
        'focus' => ['Brute force et journalisation', 'Upload sécurisé', 'Validation serveur', 'Tests, documentation et conclusion'],
    ],
];
