<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo(BASE_URL . '/rooms.php');
}

$room_id    = sanitize($_POST['room_id']    ?? '', $conn);
$check_in   = sanitize($_POST['check_in']   ?? '', $conn);
$check_out  = sanitize($_POST['check_out']  ?? '', $conn);
$num_guests = sanitize($_POST['num_guests'] ?? '', $conn);

$back = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/rooms.php';

if (!$room_id || (int)$room_id < 1) {
    setFlash('error', 'Invalid booking data. Please try again.');
    redirectTo($back);
}

$checkInDate  = DateTime::createFromFormat('Y-m-d', $check_in);
$checkOutDate = DateTime::createFromFormat('Y-m-d', $check_out);
$today        = new DateTime();
$today->setTime(0, 0, 0);

if (!$checkInDate || $checkInDate < $today) {
    setFlash('error', 'Invalid booking data. Please try again.');
    redirectTo($back);
}

if (!$checkOutDate || $checkOutDate <= $checkInDate) {
    setFlash('error', 'Invalid booking data. Please try again.');
    redirectTo($back);
}

if (!$num_guests || (int)$num_guests < 1) {
    setFlash('error', 'Invalid booking data. Please try again.');
    redirectTo($back);
}

$room_id    = (int) $room_id;
$num_guests = (int) $num_guests;

$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_available = 1");
$stmt->bind_param('i', $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    setFlash('error', 'Room not found or not available.');
    redirectTo(BASE_URL . '/rooms.php');
}

if ($num_guests > (int)$room['max_guests']) {
    setFlash('error', 'Number of guests exceeds the maximum allowed for this room.');
    redirectTo($back);
}

$num_nights  = calculateNights($check_in, $check_out);
$total_price = calculateTotalPrice($room['price_per_night'], $num_nights);

if ($num_nights < 1) {
    setFlash('error', 'Invalid booking data. Please try again.');
    redirectTo($back);
}

$stmt = $conn->prepare("SELECT id FROM bookings WHERE room_id = ? AND status != 'cancelled' AND (check_in < ? AND check_out > ?)");
$stmt->bind_param('iss', $room_id, $check_out, $check_in);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    setFlash('error', 'Sorry, this room is already booked for the selected dates.');
    redirectTo($back);
}
$stmt->close();

$user_id = (int) $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, num_guests, num_nights, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param('iisssid', $user_id, $room_id, $check_in, $check_out, $num_guests, $num_nights, $total_price);
$stmt->execute();
$booking_id = $conn->insert_id;
$stmt->close();

setFlash('success', 'Booking submitted successfully!');
redirectTo(BASE_URL . '/booking/confirm.php?id=' . $booking_id);