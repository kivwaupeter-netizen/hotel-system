<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'Phantom Ridge Resort');
define('SITE_EMAIL', 'info@phantomridgeresort.co.ke');
define('SITE_PHONE', '+254 710199008');
define('SITE_ADDRESS', 'Chuka, Kenya');

define('BASE_URL', 'https://hotel-system-ei97.onrender.com');


/*
|--------------------------------------------------------------------------
| Database Configuration (Render Environment Variables)
|--------------------------------------------------------------------------
*/

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PORT', (int) getenv('DB_PORT'));


/*
|--------------------------------------------------------------------------
| Application Settings
|--------------------------------------------------------------------------
*/

define('UPLOAD_PATH', 'uploads/rooms/');
define('UPLOAD_MAX_SIZE', 2097152);


/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Africa/Nairobi');


/*
|--------------------------------------------------------------------------
| Production Validation
|--------------------------------------------------------------------------
*/

if (
    empty(DB_HOST) ||
    empty(DB_USER) ||
    empty(DB_PASS) ||
    empty(DB_NAME) ||
    empty(DB_PORT)
) {
    die('Missing database configuration.');
}