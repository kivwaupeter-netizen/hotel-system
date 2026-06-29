<?php

require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = mysqli_init();

    $conn->options(
        MYSQLI_OPT_CONNECT_TIMEOUT,
        10
    );

    // Enable SSL connection for Aiven
    // Skip certificate verification because Render does not have Aiven CA installed
    $conn->ssl_set(
        null,
        null,
        null,
        null,
        null
    );

    $conn->real_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT,
        null,
        MYSQLI_CLIENT_SSL
    );

    $conn->set_charset("utf8mb4");


} catch (mysqli_sql_exception $e) {

    error_log("Database connection failed: " . $e->getMessage());

    die("Database connection failed. Please try again later.");

}