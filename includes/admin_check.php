<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    setFlash('error', 'You must be logged in to access this area.');
    redirectTo(BASE_URL . '/auth/login.php');
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    setFlash('error', 'Access denied. You do not have permission to view this page.');
    redirectTo(BASE_URL . '/index.php');
    exit();
}