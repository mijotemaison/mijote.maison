<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/bootstrap.php';

$recipes = [
    [
        'title' => 'Couscous aux legumes',
        'slug' => 'couscous-aux-legumes',
        'short_description' => 'Un plat vegetarien genereux, parfume et colore pour les grandes tables.',
        'description' => 'Un couscous aux legumes fondants, pois chiches et semoule legere. Une recette conviviale qui apporte du soleil au repas familial.',
        'ingredients' => "350 g de semoule\n3 carottes\n2 courgettes\n2 navets\n250 g de pois chiches\n1 oignon\nBouillon de legumes\nRas el hanout\nCoriandre",
        'preparation_steps' => "Faire revenir l oignon avec les epices.\nAjouter les legumes et le bouillon puis mijoter 35 minutes.\nPreparer la semoule a part.\nServir les legumes sur la semoule avec coriandre fraiche.",
        'image_path' => 'assets/img/recipes/couscous-legumes.webp',
        'category' => 'vegetarien',
        'view_count' => 88,
        'rating' => 4,
        'comment_author' => 'Claire',
        'comment_content' => 'Recette tres claire, parfaite pour un repas en famille.',
    ],
    [
        'title' => 'Crepes sucrees',
        'slug' => 'crepes-sucrees',
        'short_description' => 'Des crepes fines et moelleuses pour le gouter ou un dessert rapide.',
        'description' => 'Une pate a crepes simple, doree a la poele et servie avec sucre, miel, chocolat ou fruits frais selon les envies.',
        'ingredients' => "250 g de farine\n3 oeufs\n50 cl de lait\n30 g de beurre fondu\n1 cuillere de sucre\n1 pincee de sel\nVanille",
        'preparation_steps' => "Melanger farine, sucre et sel.\nAjouter les oeufs puis le lait progressivement.\nIncorporer le beurre fondu.\nLaisser reposer puis cuire les crepes dans une poele chaude.",
        'image_path' => 'assets/img/recipes/crepes-sucrees.webp',
        'category' => 'desserts',
        'view_count' => 74,
        'rating' => 5,
        'comment_author' => 'Claire',
        'comment_content' => 'Recette tres claire, parfaite pour un repas en famille.',
    ],
    [
        'title' => 'Gaufres maison',
        'slug' => 'gaufres-maison',
        'short_description' => 'Des gaufres dorees, croustillantes dehors et moelleuses dedans.',
        'description' => 'Une recette de gaufres maison parfaite pour un brunch ou un gouter gourmand, avec fruits rouges, chantilly ou sucre glace.',
        'ingredients' => "250 g de farine\n2 oeufs\n40 g de sucre\n50 cl de lait\n80 g de beurre fondu\n1 sachet de levure\n1 pincee de sel",
        'preparation_steps' => "Melanger les ingredients secs.\nAjouter les oeufs, le lait puis le beurre fondu.\nLaisser reposer 20 minutes.\nCuire dans un gaufrier bien chaud jusqu a coloration.",
        'image_path' => 'assets/img/recipes/gaufres-maison.webp',
        'category' => 'desserts',
        'view_count' => 69,
        'rating' => 5,
        'comment_author' => null,
        'comment_content' => null,
    ],
    [
        'title' => 'Lasagnes bolognaise',
        'slug' => 'lasagnes-bolognaise',
        'short_description' => 'Un grand classique familial avec sauce bolognaise et fromage gratine.',
        'description' => 'Des couches de pates, sauce bolognaise maison, bechamel et fromage gratine au four. Un plat reconfortant et facile a partager.',
        'ingredients' => "12 feuilles de lasagnes\n500 g de boeuf hache\n700 g de coulis de tomate\n1 oignon\n2 carottes\n50 cl de bechamel\nParmesan\nMozzarella",
        'preparation_steps' => "Preparer la sauce bolognaise avec viande, legumes et tomate.\nMonter les couches de pates, sauce et bechamel.\nAjouter les fromages.\nCuire 40 minutes a 180 degres.",
        'image_path' => 'assets/img/recipes/lasagnes-bolognaise.webp',
        'category' => 'plats',
        'view_count' => 102,
        'rating' => 5,
        'comment_author' => 'Nadia',
        'comment_content' => 'J ai suivi les etapes sans difficulte, le resultat etait vraiment gourmand.',
    ],
    [
        'title' => 'Quiche lorraine maison',
        'slug' => 'quiche-lorraine-maison',
        'short_description' => 'Une quiche doree et fondante, ideale avec une salade verte.',
        'description' => 'Une pate croustillante garnie de lardons, oeufs, creme et fromage. Une recette simple pour un repas du soir ou un buffet.',
        'ingredients' => "1 pate brisee\n200 g de lardons\n3 oeufs\n25 cl de creme\n10 cl de lait\n80 g de fromage rape\nMuscade\nPoivre",
        'preparation_steps' => "Precuire legerement la pate.\nFaire dorer les lardons.\nMelanger oeufs, creme, lait et assaisonnement.\nVerser sur la pate et cuire 35 minutes a 180 degres.",
        'image_path' => 'assets/img/recipes/quiche-lorraine-maison.webp',
        'category' => 'plats',
        'view_count' => 81,
        'rating' => 5,
        'comment_author' => 'Claire',
        'comment_content' => 'Recette tres claire, parfaite pour un repas en famille.',
    ],
    [
        'title' => 'Ratatouille provencale',
        'slug' => 'ratatouille-provencale',
        'short_description' => 'Des legumes du soleil mijotes doucement avec herbes et huile d olive.',
        'description' => 'Une ratatouille familiale avec aubergines, courgettes, tomates et poivrons. Elle se sert chaude, tiede ou froide selon la saison.',
        'ingredients' => "2 aubergines\n3 courgettes\n4 tomates\n2 poivrons\n1 oignon\n2 gousses d ail\nHuile d olive\nThym\nBasilic",
        'preparation_steps' => "Couper les legumes en morceaux reguliers.\nFaire revenir chaque legume puis les rassembler.\nAjouter tomates, ail et herbes.\nMijoter 35 minutes a feu doux.",
        'image_path' => 'assets/img/recipes/ratatouille-provencale.webp',
        'category' => 'vegetarien',
        'view_count' => 77,
        'rating' => 4,
        'comment_author' => null,
        'comment_content' => null,
    ],
    [
        'title' => 'Salade nicoise',
        'slug' => 'salade-nicoise',
        'short_description' => 'Une salade complete, fraiche et coloree pour les beaux jours.',
        'description' => 'Tomates, haricots verts, oeufs, thon, olives et pommes de terre composent une salade genereuse et equilibree.',
        'ingredients' => "4 tomates\n300 g de haricots verts\n4 oeufs\n250 g de thon\nOlives noires\nPommes de terre\nHuile d olive\nCitron",
        'preparation_steps' => "Cuire les oeufs, haricots et pommes de terre.\nCouper les tomates.\nDisposer tous les ingredients dans un grand plat.\nAssaisonner avec huile d olive et citron.",
        'image_path' => 'assets/img/recipes/salade-nicoise.webp',
        'category' => 'entrees',
        'view_count' => 66,
        'rating' => 4,
        'comment_author' => null,
        'comment_content' => null,
    ],
    [
        'title' => 'Soupe lentilles corail',
        'slug' => 'soupe-lentilles-corail',
        'short_description' => 'Une soupe veloutee, rapide et pleine de douceur.',
        'description' => 'Des lentilles corail cuites avec carottes, epices douces et bouillon. Une soupe simple, nourrissante et tres pratique en semaine.',
        'ingredients' => "250 g de lentilles corail\n2 carottes\n1 oignon\n1 litre de bouillon\nCumin\nCurcuma\nHuile d olive\nCoriandre",
        'preparation_steps' => "Rincer les lentilles.\nFaire revenir oignon, carottes et epices.\nAjouter bouillon et lentilles puis cuire 20 minutes.\nMixer et servir avec coriandre.",
        'image_path' => 'assets/img/recipes/soupe-lentilles-corail.webp',
        'category' => 'entrees',
        'view_count' => 58,
        'rating' => 4,
        'comment_author' => null,
        'comment_content' => null,
    ],
    [
        'title' => 'Tajine poulet olives citron',
        'slug' => 'tajine-poulet-olives-citron',
        'short_description' => 'Un plat mijote parfume avec olives vertes, citron et coriandre.',
        'description' => 'Du poulet tendre cuit lentement avec epices, olives et citron. Un plat chaleureux qui se partage directement a table.',
        'ingredients' => "4 cuisses de poulet\n200 g d olives vertes\n1 citron confit\n2 oignons\nAil\nCurcuma\nGingembre\nCoriandre\nHuile d olive",
        'preparation_steps' => "Faire revenir oignons, ail et epices.\nAjouter le poulet et faire dorer.\nCouvrir et mijoter 40 minutes.\nAjouter olives et citron en fin de cuisson.",
        'image_path' => 'assets/img/recipes/tajine-poulet-olives-citron.webp',
        'category' => 'plats',
        'view_count' => 93,
        'rating' => 5,
        'comment_author' => 'Nadia',
        'comment_content' => 'J ai suivi les etapes sans difficulte, le resultat etait vraiment gourmand.',
    ],
    [
        'title' => 'Tiramisu classique',
        'slug' => 'tiramisu-classique',
        'short_description' => 'Un dessert italien cremeux au cafe et cacao.',
        'description' => 'Un tiramisu traditionnel avec mascarpone, biscuits imbibes de cafe et cacao. Il se prepare a l avance pour un resultat bien fondant.',
        'ingredients' => "250 g de mascarpone\n3 oeufs\n80 g de sucre\nBiscuits cuillere\nCafe fort\nCacao non sucre\n1 pincee de sel",
        'preparation_steps' => "Separer les blancs des jaunes.\nFouetter jaunes et sucre puis ajouter mascarpone.\nIncorporer les blancs montes.\nAlterner biscuits imbibes et creme puis reserver au frais.",
        'image_path' => 'assets/img/recipes/tiramisu-classique.webp',
        'category' => 'desserts',
        'view_count' => 87,
        'rating' => 5,
        'comment_author' => 'Nadia',
        'comment_content' => 'J ai suivi les etapes sans difficulte, le resultat etait vraiment gourmand.',
    ],
];

