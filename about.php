<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'About Us';
require_once 'includes/header.php';
?>

<div class="page-banner" style="background:#2d6a4f; color:#ffffff; text-align:center; padding:100px 40px 50px;">
    <h1 style="font-size:40px; font-weight:800; margin-bottom:12px;">About Phantom Ridge Resort</h1>
    <p style="font-size:15px; opacity:0.85;">
        <a href="<?php echo BASE_URL; ?>/index.php" style="color:#e9c46a; text-decoration:none;">Home</a>
        &rsaquo; About
    </p>
</div>

<div class="section about-story" style="display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center;">
    <div>
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
             alt="Phantom Ridge Resort — Kenya Highlands"
             style="width:100%; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.15); object-fit:cover; height:420px;">
    </div>
    <div>
        <h2 style="font-size:32px; font-weight:800; color:#264653; margin-bottom:16px;">Our Story</h2>
        <div style="width:60px; height:4px; background:#2d6a4f; border-radius:2px; margin-bottom:24px;"></div>
        <p style="font-size:16px; color:#555; line-height:1.9; margin-bottom:16px;">
            Phantom Ridge Resort is a luxury BnB retreat nestled in the heart of Nyeri, Kenya. We were founded
            with a single vision — to offer every guest an authentic highland experience, surrounded by the
            breathtaking beauty of Kenya's nature, fresh mountain air, and the genuine warmth of Kenyan hospitality.
        </p>
        <p style="font-size:16px; color:#555; line-height:1.9;">
            We offer three carefully designed tiers of accommodation. Our <strong>Affordable</strong> rooms
            provide comfort and exceptional value for solo travelers and couples. Our <strong>Standard</strong>
            rooms deliver modern amenities ideal for families and small groups. And our <strong>Luxury</strong>
            villas offer a completely private, world-class experience for guests who desire only the finest.
        </p>
    </div>
</div>

<div style="background:#f8f9fa; padding:80px 40px;">
    <div style="max-width:1200px; margin:0 auto;">
        <h2 style="text-align:center; font-size:36px; font-weight:700; color:#264653; margin-bottom:12px;">Our Values</h2>
        <div style="width:60px; height:4px; background:#2d6a4f; margin:0 auto 50px; border-radius:2px;"></div>
        <div class="why-grid">
            <div class="why-card">
                <div class="icon">🏠</div>
                <h3>Comfort</h3>
                <p>Every room is thoughtfully designed so you feel completely at home, with premium furnishings and carefully selected amenities.</p>
            </div>
            <div class="why-card">
                <div class="icon">⭐</div>
                <h3>Authenticity</h3>
                <p>We celebrate Kenyan culture, landscape, and tradition in every corner of our resort, giving you a real and memorable experience.</p>
            </div>
            <div class="why-card">
                <div class="icon">🏆</div>
                <h3>Excellence</h3>
                <p>From the moment you arrive to the moment you leave, we hold ourselves to the highest standards of quality and service.</p>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <h2 style="text-align:center; font-size:36px; font-weight:700; color:#264653; margin-bottom:12px;">Meet Our Team</h2>
    <div style="width:60px; height:4px; background:#2d6a4f; margin:0 auto 50px; border-radius:2px;"></div>
    <div class="why-grid">
        <div class="why-card">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&crop=face&q=80"
                 alt="James Mwangi"
                 style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin:0 auto 16px; display:block; border:4px solid #2d6a4f;">
            <h3>James Mwangi</h3>
            <p style="color:#2d6a4f; font-weight:600; font-size:14px;">General Manager</p>
            <p style="margin-top:8px;">Over 15 years of hospitality leadership across East Africa's finest resorts.</p>
        </div>
        <div class="why-card">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop&crop=face&q=80"
                 alt="Grace Wanjiku"
                 style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin:0 auto 16px; display:block; border:4px solid #2d6a4f;">
            <h3>Grace Wanjiku</h3>
            <p style="color:#2d6a4f; font-weight:600; font-size:14px;">Head of Hospitality</p>
            <p style="margin-top:8px;">Dedicated to ensuring every guest feels welcomed, valued, and cared for throughout their stay.</p>
        </div>
        <div class="why-card">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop&crop=face&q=80"
                 alt="Brian Otieno"
                 style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin:0 auto 16px; display:block; border:4px solid #2d6a4f;">
            <h3>Brian Otieno</h3>
            <p style="color:#2d6a4f; font-weight:600; font-size:14px;">Executive Chef</p>
            <p style="margin-top:8px;">Crafting authentic Kenyan cuisine and international dishes that delight every palate.</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>