document.addEventListener('DOMContentLoaded', function () {

    const hamburger = document.getElementById('hamburger-btn');
    const navLinks  = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            navLinks.classList.toggle('nav-open');
            hamburger.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.navbar')) {
                navLinks.classList.remove('nav-open');
                hamburger.classList.remove('open');
            }
        });
    }

    const checkIn  = document.getElementById('check-in');
    const checkOut = document.getElementById('check-out');

    if (checkIn && checkOut) {
        const today = new Date().toISOString().split('T')[0];
        checkIn.min = today;

        checkIn.addEventListener('change', function () {
            const selected = new Date(checkIn.value);
            selected.setDate(selected.getDate() + 1);
            const nextDay = selected.toISOString().split('T')[0];
            checkOut.min = nextDay;
            if (checkOut.value && checkOut.value <= checkIn.value) {
                checkOut.value = '';
            }
        });
    }

    window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 80) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    document.querySelectorAll('.flash-message').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.5s ease';
            el.style.opacity    = '0';
            setTimeout(function () {
                el.remove();
            }, 500);
        }, 4000);
    });

});
const heroImages = [
    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&q=80',
    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1600&q=80',
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1600&q=80',
    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1600&q=80'
];

const hero = document.getElementById('hero-section');

if (hero) {
    let current = 0;

    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        transition: opacity 2s ease-in-out;
        opacity: 0;
        z-index: 0;
    `;
    hero.style.position = 'relative';
    hero.style.overflow = 'hidden';
    hero.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('${heroImages[0]}')`;
    hero.style.backgroundSize = 'cover';
    hero.style.backgroundPosition = 'center';
    hero.appendChild(overlay);

    const heroContent = hero.querySelector('.hero-overlay');
    if (heroContent) {
        heroContent.style.position = 'relative';
        heroContent.style.zIndex = '2';
    }

    setInterval(function () {
        current = (current + 1) % heroImages.length;
        overlay.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('${heroImages[current]}')`;
        overlay.style.opacity = '1';

        setTimeout(function () {
            hero.style.backgroundImage = `linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('${heroImages[current]}')`;
            overlay.style.transition = 'none';
            overlay.style.opacity = '0';
            setTimeout(function () {
                overlay.style.transition = 'opacity 2s ease-in-out';
            }, 50);
        }, 2000);

    }, 6000);
}
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const dots   = document.querySelectorAll('.dot');

function goToSlide(n) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

function changeSlide(direction) {
    goToSlide(currentSlide + direction);
}

if (slides.length > 0) {
    setInterval(function () {
        goToSlide(currentSlide + 1);
    }, 6000);
}