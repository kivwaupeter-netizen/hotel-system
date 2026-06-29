<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME',       'Phantom Ridge Resort');
define('SITE_EMAIL',      'info@phantomridgeresort.co.ke');
define('SITE_PHONE',      '+254 710199008');
define('SITE_ADDRESS',    'Chuka, Kenya');

define('BASE_URL',        'http://localhost/hotel-system');

define('DB_HOST',         getenv('DB_HOST') ?: 'localhost');
define('DB_USER',         getenv('DB_USER') ?: 'root');
define('DB_PASS',         getenv('DB_PASS') ?: '');
define('DB_NAME',         getenv('DB_NAME') ?: 'phantom_ridge_resort');
define('DB_PORT',         getenv('DB_PORT') ?: 3306);

define('UPLOAD_PATH',     'uploads/rooms/');
define('UPLOAD_MAX_SIZE', 2097152);

date_default_timezone_set('Africa/Nairobi');