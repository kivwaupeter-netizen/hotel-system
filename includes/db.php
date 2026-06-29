<?php
require_once __DIR__ . '/config.php';

$conn = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

if ($conn->connect_error) {
    error_log("DB connection failed: " . $conn->connect_error);
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");