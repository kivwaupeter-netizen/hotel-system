<?php
$adminPageTitle = 'Edit Room';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/flash.php';
require_once '../includes/functions.php';
require_once 'includes/admin-header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] < 1) {
    redirectTo(BASE_URL . '/admin/rooms.php');
}

$roomId = (int) $_GET['id'];
$stmt   = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param('i', $roomId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    setFlash('error', 'Room not found.');
    redirectTo(BASE_URL . '/admin/rooms.php');
}

$errors       = [];
$nameVal      = $room['name'];
$typeVal      = $room['type'];
$descVal      = $room['description'];
$servicesVal  = $room['services'];
$priceVal     = $room['price_per_night'];
$maxGuestsVal = $room['max_guests'];
$isAvailable  = $room['is_available'];
$imageName    = $room['main_image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nameVal      = sanitize($_POST['name']            ?? '', $conn);
    $typeVal      = sanitize($_POST['type']            ?? '', $conn);
    $descVal      = sanitize($_POST['description']     ?? '', $conn);
    $servicesVal  = sanitize($_POST['services']        ?? '', $conn);
    $priceVal     = sanitize($_POST['price_per_night'] ?? '', $conn);
    $maxGuestsVal = sanitize($_POST['max_guests']      ?? '', $conn);
    $isAvailable  = isset($_POST['is_available']) ? 1 : 0;

    $validTypes = ['affordable', 'standard', 'luxury'];

    if (empty($nameVal))                             $errors[] = 'Room name is required.';
    if (!in_array($typeVal, $validTypes))            $errors[] = 'Please select a valid room type.';
    if (!is_numeric($priceVal) || $priceVal < 1)    $errors[] = 'Price must be a positive number.';
    if (!is_numeric($maxGuestsVal) || (int)$maxGuestsVal < 1) $errors[] = 'Max guests must be a positive integer.';

    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        $file    = $_FILES['room_image'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $errors[] = 'Image must be JPG, JPEG, PNG, or WEBP.';
        } elseif ($file['size'] > UPLOAD_MAX_SIZE) {
            $errors[] = 'Image must not exceed 2MB.';
        } else {
            $newImageName = uniqid('room_', true) . '.' . $ext;
            $dest         = $_SERVER['DOCUMENT_ROOT'] . '/hotel-system/' . UPLOAD_PATH . $newImageName;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                if ($imageName && !str_starts_with($imageName, 'http')) {
                    $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/hotel-system/' . UPLOAD_PATH . $imageName;
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $imageName = $newImageName;
            } else {
                $errors[] = 'Failed to upload image. Please try again.';
            }
        }
    }

    if (empty($errors)) {
        $price     = (float) $priceVal;
        $maxGuests = (int)   $maxGuestsVal;

        $stmt = $conn->prepare("
            UPDATE rooms
            SET name=?, type=?, description=?, services=?, price_per_night=?, max_guests=?, main_image=?, is_available=?
            WHERE id=?
        ");
        $stmt->bind_param('ssssdiisi', $nameVal, $typeVal, $descVal, $servicesVal, $price, $maxGuests, $imageName, $isAvailable, $roomId);
        $stmt->execute();
        $stmt->close();

        setFlash('success', 'Room updated successfully.');
        redirectTo(BASE_URL . '/admin/rooms.php');
    }
}

if (!empty($imageName) && !str_starts_with($imageName, 'http')) {
    $thumbSrc = BASE_URL . '/' . UPLOAD_PATH . htmlspecialchars($imageName);
} elseif (!empty($imageName)) {
    $thumbSrc = $imageName;
} else {
    $fallbacks = [
        'affordable' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=200&q=80',
        'standard'   => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=200&q=80',
        'luxury'     => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=200&q=80',
    ];
    $thumbSrc = $fallbacks[$typeVal] ?? BASE_URL . '/assets/images/placeholder.jpg';
}
?>

<?php if (!empty($errors)): ?>
    <div class="flash-message flash-error" style="margin-bottom:24px;">
        <?php foreach ($errors as $err): ?>
            <p><?php echo htmlspecialchars($err); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <h3 style="margin-bottom:24px; font-size:20px; font-weight:700; color:#264653;">Edit Room</h3>

    <form method="POST"
          action="<?php echo BASE_URL; ?>/admin/edit-room.php?id=<?php echo $roomId; ?>"
          enctype="multipart/form-data">

        <input type="hidden" name="room_id" value="<?php echo $roomId; ?>">

        <div class="form-group">
            <label>Room Name</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($nameVal); ?>" required>
        </div>

        <div class="form-group">
            <label>Room Type</label>
            <select name="type" required>
                <option value="">— Select Type —</option>
                <option value="affordable" <?php echo $typeVal === 'affordable' ? 'selected' : ''; ?>>Affordable</option>
                <option value="standard"   <?php echo $typeVal === 'standard'   ? 'selected' : ''; ?>>Standard</option>
                <option value="luxury"     <?php echo $typeVal === 'luxury'     ? 'selected' : ''; ?>>Luxury</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($descVal); ?></textarea>
        </div>

        <div class="form-group">
            <label>Services</label>
            <textarea name="services" rows="5"
                      placeholder="Enter each service on a new line or separated by commas"><?php echo htmlspecialchars($servicesVal); ?></textarea>
        </div>

        <div class="form-group">
            <label>Price Per Night (KES)</label>
            <input type="number" name="price_per_night" min="1" step="0.01"
                   value="<?php echo htmlspecialchars($priceVal); ?>" required>
        </div>

        <div class="form-group">
            <label>Max Guests</label>
            <input type="number" name="max_guests" min="1"
                   value="<?php echo htmlspecialchars($maxGuestsVal); ?>" required>
        </div>

        <div class="form-group">
            <label>Room Image</label>
            <div style="margin-bottom:10px;">
                <img src="<?php echo $thumbSrc; ?>"
                     alt="Current room image"
                     style="height:90px; width:140px; object-fit:cover; border-radius:8px; display:block; margin-bottom:8px;">
                <p style="font-size:13px; color:#888;">Current image shown above. Upload a new one to replace it.</p>
            </div>
            <input type="file" name="room_image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="form-group" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="is_available" id="is_available" value="1"
                   <?php echo $isAvailable ? 'checked' : ''; ?>>
            <label for="is_available" style="margin:0; font-weight:500;">Available for Booking</label>
        </div>

        <div style="display:flex; gap:16px; margin-top:24px;">
            <button type="submit" class="btn-primary">Update Room</button>
            <a href="<?php echo BASE_URL; ?>/admin/rooms.php" class="btn-outline">Cancel</a>
        </div>

    </form>
</div>

<?php require_once 'includes/admin-footer.php'; ?>