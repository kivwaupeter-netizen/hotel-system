<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Contact Us';

$errors   = [];
$name     = '';
$email    = '';
$subject  = '';
$message  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name']    ?? '', $conn);
    $email   = sanitize($_POST['email']   ?? '', $conn);
    $subject = sanitize($_POST['subject'] ?? '', $conn);
    $message = sanitize($_POST['message'] ?? '', $conn);

    if (empty($name))    $errors[] = 'Full name is required.';
    if (empty($email))   $errors[] = 'Email address is required.';
    if (empty($message)) $errors[] = 'Message is required.';

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if (empty($errors)) {
        setFlash('success', 'Thank you for your message. We will get back to you within 1-2 hours.');
        redirectTo(BASE_URL . '/contact.php');
    }
}

require_once 'includes/header.php';
?>

<div class="page-banner" style="background:#2d6a4f; color:#ffffff; text-align:center; padding:100px 40px 50px;">
    <h1 style="font-size:40px; font-weight:800; margin-bottom:12px;">Contact Us</h1>
    <p style="font-size:15px; opacity:0.85;">
        <a href="<?php echo BASE_URL; ?>/index.php" style="color:#e9c46a; text-decoration:none;">Home</a>
        &rsaquo; Contact
    </p>
</div>

<div class="section">
    <div class="contact-layout" style="display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:start;">

        <div>
            <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:28px;">
                <div style="background:#ffffff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:24px; display:flex; align-items:center; gap:16px;">
                    <span style="font-size:32px;">📍</span>
                    <div>
                        <h4 style="font-size:16px; font-weight:700; color:#264653; margin-bottom:4px;">Address</h4>
                        <p style="color:#666; font-size:15px;">Chuka, Tharaka Nithi, Kenya</p>
                    </div>
                </div>
                <div style="background:#ffffff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:24px; display:flex; align-items:center; gap:16px;">
                    <span style="font-size:32px;">📞</span>
                    <div>
                        <h4 style="font-size:16px; font-weight:700; color:#264653; margin-bottom:4px;">Phone</h4>
                        <p style="color:#666; font-size:15px;">+254 710 199 008</p>
                    </div>
                </div>
                <div style="background:#ffffff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); padding:24px; display:flex; align-items:center; gap:16px;">
                    <span style="font-size:32px;">✉️</span>
                    <div>
                        <h4 style="font-size:16px; font-weight:700; color:#264653; margin-bottom:4px;">Email</h4>
                        <p style="color:#666; font-size:15px;">info@phantomridgeresort.co.ke</p>
                        <p style="color:#666; font-size:15px;">kivwaupeter@gmail.com</p>
                    </div>
                </div>
            </div>

            <div style="border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.1); height:280px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.104138974118!2d37.6432!3d-0.3347!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x18288ca8a0bcae5b%3A0x9a5b7d5e7e5e5e5e!2sChuka%2C%20Kenya!5e0!3m2!1sen!2ske!4v1620000000000!5m2!1sen!2ske"
                    width="100%"
                    height="280"
                    style="border:0; display:block;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div>
            <h2 style="font-size:28px; font-weight:800; color:#264653; margin-bottom:8px;">Send Us a Message</h2>
            <div style="width:60px; height:4px; background:#2d6a4f; border-radius:2px; margin-bottom:28px;"></div>

            <?php if (!empty($errors)): ?>
                <div class="flash-message flash-error" style="margin-bottom:20px;">
                    <?php foreach ($errors as $err): ?>
                        <p><?php echo htmlspecialchars($err); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php displayFlash(); ?>

            <form method="POST" action="<?php echo BASE_URL; ?>/contact.php">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name"
                           value="<?php echo htmlspecialchars($name); ?>"
                           placeholder="Your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email"
                           value="<?php echo htmlspecialchars($email); ?>"
                           placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject"
                           value="<?php echo htmlspecialchars($subject); ?>"
                           placeholder="What is this about?">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" rows="6"
                              placeholder="Write your message here..." required><?php echo htmlspecialchars($message); ?></textarea>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">Send Message</button>
            </form>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>