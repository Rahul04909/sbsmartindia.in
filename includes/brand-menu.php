<section class="brand-menu-section">
    <div class="container">
        <div class="brand-menu-header">
            <h2>Our Partners & Brands</h2>
            <a href="brands.php" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="brand-menu-container">
            <!-- No buttons, simple ticker container -->
            <div class="brand-ticker-track">
                <?php
                // Fetch all brands
                require_once 'database/db_config.php';
                $brand_menu_sql = "SELECT * FROM brands ORDER BY name ASC";
                $brand_menu_result = $conn->query($brand_menu_sql);
                $brands = [];
                if ($brand_menu_result->num_rows > 0) {
                    while($row = $brand_menu_result->fetch_assoc()) {
                        $brands[] = $row;
                    }
                }

                // Function to render brands
                function renderBrands($brandsList) {
                    if (empty($brandsList)) {
                        echo "<p>No brands available.</p>";
                        return;
                    }
                    foreach ($brandsList as $brand_item) {
                        $brand_logo = $brand_item['logo'];
                        $brand_name = $brand_item['name'];
                        // Path adjustment if needed, assuming direct DB path is usable relative to root
                        
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
                }

                // Render twice for infinite loop
                if (!empty($brands)) {
                    renderBrands($brands);
                    renderBrands($brands); // Duplicate set
                } else {
                    echo "<p>No brands available.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- No JS needed for pure CSS ticker unless we want pause/play controls or dynamic speed calculation -->
