<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if (isLoggedIn()) {
    redirectTo(BASE_URL . '/index.php');
}

$errors   = [];
$nameVal  = '';
$emailVal = '';
$phoneVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = sanitize($_POST['name']             ?? '', $conn);
    $email            = sanitize($_POST['email']            ?? '', $conn);
    $phone            = sanitize($_POST['phone']            ?? '', $conn);
    $password         = trim($_POST['password']         ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    $nameVal  = $name;
    $emailVal = $email;
    $phoneVal = $phone;

    if (strlen($name) < 2)                              $errors[] = 'Full name must be at least 2 characters.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!empty($phone) && !preg_match('/^(07|01)[0-9]{8}$/', $phone)) $errors[] = 'Enter a valid Kenyan phone number (e.g. 07XXXXXXXX).';
    if (strlen($password) < 8)                          $errors[] = 'Password must be at least 8 characters.';
    if ($password !== $confirm_password)                $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'This email is already registered.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param('ssss', $name, $email, $hashed, $phone);
        $stmt->execute();
        $newId = $conn->insert_id;
        $stmt->close();

        $_SESSION['user_id']    = $newId;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role']  = 'user';

        setFlash('success', 'Account created successfully! Welcome to Phantom Ridge Resort.');
        redirectTo(BASE_URL . '/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Phantom Ridge Resort</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/auth.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo">
            <strong>Phantom Ridge Resort</strong>
            <span>Experience Luxury in Kenya</span>
        </div>

        <h1 class="auth-heading">Create Your Account</h1>
        <p class="auth-subheading">Join Phantom Ridge Resort and start booking today.</p>

        <?php displayFlash(); ?>

        <?php if (!empty($errors)): ?>
            <div class="flash-message flash-error" style="margin-bottom:20px;">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_URL; ?>/auth/register.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name"
                       value="<?php echo htmlspecialchars($nameVal); ?>"
                       placeholder="Your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email"
                       value="<?php echo htmlspecialchars($emailVal); ?>"
                       placeholder="your@email.com" required>
                <span class="field-error" id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone"
                       placeholder="07XXXXXXXX"
                       value="<?php echo htmlspecialchars($phoneVal); ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password"
                           placeholder="Minimum 8 characters" required>
                    <button type="button" class="toggle-password">Show</button>
                </div>
                <div class="password-strength">
                    <div class="strength-bar"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm-password"
                           placeholder="Repeat your password" required>
                    <button type="button" class="toggle-password">Show</button>
                </div>
                <span class="field-error" id="confirm-error"></span>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="<?php echo BASE_URL; ?>/auth/login.php">Sign in here.</a>
        </div>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>
</html>