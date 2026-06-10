<?php
require_once '../includes/config.php';
require_once '../includes/auth_check.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $cancel_id = (int) sanitize($_POST['cancel_booking_id'], $conn);

    if ($cancel_id > 0) {
        $stmt = $conn->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->bind_param('ii', $cancel_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
            $stmt->bind_param('ii', $cancel_id, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            setFlash('success', 'Booking cancelled successfully.');
        } else {
            $stmt->close();
        }
    }

    redirectTo(BASE_URL . '/booking/my-bookings.php');
}

$user_id = (int) $_SESSION['user_id'];
$stmt    = $conn->prepare("
    SELECT bookings.*, rooms.name AS room_name, rooms.type, rooms.main_image
    FROM bookings
    JOIN rooms ON bookings.room_id = rooms.id
    WHERE bookings.user_id = ?
    ORDER BY bookings.created_at DESC
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageTitle = 'My Bookings';
require_once '../includes/header.php';
?>

<div class="section">
    <?php displayFlash(); ?>

    <h2>My Bookings</h2>
    <div class="section-line"></div>

    <?php if (empty($bookings)): ?>
        <div style="text-align:center; padding:80px 20px;">
            <div style="font-size:64px; margin-bottom:20px;">🛏</div>
            <p style="font-size:18px; color:#666; margin-bottom:24px;">You have no bookings yet.</p>
            <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-primary">Browse Rooms</a>
        </div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:24px;">
            <?php foreach ($bookings as $booking):
                $ref = 'PRR-' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT);

                if (!empty($booking['main_image'])) {
                    if (str_starts_with($booking['main_image'], 'http')) {
                        $imgSrc = $booking['main_image'];
                    } else {
                        $imgSrc = BASE_URL . '/' . UPLOAD_PATH . htmlspecialchars($booking['main_image']);
                    }
                } else {
                    $roomImages = [
                        'affordable' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=200&q=80',
                        'standard'   => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=200&q=80',
                        'luxury'     => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=200&q=80',
                    ];
                    $imgSrc = $roomImages[$booking['type']] ?? BASE_URL . '/assets/images/placeholder.jpg';
                }
            ?>
            <div style="background:#ffffff; border-radius:16px; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:24px; display:flex; gap:24px; align-items:flex-start;">

                <div style="flex-shrink:0;">
                    <img src="<?php echo $imgSrc; ?>"
                         alt="<?php echo htmlspecialchars($booking['room_name']); ?>"
                         style="width:130px; height:100px; object-fit:cover; border-radius:10px; display:block;">
                </div>

                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
                        <div>
                            <h3 style="font-size:18px; font-weight:700; color:#264653; margin-bottom:6px;">
                                <?php echo htmlspecialchars($booking['room_name']); ?>
                            </h3>
                            <span class="<?php echo getTypeBadgeClass($booking['type']); ?>">
                                <?php echo getTypeLabel($booking['type']); ?>
                            </span>
                        </div>
                        <span class="status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; font-size:14px; color:#666; margin-bottom:16px;">
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Ref</strong>
                            <?php echo $ref; ?>
                        </div>
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Check-in</strong>
                            <?php echo date('d M Y', strtotime($booking['check_in'])); ?>
                        </div>
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Check-out</strong>
                            <?php echo date('d M Y', strtotime($booking['check_out'])); ?>
                        </div>
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Nights</strong>
                            <?php echo $booking['num_nights']; ?>
                        </div>
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Guests</strong>
                            <?php echo $booking['num_guests']; ?>
                        </div>
                        <div>
                            <strong style="display:block; color:#264653; margin-bottom:2px;">Total</strong>
                            <span style="font-size:16px; font-weight:800; color:#2d6a4f;">
                                <?php echo formatKES($booking['total_price']); ?>
                            </span>
                        </div>
                    </div>

                    <?php if ($booking['status'] === 'pending'): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/booking/my-bookings.php">
                            <input type="hidden" name="cancel_booking_id" value="<?php echo $booking['id']; ?>">
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                    style="background:#dc3545; color:#fff; border:none; padding:8px 18px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;">
                                Cancel Booking
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>