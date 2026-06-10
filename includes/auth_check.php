<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    setFlash('error', 'You must be logged in to access that page.');
    redirectTo(BASE_URL . '/auth/login.php');
    exit();
}