<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME',       'Phantom Ridge Resort');
define('SITE_EMAIL',      'info@phantomridgeresort.co.ke');
define('SITE_PHONE',      '+254 700 000 000');
define('SITE_ADDRESS',    'Nyeri, Kenya');
define('BASE_URL',        'http://localhost/hotel-system');
define('DB_HOST',         'localhost');
define('DB_USER',         'root');
define('DB_PASS',         '');
define('DB_NAME',         'phantom_ridge_resort');
define('UPLOAD_PATH',     'uploads/rooms/');
define('UPLOAD_MAX_SIZE', 2097152);

date_default_timezone_set('Africa/Nairobi');