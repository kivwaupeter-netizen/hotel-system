<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'Phantom Ridge Resort');
define('SITE_EMAIL', 'info@phantomridgeresort.co.ke');
define('SITE_PHONE', '+254 710199008');
define('SITE_ADDRESS', 'Chuka, Kenya');

define('BASE_URL', 'https://hotel-system-ei97.onrender.com');

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PORT', (int)getenv('DB_PORT'));

define('DB_SSL_CA', __DIR__ . '/../ca.pem');

/* VALIDATION (CRASH EARLY IF CONFIG IS BROKEN) */
if (!DB_HOST || !DB_USER || !DB_NAME) {
    die("Missing database configuration");
}

define('UPLOAD_PATH', 'uploads/rooms/');
define('UPLOAD_MAX_SIZE', 2097152);

date_default_timezone_set('Africa/Nairobi');