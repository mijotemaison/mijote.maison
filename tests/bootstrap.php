<?php

declare(strict_types=1);

$_SERVER['REMOTE_ADDR'] ??= '127.0.0.1';
$_SERVER['HTTP_USER_AGENT'] ??= 'phpunit';
$_SERVER['REQUEST_METHOD'] ??= 'GET';
$_SERVER['REQUEST_URI'] ??= '/';
$_SERVER['HTTP_HOST'] ??= 'localhost';

require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';
require_once BASE_PATH . '/app/repositories/LoginAttemptRepository.php';
require_once BASE_PATH . '/app/repositories/SecurityLogRepository.php';
require_once BASE_PATH . '/app/validation/admin_validation.php';
require_once BASE_PATH . '/app/validation/recipe_validation.php';
