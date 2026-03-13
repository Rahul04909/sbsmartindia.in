<?php
// Function to count slides
$slide_count = 7; // We can see there are 7 slides manually, or we could count if it was dynamic
?>
<section class="hero-slider-container">
    <div class="hero-slider-wrapper">
        <!-- Slide 1 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_lapp.jpg" alt="Lapp Product Offer">
            </a>
        </div>
        <!-- Slide 2 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_secure.jpg" alt="Secure Products">
            </a>
        </div>
        <!-- Slide 3 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_siemens (1).png" alt="Siemens Automation">
            </a>
        </div>
        <!-- Slide 4 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_asco.jpg" alt="Siemens Automation">
            </a>
        </div>
        <!-- Slide 5 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_default.jpg" alt="Siemens Automation">
            </a>
        </div>
        <!-- Slide 6 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_flender.jpg" alt="Siemens Automation">
            </a>
        </div>
        <!-- Slide 7 -->
        <div class="hero-slide">
            <a href="#">
                <img src="asstes/hero/slider_innomotics.jpg" alt="Siemens Automation">
            </a>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button class="slider-prev" onclick="moveSlide(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button class="slider-next" onclick="moveSlide(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <!-- Dots (Dynamically Generated) -->
    <div class="slider-dots">
        <?php for($i = 0; $i < $slide_count; $i++): ?>
            <span class="slider-dot <?php echo $i === 0 ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $i; ?>)"></span>
        <?php endfor; ?>
    </div>
</section>

<script>
    let slideIndex = 0;
    const slidesWrapper = document.querySelector('.hero-slider-wrapper');
    // We select dots inside showSlide to ensure we always have the current list
    const totalSlides = document.querySelectorAll('.hero-slide').length;
    let autoSlideInterval;

    function showSlide(index) {
        if (index >= totalSlides) {
            slideIndex = 0;
        } else if (index < 0) {
            slideIndex = totalSlides - 1;
        } else {
            slideIndex = index;
        }

        const offset = -slideIndex * 100;
        slidesWrapper.style.transform = `translateX(${offset}%)`;

        // Update dots dynamically
        const dots = document.querySelectorAll('.slider-dot');
        dots.forEach(dot => dot.classList.remove('active'));
        if(dots[slideIndex]) {
            dots[slideIndex].classList.add('active');
        }
    }

    function moveSlide(step) {
        showSlide(slideIndex + step);
        resetAutoSlide();
    }

    function currentSlide(index) {
        showSlide(index);
        resetAutoSlide();
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            showSlide(slideIndex + 1);
        }, 5000); // 5 seconds
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Initialize
    startAutoSlide();
</script>
