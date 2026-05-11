<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthSecurityTest extends TestCase
{
    public function testAdminPasswordHashVerifiesAndDoesNotNeedImmediateRehash(): void
    {
        $hash = admin_password_hash('Admin123!');

        self::assertTrue(password_verify('Admin123!', $hash));
        self::assertFalse(admin_password_needs_rehash($hash));
    }

    public function testLegacyBcryptHashNeedsRehashWhenArgon2idIsAvailable(): void
    {
        $legacyHash = password_hash('Admin123!', PASSWORD_BCRYPT);

        self::assertTrue(password_verify('Admin123!', $legacyHash));
        self::assertSame(defined('PASSWORD_ARGON2ID'), admin_password_needs_rehash($legacyHash));
    }

    public function testCsrfTokenGenerationAndVerification(): void
    {
        unset($_SESSION['csrf_token']);

        $token = generate_csrf_token();

        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $token);
        self::assertTrue(verify_csrf_token($token));
        self::assertFalse(verify_csrf_token('invalid-token'));
    }
}
