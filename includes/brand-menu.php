<section class="brand-menu-section">
    <div class="container">
        <div class="brand-menu-header">
            <h2>Our Partners & Brands</h2>
            <a href="#" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="brand-menu-container">
            <button class="brand-slider-btn brand-slider-prev" onclick="scrollBrands(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            
            <div class="brand-menu-wrapper" id="brandMenuWrapper">
                <?php
                // Fetch all brands
                require_once 'database/db_config.php';
                $brand_menu_sql = "SELECT * FROM brands ORDER BY name ASC";
                $brand_menu_result = $conn->query($brand_menu_sql);

                if ($brand_menu_result->num_rows > 0) {
                    while($brand_item = $brand_menu_result->fetch_assoc()) {
                        $brand_logo = $brand_item['logo'];
                        $brand_name = $brand_item['name'];
                        
                        // Only display if logo exists, or use a placeholder/name
                        // Checking if file exists would be good but path might vary, straightforward check:
                        $logo_src = ($brand_logo && file_exists($brand_logo)) ? $brand_logo : 'assets/images/no-brand.png';
                        // Since we store relative path in DB like 'assets/uploads/brands/...', and we are in root index.php
                        // The DB path should be correct relative to root. 
                        // Note: admin uploads might store with '../' or similar if not handled carefully, 
                        // but usually we store 'assets/...' in DB. 
                        // Let's assume the DB has 'assets/uploads/brands/filename.jpg' or similar.
                        // If the DB has internal references, we might need to adjust.
                        // Based on admin code: move_uploaded_file($file["tmp_name"], $target_file) where $target_file was relative.
                        
                        // Fallback display if no logo? For now, we assume logos are important for this strip.
                        if ($brand_logo) {
                            ?>
                            <a href="#" class="brand-menu-item" title="<?php echo htmlspecialchars($brand_name); ?>">
                                <img src="<?php echo htmlspecialchars($brand_logo); ?>" alt="<?php echo htmlspecialchars($brand_name); ?>">
                            </a>
                            <?php
                        } else {
                             ?>
                            <a href="#" class="brand-menu-item" title="<?php echo htmlspecialchars($brand_name); ?>">
                                <span style="font-weight:bold; color:#555;"><?php echo htmlspecialchars($brand_name); ?></span>
                            </a>
                            <?php
                        }
                    }
                } else {
                    echo "<p>No brands available.</p>";
                }
                ?>
            </div>

            <button class="brand-slider-btn brand-slider-next" onclick="scrollBrands(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</section>

<script>
    function scrollBrands(direction) {
        const wrapper = document.getElementById('brandMenuWrapper');
        const scrollAmount = 200; // Approx scroll
        wrapper.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
</script>
