<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class InputValidationTest extends TestCase
{
    public function testAdminValidationRejectsWeakPassword(): void
    {
        $errors = validate_admin_input([
            'username' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'weak',
        ]);

        self::assertArrayHasKey('password', $errors);
    }

    public function testAdminValidationAcceptsStrongPassword(): void
    {
        $errors = validate_admin_input([
            'username' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'Admin123!',
        ]);

        self::assertSame([], $errors);
    }

    public function testRecipeValidationRejectsUnknownCategoryAndStatus(): void
    {
        $errors = validate_recipe_input([
            'title' => 'Recette test',
            'short_description' => 'Courte description',
            'description' => 'Description complete',
            'ingredients' => 'Farine',
            'preparation_steps' => 'Melanger',
            'category' => 'unknown',
            'status' => 'public',
        ]);

        self::assertArrayHasKey('category', $errors);
        self::assertArrayHasKey('status', $errors);
    }
}
