<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HeadersSecurityTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['HTTPS'], $_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    public function testRequestIsHttpsWithDirectHttpsFlag(): void
    {
        $_SERVER['HTTPS'] = 'on';

        self::assertTrue(request_is_https());
    }

    public function testRequestIsHttpsWithForwardedProto(): void
    {
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

        self::assertTrue(request_is_https());
    }

    public function testRequestIsNotHttpsByDefault(): void
    {
        $_SERVER['HTTPS'] = 'off';
        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);

        self::assertFalse(request_is_https());
    }
}
