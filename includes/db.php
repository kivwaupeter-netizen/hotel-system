<?php

require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = mysqli_init();

    // Connection timeout
    $conn->options(
        MYSQLI_OPT_CONNECT_TIMEOUT,
        10
    );

    // Aiven SSL certificate
    $conn->ssl_set(
        DB_SSL_CA,
        null,
        null,
        null,
        null
    );

    // Connect to Aiven MySQL
    $conn->real_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT,
        null,
        MYSQLI_CLIENT_SSL
    );

    // Use UTF-8
    $conn->set_charset("utf8mb4");


} catch (mysqli_sql_exception $e) {

    error_log("Database connection error: " . $e->getMessage());

    die("Database connection failed. Please try again later.");

}