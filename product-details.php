<?php
require_once 'database/db_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - SB Smart India</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/hero.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="asstes/css/categories.css">
    <link rel="stylesheet" href="asstes/css/stats.css">
    <link rel="stylesheet" href="asstes/css/services.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/latest-products.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    <link rel="stylesheet" href="assets/css/product-details.css">
</head>
<body>

<?php
require_once 'includes/header.php';

// Get Product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Fetch Product Details
    $sql = "SELECT * FROM products WHERE id = $product_id AND status = 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Fetch Brand Name
        $brand_name = '';
        if ($product['brand_id']) {
            $brand_sql = "SELECT name FROM brands WHERE id = " . $product['brand_id'];
            $brand_res = $conn->query($brand_sql);
            if ($brand_res->num_rows > 0) {
                $brand_name = $brand_res->fetch_assoc()['name'];
            }
        }
        
        // Fetch Gallery Images
        $gallery = [];
        // Add featured image first
        if (!empty($product['featured_image'])) {
            $gallery[] = $product['featured_image'];
        }
        
        $gal_sql = "SELECT image_path FROM product_images WHERE product_id = $product_id ORDER BY id ASC";
        $gal_res = $conn->query($gal_sql);
        if ($gal_res->num_rows > 0) {
            while ($row = $gal_res->fetch_assoc()) {
                $gallery[] = $row['image_path'];
            }
        }
        
        // Handle Review Submission
        $review_msg = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
            $name = $conn->real_escape_string($_POST['name']);
            $email = $conn->real_escape_string($_POST['email']);
            $rating = intval($_POST['rating']);
            $comment = $conn->real_escape_string($_POST['comment']);
            
            $insert_review = "INSERT INTO product_reviews (product_id, name, email, rating, review_text) VALUES ($product_id, '$name', '$email', $rating, '$comment')";
            if ($conn->query($insert_review)) {
                $review_msg = "<div class='alert alert-success'>Review submitted successfully!</div>";
            } else {
                $review_msg = "<div class='alert alert-danger'>Error submitting review.</div>";
            }
        }
        
        // Fetch Reviews
        $reviews_sql = "SELECT * FROM product_reviews WHERE product_id = $product_id AND status = 1 ORDER BY created_at DESC";
        $reviews_res = $conn->query($reviews_sql);
        $avg_rating = 0;
        $total_reviews = $reviews_res->num_rows;
        $sum_rating = 0;
        $reviews_data = [];
        
        if ($total_reviews > 0) {
            while($rev = $reviews_res->fetch_assoc()) {
                $sum_rating += $rev['rating'];
                $reviews_data[] = $rev;
            }
            $avg_rating = round($sum_rating / $total_reviews, 1);
        }

        ?>

        
        <div class="product-details-container container">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs" style="margin-bottom: 20px; font-size: 14px; color: #555;">
                <a href="index.php">Home</a> &gt; 
                <a href="products.php">Products</a> &gt; 
                <span><?php echo htmlspecialchars($product['title']); ?></span>
            </div>

            <div class="product-layout">
                <!-- Left: Gallery -->
                <div class="product-gallery">
                    <div class="gallery-thumbs">
                        <?php foreach($gallery as $index => $img): ?>
                            <div class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this, '<?php echo $img; ?>')">
                                <img src="<?php echo $img; ?>" alt="Product Thumbnail">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="gallery-main">
                        <img id="mainImage" src="<?php echo !empty($gallery) ? $gallery[0] : 'assets/images/no-image.png'; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </div>
                </div>

                <!-- Right: Info -->
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['title']); ?></h1>
                    
                    <div class="product-rating">
                        <div class="rating-stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $avg_rating ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="rating-count"><?php echo $total_reviews; ?> ratings</span>
                    </div>
                    
                    <div class="product-meta">
                        <span>Brand: <strong><?php echo htmlspecialchars($brand_name); ?></strong></span>
                        <span>Model: <strong><?php echo htmlspecialchars($product['model_number']); ?></strong></span>
                    </div>

                    <div class="price-box">
                        <?php if ($product['is_price_request']): ?>
                            <span class="price-request-large">Price on Request</span>
                            <div class="save-amount">Contact us for best pricing</div>
                        <?php else: ?>
                            <div style="display: flex; align-items: baseline;">
                                <span style="font-size: 14px; vertical-align: top; margin-right: 2px;">₹</span>
                                <span class="current-price-large"><?php echo number_format($product['sales_price']); ?></span>
                                <span class="mrp-large">M.R.P.: ₹<?php echo number_format($product['mrp']); ?></span>
                            </div>
                            <?php if($product['mrp'] > $product['sales_price']): ?>
                                <div class="save-amount">
                                    You Save: ₹<?php echo number_format($product['mrp'] - $product['sales_price']); ?> 
                                    (<?php echo round((($product['mrp'] - $product['sales_price']) / $product['mrp']) * 100); ?>%)
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="dispatch-info" style="margin-bottom: 20px; font-size: 15px; font-weight: 500;">
                        <?php if ($product['stock_status'] == 'In Stock'): ?>
                            <span style="color: #007600;"><i class="fa-solid fa-check"></i> Ready to Dispatch</span>
                        <?php else: ?>
                            <span style="color: #b12704;"><i class="fa-solid fa-clock"></i> Stock will be available as per OEM Lead time 3 to 5 Weeks</span>
                        <?php endif; ?>
                    </div>

                    <div class="action-buttons">
                        <?php if($product['is_price_request']): ?>
                            <a href="contact-us.php?product=<?php echo urlencode($product['title']); ?>" class="btn-enquire">Request Quote</a>
                        <?php else: ?>
                            <button class="btn-buy">Buy Now</button>
                            <a href="contact-us.php?product=<?php echo urlencode($product['title']); ?>" class="btn-enquire">Enquire</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-trust-badges">
                        <div class="trust-item">
                            <div class="icon-box"><i class="fa-solid fa-shield-halved"></i></div>
                            <span>Secure Transaction</span>
                        </div>
                        <div class="trust-item">
                            <div class="icon-box"><i class="fa-solid fa-truck-fast"></i></div>
                            <span>Fast Delivery</span>
                        </div>
                        <div class="trust-item">
                            <div class="icon-box"><i class="fa-solid fa-headset"></i></div>
                            <span>Support 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="product-tabs">
                <div class="tab-headers">
                    <button class="tab-btn active" onclick="openTab('desc')">Description</button>
                    <button class="tab-btn" onclick="openTab('reviews')">Reviews (<?php echo $total_reviews; ?>)</button>
                </div>
                
                <div id="desc" class="tab-pane active">
                    <?php echo $product['description']; // Assumed safe HTML from Summernote ?>
                </div>
                
                <div id="reviews" class="tab-pane">
                    <div class="review-layout" style="display: flex; gap: 40px; flex-wrap: wrap;">
                        <div class="review-form-container" style="flex: 1; min-width: 300px;">
                            <h3>Write a Review</h3>
                            <?php echo $review_msg; ?>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label>Your Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Your Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Rating</label>
                                    <select name="rating" class="form-control">
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Very Good</option>
                                        <option value="3">3 - Good</option>
                                        <option value="2">2 - Fair</option>
                                        <option value="1">1 - Poor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Your Review</label>
                                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" name="submit_review" class="submit-btn">Submit Review</button>
                            </form>
                        </div>
                        
                        <div class="review-list" style="flex: 2; min-width: 300px;">
                            <h3>Customer Reviews</h3>
                            <?php if ($total_reviews > 0): ?>
                                <?php foreach($reviews_data as $rev): ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-name"><?php echo htmlspecialchars($rev['name']); ?></div>
                                            <div class="review-stars">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $rev['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                                }
                                                ?>
                                            </div>
                                            <div class="review-date"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></div>
                                        </div>
                                        <div class="review-text">
                                            <?php echo nl2br(htmlspecialchars($rev['review_text'])); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No reviews yet. Be the first to review this product!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products (Simple Implementation: Same Brand) -->
            <div class="related-products" style="margin-top: 60px;">
                <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">Related Products</h2>
                <div class="products-grid">
                    <?php
                    $related_sql = "SELECT * FROM products WHERE brand_id = " . ($product['brand_id'] ? $product['brand_id'] : 0) . " AND id != $product_id AND status = 1 LIMIT 4";
                    $related_res = $conn->query($related_sql);
                    
                    if ($related_res && $related_res->num_rows > 0) {
                        while($rel = $related_res->fetch_assoc()) {
                            $rel_img = !empty($rel['featured_image']) ? $rel['featured_image'] : 'assets/images/no-image.png';
                            ?>
                            <div class="product-card">
                                <Link href="product-details.php?id=<?php echo $rel['id']; ?>" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                                    <div class="product-image-box">
                                        <img src="<?php echo $rel_img; ?>" class="product-image" alt="<?php echo htmlspecialchars($rel['title']); ?>">
                                    </div>
                                    <div class="product-details">
                                        <div class="product-title" style="min-height: auto; margin-bottom: 5px;"><?php echo htmlspecialchars($rel['title']); ?></div>
                                        <div class="current-price" style="font-size: 16px;">
                                            <?php echo $rel['is_price_request'] ? 'Price on Request' : '₹' . number_format($rel['sales_price']); ?>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No related products found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
        function changeImage(element, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function openTab(tabName) {
            var i;
            var x = document.getElementsByClassName("tab-pane");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
                x[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add("active");
            
            var tabs = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }
            event.currentTarget.classList.add("active");
        }
        </script>

        <?php
    } else {
        echo "<div class='container' style='padding: 50px; text-align: center;'><h2>Product not found</h2><a href='index.php' class='btn-buy'>Go Home</a></div>";
    }
} else {
    echo "<div class='container' style='padding: 50px; text-align: center;'><h2>Invalid Product ID</h2><a href='index.php' class='btn-buy'>Go Home</a></div>";
}

require_once 'includes/footer.php';
?>
</body>
</html>
?>
