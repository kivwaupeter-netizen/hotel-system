<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/flash.php';

if (isLoggedIn()) {
    redirectTo(BASE_URL . '/index.php');
}

$error      = '';
$emailValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email']    ?? '', $conn);
    $password = trim($_POST['password'] ?? '');
    $emailValue = $email;

    if (empty($email) || empty($password)) {
        $error = 'Both email and password are required.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Invalid email or password.';
        } else {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            if ($user['role'] === 'admin') {
                setFlash('success', 'Welcome back, Admin!');
                redirectTo(BASE_URL . '/admin/index.php');
            } else {
                setFlash('success', 'Welcome back, ' . $user['name'] . '!');
                redirectTo(BASE_URL . '/index.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Phantom Ridge Resort</title>
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

        <h1 class="auth-heading">Welcome Back</h1>
        <p class="auth-subheading">Sign in to your account to manage bookings.</p>

        <?php displayFlash(); ?>

        <?php if ($error): ?>
            <div class="flash-message flash-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo BASE_URL; ?>/auth/login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email"
                       value="<?php echo htmlspecialchars($emailValue); ?>"
                       placeholder="your@email.com" required>
                <span class="field-error" id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password"
                           placeholder="Enter your password" required>
                    <button type="button" class="toggle-password">Show</button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account?
            <a href="<?php echo BASE_URL; ?>/auth/register.php">Register here.</a>
        </div>

    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>
</html>