<?php
$adminPageTitle = 'Add New Room';
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/flash.php';
require_once '../includes/functions.php';
require_once 'includes/admin-header.php';

$errors       = [];
$nameVal      = '';
$typeVal      = '';
$descVal      = '';
$servicesVal  = '';
$priceVal     = '';
$maxGuestsVal = '';
$isAvailable  = 1;
$imageName    = null;

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
            $imageName = uniqid('room_', true) . '.' . $ext;
            $dest      = $_SERVER['DOCUMENT_ROOT'] . '/hotel-system/' . UPLOAD_PATH . $imageName;
            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                $errors[] = 'Failed to upload image. Please try again.';
                $imageName = null;
            }
        }
    }

    if (empty($errors)) {
        $price     = (float) $priceVal;
        $maxGuests = (int)   $maxGuestsVal;

        $stmt = $conn->prepare("
            INSERT INTO rooms (name, type, description, services, price_per_night, max_guests, main_image, is_available)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssdiis', $nameVal, $typeVal, $descVal, $servicesVal, $price, $maxGuests, $imageName, $isAvailable);
        $stmt->execute();
        $stmt->close();

        setFlash('success', 'Room added successfully.');
        redirectTo(BASE_URL . '/admin/rooms.php');
    }
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
    <h3 style="margin-bottom:24px; font-size:20px; font-weight:700; color:#264653;">Add New Room</h3>

    <form method="POST"
          action="<?php echo BASE_URL; ?>/admin/add-room.php"
          enctype="multipart/form-data">

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
            <input type="file" name="room_image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="form-group" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="is_available" id="is_available" value="1"
                   <?php echo $isAvailable ? 'checked' : ''; ?>>
            <label for="is_available" style="margin:0; font-weight:500;">Available for Booking</label>
        </div>

        <div style="display:flex; gap:16px; margin-top:24px;">
            <button type="submit" class="btn-primary">Add Room</button>
            <a href="<?php echo BASE_URL; ?>/admin/rooms.php" class="btn-outline">Cancel</a>
        </div>

    </form>
</div>

<?php require_once 'includes/admin-footer.php'; ?>