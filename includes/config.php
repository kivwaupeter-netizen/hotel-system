<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'Phantom Ridge Resort');
define('SITE_EMAIL', 'info@phantomridgeresort.co.ke');
define('SITE_PHONE', '+254 710199008');
define('SITE_ADDRESS', 'Chuka, Kenya');

/* IMPORTANT: change localhost */
define('BASE_URL', 'https://hotel-system-ei97.onrender.com');

/* AIVEN DB CONFIG */
define('DB_HOST', 'mysql-b517aa9-kivwaupeter-2895.h.aivencloud.com');
define('DB_USER', 'avnadmin');
define('DB_PASS', 'YOUR_AIVEN_PASSWORD_HERE');
define('DB_NAME', 'phantom_ridge_resort');
define('DB_PORT', 18412);

define('UPLOAD_PATH', 'uploads/rooms/');
define('UPLOAD_MAX_SIZE', 2097152);

date_default_timezone_set('Africa/Nairobi');
?>