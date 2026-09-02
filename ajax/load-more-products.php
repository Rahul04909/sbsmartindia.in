<?php
/**
 * AJAX Handler for Loading More Products
 */
require_once __DIR__ . '/../database/db_config.php';
require_once __DIR__ . '/../includes/url_helper.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 8;

// Fetch next batch of 8 products
$sql = "SELECT p.*, b.name as brand_name 
        FROM products p 
        LEFT JOIN brands b ON p.brand_id = b.id 
        WHERE p.status = 1 
        ORDER BY p.id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$html = '';
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $image_path = !empty($row['featured_image']) ? $row['featured_image'] : 'assets/images/no-image.png';
        $brand = !empty($row['brand_name']) ? $row['brand_name'] : 'Generic';
        $price_request = $row['is_price_request'];
        $prod_url = getProductUrl($row);
        
        $html .= '
        <div class="product-card">
            <div class="product-image-box">
                <a href="' . $prod_url . '">
                    <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['title']) . '" class="product-image">
                </a>
                <div class="product-badges">';
                    if($row['discount_percentage'] > 0 && !$price_request) {
                        $html .= '<span class="badge-sale">' . round($row['discount_percentage']) . '% OFF</span>';
                    }
        $html .= '</div>
            </div>
            
            <div class="product-details">
                <div class="product-brand">' . htmlspecialchars($brand) . '</div>
                <h3 class="product-title" title="' . htmlspecialchars($row['title']) . '">
                    <a href="' . $prod_url . '" style="color: inherit; text-decoration: none;">' . htmlspecialchars($row['title']) . '</a>
                </h3>
                
                <div class="product-price-box">';
                    if($price_request) {
                        $html .= '<span class="price-request">Price on Request</span>';
                    } else {
                        $html .= '<span class="current-price">₹' . number_format($row['sales_price']) . '</span>';
                        if($row['mrp'] > $row['sales_price']) {
                            $html .= '<span class="original-price">₹' . number_format($row['mrp']) . '</span>';
                        }
                    }
        $html .= '</div>
                
                <div class="product-actions">
                    <a href="' . $prod_url . '" class="btn-details">View Details</a>
                    <a href="contact-us.php?product=' . urlencode($row['title']) . '" class="btn-enquire">
                        Enquire Now
                    </a>
                </div>
            </div>
        </div>';
    }
    echo $html;
} else {
    // Return empty string if no more products
    echo '';
}
?>
