<?php
require_once 'database/db_config.php';
?>
<section class="categories-section">
    <div class="container">
        <div class="categories-header">
            <h2>Browse Categories</h2>
            <a href="#" class="view-all-link">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="categories-slider-container">
            <button class="cat-nav-btn cat-nav-prev" onclick="scrollCategories(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            
            <div class="categories-wrapper" id="categoriesWrapper">
                
                <?php
                // Fetch Brands that have categories
                // Use distinct to avoid brands with no categories, or just fetch all and check count
                $brand_sql = "SELECT * FROM brands ORDER BY id DESC"; // Ordering by ID or Name
                $brand_result = $conn->query($brand_sql);

                if ($brand_result->num_rows > 0) {
                    while($brand = $brand_result->fetch_assoc()) {
                        $brand_id = $brand['id'];
                        $brand_name = $brand['name'];
                        
                        // Fetch Categories for this Brand (Limit 4)
                        $cat_sql = "SELECT * FROM product_categories WHERE brand_id = $brand_id ORDER BY id DESC LIMIT 4";
                        $cat_result = $conn->query($cat_sql);

                        if ($cat_result->num_rows > 0) {
                            ?>
                            <div class="brand-card">
                                <div class="brand-title"><?php echo htmlspecialchars($brand_name); ?></div>
                                <div class="brand-grid">
                                    <?php
                                    while($cat = $cat_result->fetch_assoc()) {
                                        $cat_name = $cat['name'];
                                        $cat_img = $cat['image'] ? $cat['image'] : 'assets/images/no-image.png'; // Fallback image if needed
                                        ?>
                                        <div class="cat-item">
                                            <div class="cat-item-img-box">
                                                <img src="<?php echo htmlspecialchars($cat_img); ?>" alt="<?php echo htmlspecialchars($cat_name); ?>">
                                            </div>
                                            <div class="cat-item-name"><?php echo htmlspecialchars($cat_name); ?></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <a href="#" class="see-all-brand">See all <?php echo htmlspecialchars($brand_name); ?></a>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "<p style='text-align:center; width:100%; color:#888;'>No categories found.</p>";
                }
                ?>

            </div>

            <button class="cat-nav-btn cat-nav-next" onclick="scrollCategories(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>
</section>

<script>
    function scrollCategories(direction) {
        const wrapper = document.getElementById('categoriesWrapper');
        const scrollAmount = 300; // Approx card width
        wrapper.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
</script>
