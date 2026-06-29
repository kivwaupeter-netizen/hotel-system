document.addEventListener('DOMContentLoaded', function () {

    const hamburger = document.getElementById('hamburger-btn');
    const navLinks  = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            navLinks.classList.toggle('nav-open');
            hamburger.classList.toggle('open');
            const navAuth = document.getElementById('nav-auth');
            if (navAuth) navAuth.classList.toggle('nav-open');
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.navbar')) {
                navLinks.classList.remove('nav-open');
                hamburger.classList.remove('open');
                const navAuth = document.getElementById('nav-auth');
                if (navAuth) navAuth.classList.remove('nav-open');
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

    let rafPending = false;
    window.addEventListener('scroll', function () {
        if (!rafPending) {
            window.requestAnimationFrame(function () {
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    if (window.scrollY > 80) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                }

                const backToTop = document.getElementById('back-to-top');
                if (backToTop) {
                    if (window.scrollY > 400) {
                        backToTop.classList.add('visible');
                    } else {
                        backToTop.classList.remove('visible');
                    }
                }

                revealElements.forEach(function (el) {
                    const rect         = el.getBoundingClientRect();
                    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                    if (rect.top <= windowHeight - 60) {
                        el.classList.add('visible');
                    }
                });

                rafPending = false;
            });
            rafPending = true;
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
            setTimeout(function () { el.remove(); }, 500);
        }, 4000);
    });

    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');

    function checkVisible() {
        revealElements.forEach(function (el) {
            const rect         = el.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            if (rect.top <= windowHeight - 60) {
                el.classList.add('visible');
            }
        });
    }
    checkVisible();

    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    const lightboxOverlay = document.getElementById('lightbox-overlay');
    const lightboxImg     = document.getElementById('lightbox-img');
    const lightboxClose   = document.getElementById('lightbox-close');
    const lightboxPrev    = document.getElementById('lightbox-prev');
    const lightboxNext    = document.getElementById('lightbox-next');
    const lightboxCounter = document.getElementById('lightbox-counter');
    const lightboxCaption = document.getElementById('lightbox-caption');

    let lightboxImages  = [];
    let lightboxCurrent = 0;

    function openLightbox(images, index, caption) {
        if (!images || images.length === 0) return;
        lightboxImages  = images;
        lightboxCurrent = index;
        lightboxImg.src = images[index];
        lightboxCaption.textContent = caption || '';
        lightboxCounter.textContent = (index + 1) + ' / ' + images.length;
        lightboxOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightboxOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function lightboxGoTo(index) {
        if (lightboxImages.length === 0) return;
        lightboxCurrent           = (index + lightboxImages.length) % lightboxImages.length;
        lightboxImg.style.opacity = '0';
        setTimeout(function () {
            lightboxImg.src             = lightboxImages[lightboxCurrent];
            lightboxCounter.textContent = (lightboxCurrent + 1) + ' / ' + lightboxImages.length;
            lightboxImg.style.opacity   = '1';
        }, 200);
    }

    if (lightboxImg)   lightboxImg.style.transition   = 'opacity 0.2s ease';
    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);

    if (lightboxOverlay) {
        lightboxOverlay.addEventListener('click', function (e) {
            if (e.target === lightboxOverlay) closeLightbox();
        });
    }

    if (lightboxPrev) lightboxPrev.addEventListener('click', function () { lightboxGoTo(lightboxCurrent - 1); });
    if (lightboxNext) lightboxNext.addEventListener('click', function () { lightboxGoTo(lightboxCurrent + 1); });

    document.addEventListener('keydown', function (e) {
        if (!lightboxOverlay || !lightboxOverlay.classList.contains('active')) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowRight') lightboxGoTo(lightboxCurrent + 1);
        if (e.key === 'ArrowLeft')  lightboxGoTo(lightboxCurrent - 1);
    });

    document.querySelectorAll('.lightbox-trigger').forEach(function (img) {
        img.addEventListener('click', function () {
            try {
                const gallery = JSON.parse(this.dataset.gallery || '[]');
                const index   = parseInt(this.dataset.index   || '0');
                const caption = this.dataset.caption || '';
                openLightbox(gallery, index, caption);
            } catch (err) {
                const singleImg = this.src || this.dataset.src;
                if (singleImg) openLightbox([singleImg], 0, this.dataset.caption || '');
            }
        });
    });

    const track   = document.getElementById('testimonials-track');
    const tDots   = document.querySelectorAll('.testimonial-dot');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');

    if (track) {
        let tCurrent = 0;
        const cards  = track.querySelectorAll('.testimonial-card');
        const total  = cards.length;

        function updateTestimonials(animate) {
            if (cards.length === 0) return;
            if (animate !== false) {
                track.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            }
            const cardWidth = cards[0].offsetWidth + 28;
            track.style.transform = 'translateX(-' + (tCurrent * cardWidth) + 'px)';
            tDots.forEach(function (d, i) {
                d.classList.toggle('active', i === tCurrent);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                tCurrent = tCurrent >= total - 1 ? 0 : tCurrent + 1;
                updateTestimonials();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                tCurrent = tCurrent <= 0 ? total - 1 : tCurrent - 1;
                updateTestimonials();
            });
        }

        tDots.forEach(function (dot, i) {
            dot.addEventListener('click', function () {
                tCurrent = i;
                updateTestimonials();
            });
        });

        setInterval(function () {
            if (cards.length === 0) return;
            tCurrent = tCurrent >= total - 1 ? 0 : tCurrent + 1;
            updateTestimonials();
        }, 5000);

        window.addEventListener('resize', function () {
            updateTestimonials(false);
        });
    }

    let currentSlide = 0;
    const slides     = document.querySelectorAll('.slide');
    const dots       = document.querySelectorAll('.dot');

    function goToSlide(n) {
        if (slides.length === 0) return;
        slides[currentSlide].classList.remove('active');
        if (dots[currentSlide]) dots[currentSlide].classList.remove('active');
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    if (slides.length > 0) {
        setInterval(function () {
            goToSlide(currentSlide + 1);
        }, 6000);
    }

    const prevSlideBtn = document.getElementById('hero-prev');
    const nextSlideBtn = document.getElementById('hero-next');

    if (prevSlideBtn) {
        prevSlideBtn.addEventListener('click', function () {
            goToSlide(currentSlide - 1);
        });
    }

    if (nextSlideBtn) {
        nextSlideBtn.addEventListener('click', function () {
            goToSlide(currentSlide + 1);
        });
    }

    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
            goToSlide(i);
        });
    });

});

window.addEventListener('load', function () {
    const loader = document.getElementById('loading-screen');
    if (loader) {
        setTimeout(function () {
            loader.style.transition = 'opacity 0.8s ease, visibility 0.8s';
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            setTimeout(function () {
                loader.style.display = 'none';
            }, 800);
        }, 2500);
    }
});