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
    </div>

    <!-- Navigation Arrows -->
    <button class="slider-prev" onclick="moveSlide(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button class="slider-next" onclick="moveSlide(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <!-- Dots -->
    <div class="slider-dots">
        <span class="slider-dot active" onclick="currentSlide(0)"></span>
        <span class="slider-dot" onclick="currentSlide(1)"></span>
        <span class="slider-dot" onclick="currentSlide(2)"></span>
    </div>
</section>

<script>
    let slideIndex = 0;
    const slidesWrapper = document.querySelector('.hero-slider-wrapper');
    const dots = document.querySelectorAll('.slider-dot');
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

        // Update dots
        dots.forEach(dot => dot.classList.remove('active'));
        dots[slideIndex].classList.add('active');
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
