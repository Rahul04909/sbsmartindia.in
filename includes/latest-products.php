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
    </div>
</section>
