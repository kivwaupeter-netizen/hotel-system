<?php

require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = mysqli_init();

    $conn->ssl_set(
        DB_SSL_CA,
        NULL,
        NULL,
        NULL,
        NULL
    );

    $conn->real_connect(
        DB_HOST,
        DB_USER,
        DB_PASS,
        DB_NAME,
        DB_PORT,
        NULL,
        MYSQLI_CLIENT_SSL
    );

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {

    error_log($e->getMessage());

    die("Database connection failed");
}