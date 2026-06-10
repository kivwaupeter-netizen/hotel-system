<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] < 1) {
    redirectTo(BASE_URL . '/index.php');
}

$id   = (int) $_GET['id'];
$stmt = $conn->prepare("
    SELECT bookings.*, rooms.name AS room_name, rooms.type AS room_type,
           users.name AS user_name, users.id AS user_id
    FROM bookings
    JOIN rooms ON bookings.room_id = rooms.id
    JOIN users ON bookings.user_id = users.id
    WHERE bookings.id = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    redirectTo(BASE_URL . '/index.php');
}

if ((int)$booking['user_id'] !== (int)$_SESSION['user_id']) {
    setFlash('error', 'Access denied.');
    redirectTo(BASE_URL . '/index.php');
}

$pageTitle = 'Booking Confirmed';
require_once '../includes/header.php';

$ref = 'PRR-' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);
?>

<div class="section" style="max-width:700px; margin:0 auto; padding:60px 20px;">

    <div style="text-align:center; margin-bottom:40px;">
        <div style="width:80px; height:80px; background:#d4edda; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:40px;">
            ✅
        </div>
        <h1 style="color:#2d6a4f; font-size:36px; font-weight:800; margin-bottom:8px;">Booking Confirmed!</h1>
        <p style="font-size:18px; color:#666;">Your reservation has been received.</p>
    </div>

    <div style="background:#ffffff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.1); padding:36px; margin-bottom:24px;">
        <table style="width:100%; border-collapse:collapse; font-size:15px;">
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888; width:45%;">Booking Reference</td>
                <td style="padding:14px 0; font-weight:700; color:#264653;"><?php echo $ref; ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Room</td>
                <td style="padding:14px 0; font-weight:600;"><?php echo htmlspecialchars($booking['room_name']); ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Type</td>
                <td style="padding:14px 0;">
                    <span class="<?php echo getTypeBadgeClass($booking['room_type']); ?>">
                        <?php echo getTypeLabel($booking['room_type']); ?>
                    </span>
                </td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Check-in</td>
                <td style="padding:14px 0;"><?php echo date('d M Y', strtotime($booking['check_in'])); ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Check-out</td>
                <td style="padding:14px 0;"><?php echo date('d M Y', strtotime($booking['check_out'])); ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Nights</td>
                <td style="padding:14px 0;"><?php echo $booking['num_nights']; ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Guests</td>
                <td style="padding:14px 0;"><?php echo $booking['num_guests']; ?></td>
            </tr>
            <tr style="border-bottom:1px solid #f0f0f0;">
                <td style="padding:14px 0; color:#888;">Total Price</td>
                <td style="padding:14px 0; font-size:24px; font-weight:800; color:#2d6a4f;">
                    <?php echo formatKES($booking['total_price']); ?>
                </td>
            </tr>
            <tr>
                <td style="padding:14px 0; color:#888;">Status</td>
                <td style="padding:14px 0;">
                    <span class="status-pending">Pending Confirmation</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="background:#cce5ff; border-left:4px solid #007bff; border-radius:10px; padding:18px 22px; margin-bottom:32px; color:#004085; font-size:15px; line-height:1.7;">
        Our team will contact you on your registered phone number within 2 hours to confirm your booking.
        For urgent enquiries, call <strong><?php echo SITE_PHONE; ?></strong>.
    </div>

    <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
        <a href="<?php echo BASE_URL; ?>/booking/my-bookings.php" class="btn-primary">View My Bookings</a>
        <a href="<?php echo BASE_URL; ?>/index.php" class="btn-outline">Back to Home</a>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>