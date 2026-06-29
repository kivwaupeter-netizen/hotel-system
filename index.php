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

<style>
/* Hero Slider Hardware Acceleration & Transitions */
.hero-slider {
    position: relative;
    overflow: hidden;
    height: 80vh;
    min-height: 500px;
    width: 100%;
}
.hero-slider .slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), visibility 1.2s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
}
.hero-slider .slide.active {
    opacity: 1;
    visibility: visible;
    z-index: 2;
}
.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.45);
    z-index: 3;
}
.hero-content {
    position: relative;
    z-index: 4;
}
.slide-dots, .slide-arrows button {
    z-index: 5;
}

/* Testimonials Carousel Track and Mask Styles */
.testimonials-track-wrapper {
    width: 100%;
    overflow: hidden;
    position: relative;
    padding: 20px 0;
}
.testimonials-track {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    width: 100%;
}
.testimonial-card {
    flex: 0 0 100%;
    width: 100%;
    box-sizing: border-box;
    padding: 15px;
}
@media (min-width: 768px) {
    .testimonial-card {
        flex: 0 0 33.333%;
        width: 33.333%;
    }
}
</style>

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
                <img src="<?php echo $imgSrc; ?>"
                     alt="<?php echo $room ? htmlspecialchars($room['name']) : getTypeLabel($type); ?>">
                <div class="card-image-overlay">
                    <?php if ($room): ?>
                        <a href="<?php echo BASE_URL; ?>/room-detail.php?id=<?php echo $room['id']; ?>"
                           class="btn-primary">View Details</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <span class="<?php echo getTypeBadgeClass($type); ?>">
                    <?php echo getTypeLabel($type); ?>
                </span>
                <?php if ($room): ?>
                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                    <div class="card-price">
                        <?php echo formatKES($room['price_per_night']); ?>
                        <small>/ night</small>
                    </div>
                    <p>👥 Max <?php echo $room['max_guests']; ?> guests</p>
                    <p class="card-desc">
                        <?php
                        $desc = htmlspecialchars($room['description']);
                        echo strlen($desc) > 100 ? substr($desc, 0, 100) . '...' : $desc;
                        ?>
                    </p>
                    <a href="<?php echo BASE_URL; ?>/room-detail.php?id=<?php echo $room['id']; ?>"
                       class="btn-primary" style="display:block; text-align:center; margin-top:12px;">
                        View Details
                    </a>
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
                <div class="exp-feature">
                    <span>🌿</span>
                    <div>
                        <strong>Nature Immersion</strong>
                        <p>Surrounded by Kenya's breathtaking highland scenery</p>
                    </div>
                </div>
                <div class="exp-feature">
                    <span>🍽️</span>
                    <div>
                        <strong>Fine Dining</strong>
                        <p>Authentic Kenyan cuisine and international dishes</p>
                    </div>
                </div>
                <div class="exp-feature">
                    <span>🧖</span>
                    <div>
                        <strong>Spa & Wellness</strong>
                        <p>Rejuvenate your body and mind in our luxury spa</p>
                    </div>
                </div>
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

<section class="testimonials-section">
    <div style="max-width:1200px; margin:0 auto; padding: 0 15px;">
        <h2>What Our Guests Say</h2>
        <div class="section-line"></div>
        <div class="testimonials-track-wrapper">
            <div class="testimonials-track" id="testimonials-track">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">Phantom Ridge Resort exceeded every expectation. The highland views were breathtaking and the staff made us feel like royalty from the moment we arrived.</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar"
                             src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face&q=80"
                             alt="James Mwenda">
                        <div class="testimonial-author-info">
                            <strong>James Mwenda</strong>
                            <span>Nairobi, Kenya</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">The Luxury Villa was absolutely stunning. Private, peaceful, and perfectly designed. We celebrated our anniversary here and it was unforgettable.</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar"
                             src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face&q=80"
                             alt="Grace Njeri">
                        <div class="testimonial-author-info">
                            <strong>Grace Njeri</strong>
                            <span>Mombasa, Kenya</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">We booked the Garden Suite for our family of four and it was perfect. The pool, the food, the service — everything was world class. We are definitely coming back.</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar"
                             src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face&q=80"
                             alt="Brian Ochieng">
                        <div class="testimonial-author-info">
                            <strong>Brian Ochieng</strong>
                            <span>Kisumu, Kenya</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">Affordable yet so comfortable. The Savannah Room was cozy, clean and had everything we needed. The breakfast was delicious. Great value for money.</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar"
                             src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face&q=80"
                             alt="Amina Hassan">
                        <div class="testimonial-author-info">
                            <strong>Amina Hassan</strong>
                            <span>Nakuru, Kenya</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">The spa experience at Phantom Ridge is unmatched. I came stressed and left completely renewed. The highland air alone is worth the trip. Highly recommended.</p>
                    <div class="testimonial-author">
                        <img class="testimonial-avatar"
                             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face&q=80"
                             alt="David Kamau">
                        <div class="testimonial-author-info">
                            <strong>David Kamau</strong>
                            <span>Nyeri, Kenya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="testimonial-controls">
            <button class="testimonial-btn" id="testimonial-prev">&#10094;</button>
            <div class="testimonial-dots" id="testimonial-dots">
                <span class="testimonial-dot active" onclick="goToTestimonialSlide(0)"></span>
                <span class="testimonial-dot" onclick="goToTestimonialSlide(1)"></span>
                <span class="testimonial-dot" onclick="goToTestimonialSlide(2)"></span>
                <span class="testimonial-dot" onclick="goToTestimonialSlide(3)"></span>
                <span class="testimonial-dot" onclick="goToTestimonialSlide(4)"></span>
            </div>
            <button class="testimonial-btn" id="testimonial-next">&#10095;</button>
        </div>
    </div>