$pdo = db();
$pdo->beginTransaction();

try {
    $recipeInsert = $pdo->prepare(
        "INSERT INTO recipes
            (title, slug, short_description, description, ingredients, preparation_steps, image_path, category, status, published_at, view_count, created_at, updated_at)
         VALUES
            (:title, :slug, :short_description, :description, :ingredients, :preparation_steps, :image_path, :category, 'published', NOW(), :view_count, NOW(), NOW())
         ON DUPLICATE KEY UPDATE slug = slug"
    );

    $findRecipe = $pdo->prepare('SELECT id FROM recipes WHERE slug = :slug LIMIT 1');
    $ratingInsert = $pdo->prepare(
        'INSERT INTO recipe_ratings (recipe_id, rating, voter_hash, created_at, updated_at)
         VALUES (:recipe_id, :rating, SHA2(CONCAT(:slug, "-additional-seed"), 256), NOW(), NOW())
         ON DUPLICATE KEY UPDATE rating = rating'
    );
    $commentExists = $pdo->prepare(
        'SELECT COUNT(*) FROM recipe_comments WHERE recipe_id = :recipe_id AND visitor_hash = SHA2(CONCAT(:slug, "-additional-comment"), 256)'
    );
    $commentInsert = $pdo->prepare(
        'INSERT INTO recipe_comments (recipe_id, author_name, content, status, visitor_hash, created_at, updated_at)
         VALUES (:recipe_id, :author_name, :content, "approved", SHA2(CONCAT(:slug, "-additional-comment"), 256), NOW(), NOW())'
    );

    foreach ($recipes as $recipe) {
        $recipeInsert->execute([
            'title' => $recipe['title'],
            'slug' => $recipe['slug'],
            'short_description' => $recipe['short_description'],
            'description' => $recipe['description'],
            'ingredients' => $recipe['ingredients'],
            'preparation_steps' => $recipe['preparation_steps'],
            'image_path' => $recipe['image_path'],
            'category' => $recipe['category'],
            'view_count' => $recipe['view_count'],
        ]);

        $findRecipe->execute(['slug' => $recipe['slug']]);
        $recipeId = (int) $findRecipe->fetchColumn();
        if ($recipeId <= 0) {
            continue;
        }

        $ratingInsert->execute([
            'recipe_id' => $recipeId,
            'rating' => $recipe['rating'],
            'slug' => $recipe['slug'],
        ]);

        if ($recipe['comment_author'] && $recipe['comment_content']) {
            $commentExists->execute([
                'recipe_id' => $recipeId,
                'slug' => $recipe['slug'],
            ]);

            if ((int) $commentExists->fetchColumn() === 0) {
                $commentInsert->execute([
                    'recipe_id' => $recipeId,
                    'author_name' => $recipe['comment_author'],
                    'content' => $recipe['comment_content'],
                    'slug' => $recipe['slug'],
                ]);
            }
        }
    }

    $pdo->commit();
} catch (Throwable $exception) {
    $pdo->rollBack();
    fwrite(STDERR, 'Seed impossible : ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}

$placeholders = implode(',', array_fill(0, count($recipes), '?'));
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM recipes WHERE slug IN ({$placeholders})");
$countStmt->execute(array_column($recipes, 'slug'));

echo 'Recettes supplementaires presentes : ' . (int) $countStmt->fetchColumn() . '/' . count($recipes) . PHP_EOL;
