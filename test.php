<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli(
    "sql210.byetcluster.com",
    "if0_42190043",
    "Phantom2026",
    "if0_42190043_phantom_ridge_resort"
);

if ($conn->connect_error) {
    die("FAILED: " . $conn->connect_error);
}

echo "DATABASE CONNECTED SUCCESSFULLY";
?>