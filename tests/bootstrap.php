<?php

declare(strict_types=1);

$_SERVER['REMOTE_ADDR'] ??= '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] ??= 'phpunit';
$_SERVER['REQUEST_METHOD'] ??= 'GET';
$_SERVER['REQUEST_URI'] ??= '/';
$_SERVER['HTTP_HOST'] ??= 'localhost';

require_once dirname(__DIR__) . '/src/bootstrap.php';
