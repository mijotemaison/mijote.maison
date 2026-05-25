<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_RECIPE_DIR', PUBLIC_PATH . '/uploads/recipes');

require_once BASE_PATH . '/vendor/autoload.php';

enforce_https_in_production();
start_secure_session();
apply_security_headers();
