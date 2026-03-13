<?php
/**
 * Latest Products Component
 */
require_once __DIR__ . '/../database/db_config.php';

// Fetch latest 8 active products
$sql = "SELECT p.*, b.name as brand_name 
        FROM products p 
        LEFT JOIN brands b ON p.brand_id = b.id 
        WHERE p.status = 1 
        ORDER BY p.id DESC 
        LIMIT 8";
$result = $conn->query($sql);
?>

<section class="latest-products-section">
    <div class="container">
        <div class="latest-products-header">
            <h2>Latest Arrivals</h2>
            <a href="products.php" class="view-all-btn">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="products-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $image_path = !empty($row['featured_image']) ? $row['featured_image'] : 'assets/images/no-image.png';
                    $brand = !empty($row['brand_name']) ? $row['brand_name'] : 'Generic';
                    $price_request = $row['is_price_request'];
                    ?>
                    <div class="product-card">
                        <div class="product-image-box">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-image">
                            <div class="product-badges">
                                <?php if($row['discount_percentage'] > 0 && !$price_request): ?>
                                    <span class="badge-sale"><?php echo round($row['discount_percentage']); ?>% OFF</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="product-details">
                            <div class="product-brand"><?php echo htmlspecialchars($brand); ?></div>
                            <h3 class="product-title" title="<?php echo htmlspecialchars($row['title']); ?>">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>
                            
                            <div class="product-price-box">
                                <?php if($price_request): ?>
                                    <span class="price-request">Price on Request</span>
                                <?php else: ?>
                                    <span class="current-price">₹<?php echo number_format($row['sales_price']); ?></span>
                                    <?php if($row['mrp'] > $row['sales_price']): ?>
                                        <span class="original-price">₹<?php echo number_format($row['mrp']); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-actions">
                                <a href="product-details.php?id=<?php echo $row['id']; ?>" class="btn-details">View Details</a>
                                <a href="contact-us.php?product=<?php echo urlencode($row['title']); ?>" class="btn-enquire">
                                    Enquire Now
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #777;">No products found correctly.</div>';
            }
            ?>
        </div>

        <div class="load-more-container">
            <button id="loadMoreBtn" class="btn-load-more" onclick="loadMoreProducts()">
                <span>Load More Products</span>
                <i class="fas fa-spinner fa-spin" style="display: none;"></i>
            </button>
        </div>
    </div>
</section>

<script>
    let currentOffset = 8;
    const itemsPerPage = 8;
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const productsGrid = document.querySelector('.products-grid');

    async function loadMoreProducts() {
        const spinner = loadMoreBtn.querySelector('.fa-spinner');
        const btnText = loadMoreBtn.querySelector('span');

        // Show loading state
        loadMoreBtn.disabled = true;
        spinner.style.display = 'inline-block';
        btnText.style.opacity = '0.7';

        try {
            const response = await fetch(`ajax/load-more-products.php?offset=${currentOffset}`);
            const html = await response.text();

            if (html.trim() !== '') {
                // Append new products
                productsGrid.insertAdjacentHTML('beforeend', html);
                currentOffset += itemsPerPage;

                // Check if we should hide the button (simplistic check: if less than itemsPerPage were returned)
                // Better check would be a separate count, but this is usually enough for the first pass
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                if (tempDiv.querySelectorAll('.product-card').length < itemsPerPage) {
                    loadMoreBtn.style.display = 'none';
                }
            } else {
                // No more products
                loadMoreBtn.style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading more products:', error);
            alert('Failed to load more products. Please try again.');
        } finally {
            // Restore button state
            loadMoreBtn.disabled = false;
            spinner.style.display = 'none';
            btnText.style.opacity = '1';
        }
    }
</script>
