<?php
function sanitize($value, $conn) {
    $value = trim($value);
    $value = strip_tags($value);
    $value = mysqli_real_escape_string($conn, $value);
    return $value;
}

function formatKES($amount) {
    return 'KES ' . number_format($amount, 0, '.', ',');
}

function calculateNights($check_in, $check_out) {
    $date1 = new DateTime($check_in);
    $date2 = new DateTime($check_out);
    if ($date2 <= $date1) {
        return 0;
    }
    return (int) $date1->diff($date2)->days;
}

function calculateTotalPrice($price_per_night, $num_nights) {
    return (float) ($price_per_night * $num_nights);
}

function getTypeLabel($type) {
    switch ($type) {
        case 'affordable': return 'Affordable';
        case 'standard':   return 'Standard';
        case 'luxury':     return 'Luxury';
        default:           return 'Unknown';
    }
}

function getTypeBadgeClass($type) {
    switch ($type) {
        case 'affordable': return 'badge-affordable';
        case 'standard':   return 'badge-standard';
        case 'luxury':     return 'badge-luxury';
        default:           return 'badge-default';
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function redirectTo($url) {
    header('Location: ' . $url);
    exit();
}

function getPaginationOffset($page = 1, $limit = 10) {
    if ($page < 1)  $page  = 1;
    if ($limit < 1) $limit = 10;
    return ($page - 1) * $limit;
}