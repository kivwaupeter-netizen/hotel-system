<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Welcome';
require_once 'includes/header.php';

$stmt = $conn->prepare("SELECT * FROM rooms WHERE type = 'affordable' AND is_available = 1 ORDER BY price_per_night ASC LIMIT 1");
$stmt->execute();
$affordable = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM rooms WHERE type = 'standard' AND is_available = 1 ORDER BY price_per_night ASC LIMIT 1");
$stmt->execute();
$standard = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM rooms WHERE type = 'luxury' AND is_available = 1 ORDER BY price_per_night ASC LIMIT 1");
$stmt->execute();
$luxury = $stmt->get_result()->fetch_assoc();
$stmt->close();

$featuredRooms = [
    'affordable' => $affordable,
    'standard'   => $standard,
    'luxury'     => $luxury
];
?>

<div class="hero-slider" id="hero-slider">
    <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&q=80')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=1600&q=80')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1600&q=80')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1600&q=80')"></div>
    <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1600&q=80')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">✦ Luxury BnB Experience</div>
        <h1>Welcome to<br><span>Phantom Ridge Resort</span></h1>
        <p>Experience comfort, nature, and luxury in the heart of Kenya</p>
        <div class="hero-buttons">
            <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-hero-primary">Explore Rooms</a>
            <a href="<?php echo BASE_URL; ?>/about.php" class="btn-hero-secondary">Our Story</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><strong>3</strong><span>Room Types</span></div>
            <div class="hero-stat"><strong>100%</strong><span>Satisfaction</span></div>
            <div class="hero-stat"><strong>24/7</strong><span>Support</span></div>
        </div>
    </div>
    <div class="slide-dots" id="slide-dots">
        <span class="dot active" onclick="goToSlide(0)"></span>
        <span class="dot" onclick="goToSlide(1)"></span>
        <span class="dot" onclick="goToSlide(2)"></span>
        <span class="dot" onclick="goToSlide(3)"></span>
        <span class="dot" onclick="goToSlide(4)"></span>
    </div>
    <div class="slide-arrows">
        <button onclick="changeSlide(-1)">&#10094;</button>
        <button onclick="changeSlide(1)">&#10095;</button>
    </div>
</div>

<div class="search-bar-floating">
    <form method="GET" action="<?php echo BASE_URL; ?>/rooms.php">
        <div class="search-field">
            <label>Check-in</label>
            <input type="date" name="check_in" id="check-in">
        </div>
        <div class="search-divider"></div>
        <div class="search-field">
            <label>Check-out</label>
            <input type="date" name="check_out" id="check-out">
        </div>
        <div class="search-divider"></div>
        <div class="search-field">
            <label>Guests</label>
            <input type="number" name="guests" min="1" max="20" placeholder="1">
        </div>
        <button type="submit" class="btn-search">Search Rooms</button>
    </form>
</div>

<section class="section featured-rooms">
    <h2>Our BnB Types</h2>
    <div class="section-line"></div>
    <div class="rooms-grid">
        <?php foreach ($featuredRooms as $type => $room): ?>
        <?php
        $imgSrc = '';
        if ($room && !empty($room['main_image'])) {
            $imgSrc = str_starts_with($room['main_image'], 'http')
                ? $room['main_image']
                : BASE_URL . '/' . UPLOAD_PATH . htmlspecialchars($room['main_image']);
        } else {
            $fallbacks = [
                'affordable' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&q=80',
                'standard'   => 'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=800&q=80',
                'luxury'     => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&q=80',
            ];
            $imgSrc = $fallbacks[$type];
        }
        ?>
        <div class="room-card">
            <div class="card-image-wrapper">
                <img src="<?php echo $imgSrc; ?>" alt="<?php echo $room ? htmlspecialchars($room['name']) : getTypeLabel($type); ?>">
                <div class="card-image-overlay">
                    <?php if ($room): ?>
                        <a href="<?php echo BASE_URL; ?>/room-detail.php?id=<?php echo $room['id']; ?>" class="btn-primary">View Details</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <span class="<?php echo getTypeBadgeClass($type); ?>"><?php echo getTypeLabel($type); ?></span>
                <?php if ($room): ?>
                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                    <div class="card-price"><?php echo formatKES($room['price_per_night']); ?> <small>/ night</small></div>
                    <p>👥 Max <?php echo $room['max_guests']; ?> guests</p>
                    <p class="card-desc"><?php $desc = htmlspecialchars($room['description']); echo strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc; ?></p>
                <?php else: ?>
                    <h3><?php echo getTypeLabel($type); ?> Room</h3>
                    <p class="card-desc">Coming soon.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="experience-section">
    <div class="experience-grid">
        <div class="experience-text">
            <div class="section-badge">✦ The Experience</div>
            <h2>More Than Just a Stay</h2>
            <p>Phantom Ridge Resort is nestled in the heart of Kenya's highlands, offering an escape from the ordinary. Every detail is curated to give you an experience you will never forget.</p>
            <div class="experience-features">
                <div class="exp-feature"><span>🌿</span><div><strong>Nature Immersion</strong><p>Surrounded by Kenya's breathtaking highland scenery</p></div></div>
                <div class="exp-feature"><span>🍽️</span><div><strong>Fine Dining</strong><p>Authentic Kenyan cuisine and international dishes</p></div></div>
                <div class="exp-feature"><span>🧖</span><div><strong>Spa & Wellness</strong><p>Rejuvenate your body and mind in our luxury spa</p></div></div>
            </div>
            <a href="<?php echo BASE_URL; ?>/about.php" class="btn-primary">Discover More</a>
        </div>
        <div class="experience-images">
            <div class="exp-img-large" style="background-image:url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&q=80')"></div>
            <div class="exp-img-small top" style="background-image:url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=400&q=80')"></div>
            <div class="exp-img-small bottom" style="background-image:url('https://images.unsplash.com/photo-1540518614846-7eded433c457?w=400&q=80')"></div>
        </div>
    </div>
</section>

<section class="section why-us">
    <h2>Why Choose Phantom Ridge Resort</h2>
    <div class="section-line"></div>
    <div class="why-grid">
        <div class="why-card"><div class="icon">💳</div><h3>No Hidden Fees</h3><p>The price you see is the price you pay. Transparent pricing always.</p></div>
        <div class="why-card"><div class="icon">✅</div><h3>Instant Confirmation</h3><p>Book in minutes and get instant booking confirmation.</p></div>
        <div class="why-card"><div class="icon">🔄</div><h3>Free Cancellation</h3><p>Many of our rooms offer free cancellation. No stress.</p></div>
    </div>
</section>

<section class="cta-section">
    <h2>Ready to Book Your Dream Stay?</h2>
    <p>Choose from our Affordable, Standard, and Luxury BnB rooms</p>
    <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-secondary">Explore Our Rooms</a>
</section>

<?php require_once 'includes/footer.php'; ?>