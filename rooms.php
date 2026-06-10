<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Our Rooms';
require_once 'includes/header.php';

$type      = isset($_GET['type'])      ? sanitize($_GET['type'], $conn)      : '';
$min_price = isset($_GET['min_price']) ? sanitize($_GET['min_price'], $conn) : '';
$max_price = isset($_GET['max_price']) ? sanitize($_GET['max_price'], $conn) : '';
$guests    = isset($_GET['guests'])    ? sanitize($_GET['guests'], $conn)    : '';

$validTypes = ['affordable', 'standard', 'luxury'];

$sql    = "SELECT * FROM rooms WHERE is_available = 1";
$params = [];
$types  = '';

if ($type && in_array($type, $validTypes)) {
    $sql    .= " AND type = ?";
    $types  .= 's';
    $params[] = $type;
}

if ($min_price && is_numeric($min_price)) {
    $sql    .= " AND price_per_night >= ?";
    $types  .= 'd';
    $params[] = (float) $min_price;
}

if ($max_price && is_numeric($max_price)) {
    $sql    .= " AND price_per_night <= ?";
    $types  .= 'd';
    $params[] = (float) $max_price;
}

if ($guests && is_numeric($guests)) {
    $sql    .= " AND max_guests >= ?";
    $types  .= 'i';
    $params[] = (int) $guests;
}

$sql .= " ORDER BY price_per_night ASC";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query($sql);
}

$rooms = $result->fetch_all(MYSQLI_ASSOC);

function getRoomImage($room) {
    if (!empty($room['main_image'])) {
        if (str_starts_with($room['main_image'], 'http')) {
            return $room['main_image'];
        }
        return BASE_URL . '/' . UPLOAD_PATH . htmlspecialchars($room['main_image']);
    }
    return BASE_URL . '/assets/images/placeholder.jpg';
}
?>

<div class="section">
    <h2>Explore Our Rooms</h2>
    <div class="section-line"></div>

    <form class="filter-form" method="GET" action="<?php echo BASE_URL; ?>/rooms.php"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; margin-bottom:32px; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08);">
        <div>
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Room Type</label>
            <select name="type" style="padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:15px;">
                <option value="">All Types</option>
                <option value="affordable" <?php echo $type === 'affordable' ? 'selected' : ''; ?>>Affordable</option>
                <option value="standard"   <?php echo $type === 'standard'   ? 'selected' : ''; ?>>Standard</option>
                <option value="luxury"     <?php echo $type === 'luxury'     ? 'selected' : ''; ?>>Luxury</option>
            </select>
        </div>
        <div>
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Min Price (KES)</label>
            <input type="number" name="min_price" placeholder="e.g. 4000"
                   value="<?php echo htmlspecialchars($min_price); ?>"
                   style="padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:15px; width:140px;">
        </div>
        <div>
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Max Price (KES)</label>
            <input type="number" name="max_price" placeholder="e.g. 25000"
                   value="<?php echo htmlspecialchars($max_price); ?>"
                   style="padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:15px; width:140px;">
        </div>
        <div>
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Guests</label>
            <input type="number" name="guests" placeholder="e.g. 2" min="1"
                   value="<?php echo htmlspecialchars($guests); ?>"
                   style="padding:10px 14px; border:1px solid #ddd; border-radius:8px; font-size:15px; width:100px;">
        </div>
        <div style="display:flex; gap:10px; align-items:center; padding-top:20px;">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-outline">Clear Filters</a>
        </div>
    </form>

    <?php if (empty($rooms)): ?>
        <div class="empty-state" style="text-align:center; padding:60px 20px;">
            <p style="font-size:18px; color:#666; margin-bottom:20px;">No rooms found matching your search. Try adjusting your filters.</p>
            <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-primary">View All Rooms</a>
        </div>
    <?php else: ?>
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
            <div class="room-card">
                <img src="<?php echo getRoomImage($room); ?>"
                     alt="<?php echo htmlspecialchars($room['name']); ?>"
                     style="width:100%; height:240px; object-fit:cover; display:block;">
                <div class="card-body">
                    <span class="<?php echo getTypeBadgeClass($room['type']); ?>">
                        <?php echo getTypeLabel($room['type']); ?>
                    </span>
                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                    <p style="font-size:14px; color:#666; margin:6px 0;">
                        👥 Max <?php echo $room['max_guests']; ?> guests
                    </p>
                    <div class="card-price">
                        <?php echo formatKES($room['price_per_night']); ?>
                        <small style="font-size:14px; font-weight:400; color:#888;">/ night</small>
                    </div>
                    <p class="card-desc">
                        <?php
                        $desc = htmlspecialchars($room['description']);
                        echo strlen($desc) > 120 ? substr($desc, 0, 120) . '...' : $desc;
                        ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/room-detail.php?id=<?php echo $room['id']; ?>"
                       class="btn-primary" style="display:block; text-align:center;">View Details</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>