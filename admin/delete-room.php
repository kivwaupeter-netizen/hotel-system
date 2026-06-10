<?php
require_once '../includes/config.php';
require_once '../includes/admin_check.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo(BASE_URL . '/admin/rooms.php');
}

$room_id = sanitize($_POST['room_id'] ?? '', $conn);

if (!$room_id || (int)$room_id < 1) {
    setFlash('error', 'Invalid room ID.');
    redirectTo(BASE_URL . '/admin/rooms.php');
}

$room_id = (int) $room_id;

$stmt = $conn->prepare("SELECT main_image FROM rooms WHERE id = ?");
$stmt->bind_param('i', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($room && !empty($room['main_image']) && !str_starts_with($room['main_image'], 'http')) {
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/hotel-system/' . UPLOAD_PATH . $room['main_image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
$stmt->bind_param('i', $room_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    setFlash('success', 'Room deleted successfully.');
} else {
    setFlash('error', 'Failed to delete room.');
}

$stmt->close();

redirectTo(BASE_URL . '/admin/rooms.php');