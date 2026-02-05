<section class="stats-section" id="statsSection">
    <div class="stats-header">
        <h2>Numbers that Matter</h2>
        <p>Empowering Industry Since 1992</p>
    </div>

    <div class="stats-container">
        <!-- Stat 1 -->
        <div class="stat-item">
            <div class="stat-number">
                <span class="count" data-target="10000">0</span>
                <span class="stat-symbol">+</span>
            </div>
            <div class="stat-label">SKUs Available</div>
        </div>

        <!-- Stat 2 -->
        <div class="stat-item">
            <div class="stat-number">
                <span class="count" data-target="7">0</span>
                <span class="stat-symbol">+</span>
            </div>
            <div class="stat-label">Global Brands</div>
        </div>

        <!-- Stat 3 -->
        <div class="stat-item">
            <div class="stat-number">
                <span class="count" data-target="34">0</span>
                <span class="stat-symbol">+</span>
            </div>
            <div class="stat-label">Years of Trust</div>
        </div>

        <!-- Stat 4 -->
        <div class="stat-item">
            <div class="stat-number">
                <span class="count" data-target="160000">0</span>
            </div>
            <div class="stat-label">Pin Codes Covered</div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const statsSection = document.getElementById('statsSection');
        const counters = document.querySelectorAll('.count');
        let started = false; // Flag to ensure animation runs only once

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !started) {
                started = true;
                counters.forEach(counter => {
                    const target = +counter.getAttribute('data-target');
                    const duration = 2000; // Animation duration in ms
                    const increment = target / (duration / 20); // updates every 20ms

                    let current = 0;
                    const updateCount = () => {
                        current += increment;
                        if (current < target) {
                            counter.innerText = Math.ceil(current).toLocaleString();
                            setTimeout(updateCount, 20);
                        } else {
                            counter.innerText = target.toLocaleString();
                        }
                    };
                    updateCount();
                });
            }
        }, { threshold: 0.5 }); // Trigger when 50% visible

        observer.observe(statsSection);
    });
</script>
