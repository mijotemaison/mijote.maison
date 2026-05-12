<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_RECIPE_DIR', PUBLIC_PATH . '/uploads/recipes');

if (is_file(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/helpers/flash.php';
require_once BASE_PATH . '/app/repositories/SecurityLogRepository.php';
require_once BASE_PATH . '/app/security/headers.php';
require_once BASE_PATH . '/app/security/auth.php';
require_once BASE_PATH . '/app/security/csrf.php';

enforce_https_in_production();
start_secure_session();
apply_security_headers();