</section>

<section class="section why-us">
    <h2>Why Choose Phantom Ridge Resort</h2>
    <div class="section-line"></div>
    <div class="why-grid">
        <div class="why-card">
            <div class="icon">💳</div>
            <h3>No Hidden Fees</h3>
            <p>The price you see is the price you pay. Transparent pricing always.</p>
        </div>
        <div class="why-card">
            <div class="icon">✅</div>
            <h3>Instant Confirmation</h3>
            <p>Book in minutes and get instant booking confirmation.</p>
        </div>
        <div class="why-card">
            <div class="icon">🔄</div>
            <h3>Free Cancellation</h3>
            <p>Many of our rooms offer free cancellation. No stress.</p>
        </div>
    </div>
</section>

<section class="cta-section">
    <h2>Ready to Book Your Dream Stay?</h2>
    <p>Choose from our Affordable, Standard, and Luxury BnB rooms</p>
    <a href="<?php echo BASE_URL; ?>/rooms.php" class="btn-secondary">Explore Our Rooms</a>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // ----------------------------------------------------
    // 1. HERO SLIDER LOGIC
    // ----------------------------------------------------
    let currentHeroIndex = 0;
    const heroSlides = document.querySelectorAll('#hero-slider .slide');
    const heroDots = document.querySelectorAll('#slide-dots .dot');
    const totalHeroSlides = heroSlides.length;
    let heroInterval = setInterval(autoHeroNext, 5000);

    function updateHeroDisplay() {
        heroSlides.forEach((slide, idx) => {
            if(idx === currentHeroIndex) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });
        heroDots.forEach((dot, idx) => {
            if(idx === currentHeroIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    window.goToSlide = function(index) {
        clearInterval(heroInterval);
        currentHeroIndex = index;
        updateHeroDisplay();
        heroInterval = setInterval(autoHeroNext, 5000);
    };

    window.changeSlide = function(step) {
        clearInterval(heroInterval);
        currentHeroIndex = (currentHeroIndex + step + totalHeroSlides) % totalHeroSlides;
        updateHeroDisplay();
        heroInterval = setInterval(autoHeroNext, 5000);
    };

    function autoHeroNext() {
        currentHeroIndex = (currentHeroIndex + 1) % totalHeroSlides;
        updateHeroDisplay();
    }

    // ----------------------------------------------------
    // 2. TESTIMONIAL CAROUSEL ENGINE
    // ----------------------------------------------------
    let currentTestimonialIndex = 0;
    const track = document.getElementById('testimonials-track');
    const cards = document.querySelectorAll('.testimonial-card');
    const dots = document.querySelectorAll('#testimonial-dots .testimonial-dot');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');
    const totalTestimonials = cards.length;

    function getVisibleCards() {
        return window.innerWidth >= 768 ? 3 : 1;
    }

    function getMaxIndex() {
        return Math.max(0, totalTestimonials - getVisibleCards());
    }

    function updateTestimonialDisplay() {
        const visibleCards = getVisibleCards();
        let percentageMove = 0;

        if (visibleCards === 3) {
            percentageMove = currentTestimonialIndex * (100 / 3);
        } else {
            percentageMove = currentTestimonialIndex * 100;
        }

        track.style.transform = `translateX(-${percentageMove}%)`;

        dots.forEach((dot, idx) => {
            if (idx === currentTestimonialIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    window.goToTestimonialSlide = function(index) {
        const maxIdx = getMaxIndex();
        currentTestimonialIndex = index > maxIdx ? maxIdx : index;
        updateTestimonialDisplay();
    };

    if(nextBtn) {
        nextBtn.addEventListener('click', function() {
            const maxIdx = getMaxIndex();
            if (currentTestimonialIndex >= maxIdx) {
                currentTestimonialIndex = 0;
            } else {
                currentTestimonialIndex++;
            }
            updateTestimonialDisplay();
        });
    }

    if(prevBtn) {
        prevBtn.addEventListener('click', function() {
            const maxIdx = getMaxIndex();
            if (currentTestimonialIndex <= 0) {
                currentTestimonialIndex = maxIdx;
            } else {
                currentTestimonialIndex--;
            }
            updateTestimonialDisplay();
        });
    }

    window.addEventListener('resize', function() {
        const maxIdx = getMaxIndex();
        if(currentTestimonialIndex > maxIdx) {
            currentTestimonialIndex = maxIdx;
        }
        updateTestimonialDisplay();
    });

    updateTestimonialDisplay();
});
</script>

<?php require_once 'includes/footer.php'; ?>