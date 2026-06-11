<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "Ce script doit etre lance en ligne de commande.\n";
    exit(1);
}

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/app.php';

$sqlFile = BASE_PATH . '/database.sql';

if (!is_file($sqlFile)) {
    fwrite(STDERR, "database.sql introuvable, bootstrap ignore.\n");
    exit(0);
}

if (env_value('SKIP_DB_BOOTSTRAP', '0') === '1') {
    echo "Bootstrap base ignore par SKIP_DB_BOOTSTRAP=1.\n";
    exit(0);
}

$host = (string) env_value('DB_HOST', env_value('MYSQLHOST', '127.0.0.1'));
$port = (string) env_value('DB_PORT', env_value('MYSQLPORT', '3306'));
$name = (string) env_value('DB_NAME', env_value('MYSQLDATABASE', 'secure_recipes_greta92'));
$user = (string) env_value('DB_USER', env_value('MYSQLUSER', 'root'));
$password = (string) env_value('DB_PASSWORD', env_value('MYSQLPASSWORD', ''));

try {
    $dbName = quote_identifier($name);
    $serverPdo = connect_with_retry("mysql:host={$host};port={$port};charset=utf8mb4", $user, $password);
    $serverPdo->exec("CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo = connect_with_retry("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $password);
    if (database_has_recipes($pdo, $name)) {
        echo "Base deja initialisee, import SQL ignore.\n";
        exit(0);
    }

    import_sql_file($pdo, $sqlFile, $dbName);
    echo "Base initialisee depuis database.sql.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, 'Bootstrap base impossible: ' . $exception->getMessage() . "\n");
    exit(1);
}

function connect_with_retry(string $dsn, string $user, string $password): PDO
{
    $attempts = 20;
    $lastException = null;

    for ($attempt = 1; $attempt <= $attempts; $attempt++) {
        try {
            return new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (Throwable $exception) {
            $lastException = $exception;
            if ($attempt < $attempts) {
                sleep(2);
            }
        }
    }

    throw $lastException ?? new RuntimeException('Connexion MySQL impossible.');
}

function quote_identifier(string $identifier): string
{
    if (!preg_match('/^[A-Za-z0-9_]+$/', $identifier)) {
        throw new InvalidArgumentException('Nom de base invalide.');
    }

    return '`' . $identifier . '`';
}

function database_has_recipes(PDO $pdo, string $databaseName): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.tables
         WHERE table_schema = :database_name AND table_name = "recipes"'
    );
    $stmt->execute(['database_name' => $databaseName]);

    if ((int) $stmt->fetchColumn() === 0) {
        return false;
    }

    $stmt = $pdo->query('SELECT COUNT(*) FROM recipes');

    return (int) $stmt->fetchColumn() > 0;
}

function import_sql_file(PDO $pdo, string $sqlFile, string $dbName): void
{
    $sql = (string) file_get_contents($sqlFile);
    $sql = preg_replace('/CREATE\s+DATABASE\s+IF\s+NOT\s+EXISTS\s+`?secure_recipes_greta92`?.*?;\s*/is', '', $sql) ?? $sql;
    $sql = preg_replace('/USE\s+`?secure_recipes_greta92`?\s*;\s*/i', '', $sql) ?? $sql;

    $pdo->exec("USE {$dbName}");

    foreach (split_sql_statements($sql) as $statement) {
        $trimmed = trim($statement);
        if ($trimmed === '' || str_starts_with($trimmed, '--')) {
            continue;
        }
        $pdo->exec($trimmed);
    }
}

function split_sql_statements(string $sql): array
{
    $statements = [];
    $buffer = '';
    $quote = null;
    $length = strlen($sql);

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $sql[$i + 1] ?? '';

        if ($quote !== null) {
            $buffer .= $char;
            if ($char === '\\' && $next !== '') {
                $buffer .= $next;
                $i++;
                continue;
            }
            if ($char === $quote) {
                $quote = null;
            }
            continue;
        }

        if ($char === '"' || $char === "'") {
            $quote = $char;
            $buffer .= $char;
            continue;
        }

        if ($char === ';') {
            $statements[] = $buffer;
            $buffer = '';
            continue;
        }

        $buffer .= $char;
    }

    if (trim($buffer) !== '') {
        $statements[] = $buffer;
    }

    return $statements;
}
