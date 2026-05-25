<?php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env_value('DB_HOST', env_value('MYSQLHOST', '127.0.0.1'));
    $port = env_value('DB_PORT', env_value('MYSQLPORT', '3306'));
    $name = env_value('DB_NAME', env_value('MYSQLDATABASE', 'secure_recipes_greta92'));
    $user = env_value('DB_USER', env_value('MYSQLUSER', 'root'));
    $password = env_value('DB_PASSWORD', env_value('MYSQLPASSWORD', ''));

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo = new PDO($dsn, (string) $user, (string) $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
