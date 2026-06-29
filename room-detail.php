<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] < 1) {
    redirectTo(BASE_URL . '/rooms.php');
}

$id   = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ? AND is_available = 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    redirectTo(BASE_URL . '/rooms.php');
}

$pageTitle = htmlspecialchars($room['name']);
require_once 'includes/header.php';

function getRoomImageUrl($room) {
    if (!empty($room['main_image'])) {
        if (str_starts_with($room['main_image'], 'http')) {
            return $room['main_image'];
        }
        return BASE_URL . '/' . UPLOAD_PATH . htmlspecialchars($room['main_image']);
    }
    return BASE_URL . '/assets/images/placeholder.jpg';
}

$roomImages = [
    'affordable' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1200&q=80',
    'standard'   => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1200&q=80',
    'luxury'     => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80',
];

$imageUrl = !empty($room['main_image'])
    ? getRoomImageUrl($room)
    : ($roomImages[$room['type']] ?? BASE_URL . '/assets/images/placeholder.jpg');
?>

<div style="width:100%; height:480px; overflow:hidden; position:relative;">
    <img id="room-main-img"
     src="<?php echo $imageUrl; ?>"
     class="lightbox-trigger"
     data-gallery="<?php echo isset($galleryJson) ? $galleryJson : htmlspecialchars(json_encode([$imageUrl])); ?>"
     data-index="0"
     data-caption="<?php echo htmlspecialchars($room['name']); ?>"
     style="width:100%; height:100%; object-fit:cover; display:block; transition:opacity 0.5s ease; cursor:zoom-in;"
         alt="<?php echo htmlspecialchars($room['name']); ?>">
</div>

<?php
$galleryImages = [
    1 => [
        'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
        'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=800&q=80',
        'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800&q=80',
        'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&q=80',
    ],
    2 => [
        'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&q=80',
        'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=800&q=80',
        'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&q=80',
        'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&q=80',
    ],
    3 => [
        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',
        'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
        'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80',
        'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&q=80',
    ],
];
$gallery = $galleryImages[$room['id']] ?? [];
?>

<?php if (!empty($gallery)): ?>
<div style="display:flex; gap:10px; padding:10px 0; flex-wrap:wrap;">
    <?php
    $galleryJson = htmlspecialchars(json_encode($gallery), ENT_QUOTES);
    foreach ($gallery as $index => $img): ?>
    <img src="<?php echo $img; ?>"
         class="lightbox-trigger gallery-thumb"
         data-gallery="<?php echo $galleryJson; ?>"
         data-index="<?php echo $index; ?>"
         data-caption="<?php echo htmlspecialchars($room['name']); ?>"
         onclick="document.getElementById('room-main-img').src='<?php echo $img; ?>'"
         style="width:110px; height:75px; object-fit:cover; border-radius:8px; cursor:pointer; border:3px solid transparent; transition:all 0.3s ease;"
         onmouseover="this.style.border='3px solid #2d6a4f'; this.style.transform='scale(1.05)'"
         onmouseout="this.style.border='3px solid transparent'; this.style.transform='scale(1)'"
         alt="<?php echo htmlspecialchars($room['name']); ?> photo <?php echo $index + 1; ?>">
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="section">
    <div class="room-detail-grid">

        <div class="room-info">
            <h1 style="font-size:32px; font-weight:800; color:#264653; margin-bottom:12px;">
                <?php echo htmlspecialchars($room['name']); ?>
            </h1>

            <span class="<?php echo getTypeBadgeClass($room['type']); ?>"
                  style="font-size:14px; padding:6px 16px; margin-bottom:20px; display:inline-block;">
                <?php echo getTypeLabel($room['type']); ?>
            </span>

            <div class="room-description" style="margin:20px 0; font-size:16px; color:#555; line-height:1.8;">
                <p><?php echo htmlspecialchars($room['description']); ?></p>
            </div>

            <h3 style="font-size:20px; font-weight:700; color:#264653; margin-bottom:16px;">Services Included</h3>
            <ul class="services-list">
                <?php foreach (explode(',', $room['services']) as $service):
                    $service = trim($service);
                    if ($service): ?>
                    <li>
                        <span class="check">✅</span>
                        <?php echo htmlspecialchars($service); ?>
                    </li>
                <?php endif; endforeach; ?>
            </ul>

            <p style="margin-top:24px; font-size:16px; color:#444;">
                👥 This room accommodates up to <strong><?php echo $room['max_guests']; ?></strong> guests.
            </p>

            <div style="margin-top:48px;">
                <h3 style="font-size:20px; font-weight:700; color:#264653; margin-bottom:12px;">Guest Reviews</h3>
                <p style="color:#888; font-size:15px;">No reviews yet for this room.</p>
            </div>
        </div>

        <div class="room-booking-card">
            <div class="booking-card">
                <div style="margin-bottom:16px;">
                    <span style="font-size:28px; font-weight:800; color:#2d6a4f;">
                        <?php echo formatKES($room['price_per_night']); ?>
                    </span>
                    <span style="font-size:15px; color:#888;"> per night</span>
                </div>

                <hr style="margin-bottom:20px; border:none; border-top:1px solid #eee;">

                <form method="POST" action="<?php echo BASE_URL; ?>/booking/book.php">
                    <input type="hidden" name="room_id"        value="<?php echo $room['id']; ?>">
                    <input type="hidden" id="price-per-night"  value="<?php echo $room['price_per_night']; ?>">
                    <input type="hidden" id="max-guests"        value="<?php echo $room['max_guests']; ?>">
                    <input type="hidden" id="num-nights"   name="num_nights"   value="">
                    <input type="hidden" id="total-price"  name="total_price"  value="">

                    <div class="form-group">
                        <label for="check-in">Check-in Date</label>
                        <input type="date" name="check_in" id="check-in" required>
                    </div>

                    <div class="form-group">
                        <label for="check-out">Check-out Date</label>
                        <input type="date" name="check_out" id="check-out" required>
                    </div>

                    <div class="form-group">
                        <label for="num-guests">Number of Guests</label>
                        <input type="number" name="num_guests" id="num-guests"
                               min="1" max="<?php echo $room['max_guests']; ?>" required>
                    </div>

                    <div id="date-error"
                         style="color:#dc3545; font-size:13px; margin-bottom:8px;"></div>
                    <div id="guests-error"
                         style="color:#dc3545; font-size:13px; margin-bottom:8px;"></div>

                    <div id="price-summary"
                         style="background:#f8f9fa; border-radius:8px; padding:14px; margin-bottom:16px; font-size:14px; color:#555;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span>Duration</span>
                            <span id="num-nights-display" style="font-weight:600; color:#264653;"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                            <span>Rate</span>
                            <span style="font-weight:600; color:#264653;">
                                <?php echo formatKES($room['price_per_night']); ?> / night
                            </span>
                        </div>
                        <hr style="border:none; border-top:1px solid #ddd; margin:8px 0;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-weight:700;">Total</span>
                            <span id="total-price-display"
                                  style="font-weight:800; font-size:18px; color:#2d6a4f;"></span>
                        </div>
                    </div>

                    <?php if (!isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login.php"
                           class="btn-primary"
                           style="display:block; text-align:center;">
                            Login to Book
                        </a>
                    <?php else: ?>
                        <button type="submit" id="book-btn" class="btn-primary"
                                style="width:100%;">
                            Book This Room Now
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/booking.js"></script>

<?php require_once 'includes/footer.php'; ?>