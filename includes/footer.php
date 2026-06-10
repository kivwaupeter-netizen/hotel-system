<?php require_once __DIR__ . '/config.php'; ?>

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-brand">
            <strong>Phantom Ridge Resort</strong>
            <p>Experience comfort and luxury in the heart of Kenya.</p>
        </div>

        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>/rooms.php">Rooms</a></li>
                <li><a href="<?php echo BASE_URL; ?>/about.php">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>/contact.php">Contact</a></li>
                <li><a href="<?php echo BASE_URL; ?>/auth/login.php">Login</a></li>
                <li><a href="<?php echo BASE_URL; ?>/auth/register.php">Register</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h4>Contact Us</h4>
            <p><?php echo SITE_EMAIL; ?></p>
            <p><?php echo SITE_PHONE; ?></p>
            <p><?php echo SITE_ADDRESS; ?></p>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; <?php echo date('Y'); ?> Phantom Ridge Resort. All rights reserved.
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
</body>
</html>