<?php
session_start();
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
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
                $review_msg = "<div class='alert alert-success' style='color:green; margin-bottom:10px;'>Review submitted successfully!</div>";
            } else {
                $review_msg = "<div class='alert alert-danger' style='color:red; margin-bottom:10px;'>Error submitting review.</div>";
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

        <div class="product-details-container">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <a href="index.php">Home</a> &gt; 
                <a href="products.php">Products</a> &gt; 
                <span><?php echo htmlspecialchars($product['title']); ?></span>
            </div>

            <!-- Main Split Layout -->
            <div class="product-hero">
                <!-- Left: Image Gallery -->
                <div class="product-gallery-card">
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

                <!-- Right: Product Info & CTA -->
                <div class="product-info-col">
                    <div class="brand-badge"><?php echo htmlspecialchars($brand_name); ?></div>
                    <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
                    
                    <div class="rating-row">
                        <div class="stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $avg_rating ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="review-count">(<?php echo $total_reviews; ?> Reviews)</span>
                    </div>

                    <!-- Sticky CTA Card -->
                    <div class="cta-card">
                        <div class="price-section">
                            <?php if ($product['is_price_request']): ?>
                                <span class="price-request-text">Price on Request</span>
                            <?php else: ?>
                                <span class="price-label">Best Price:</span>
                                <div>
                                    <span class="price-currency">₹</span>
                                    <span class="price-large"><?php echo number_format($product['sales_price']); ?></span>
                                    <span class="price-mrp">MRP: ₹<?php echo number_format($product['mrp']); ?></span>
                                </div>
                                <?php if($product['mrp'] > $product['sales_price']): ?>
                                    <span class="price-save">You Save: ₹<?php echo number_format($product['mrp'] - $product['sales_price']); ?> (<?php echo round((($product['mrp'] - $product['sales_price']) / $product['mrp']) * 100); ?>%)</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="stock-dispatch-row">
                             <?php if ($product['stock'] > 0): ?>
                                <span class="stock-status in-stock"><i class="fa-solid fa-check-circle"></i> Ready to Dispatch</span>
                            <?php else: ?>
                                <span class="stock-status out-stock"><i class="fa-solid fa-clock"></i> Ships in 3-5 Weeks</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                             <?php if($product['is_price_request']): ?>
                                <a href="contact-us.php?product=<?php echo urlencode($product['title']); ?>" class="btn-primary">Request Quote</a>
                            <?php else: ?>
                                <a href="checkout.php?product_id=<?php echo $product['id']; ?>" class="btn-primary">Buy Now</a>
                                <a href="contact-us.php?product=<?php echo urlencode($product['title']); ?>" class="btn-secondary">Enquire Now</a>
                            <?php endif; ?>
                        </div>

                        <div class="trust-icons-row" style="border-top: 1px solid #eee; padding-top: 15px;">
                            <div class="trust-icon-item">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Secure</span>
                            </div>
                            <div class="trust-icon-item">
                                <i class="fa-solid fa-truck-fast"></i>
                                <span>Fast Delivery</span>
                            </div>
                            <div class="trust-icon-item">
                                <i class="fa-solid fa-headset"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Highlights Row -->
            <div class="highlights-row">
                <div class="highlight-card">
                    <i class="fa-solid fa-medal"></i>
                    <h4>100% Genuine Product</h4>
                </div>
                <div class="highlight-card">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <h4>Industrial Grade</h4>
                </div>
                <div class="highlight-card">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <h4>Quality Tested</h4>
                </div>
                <div class="highlight-card">
                    <i class="fa-solid fa-file-invoice"></i>
                    <h4>GST Invoice Available</h4>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="product-tabs-section">
                <div class="tabs-nav">
                    <button class="tab-btn active" onclick="openTab('desc')">Description</button>
                    <button class="tab-btn" onclick="openTab('specs')">Specifications</button>
                    <button class="tab-btn" onclick="openTab('reviews')">Reviews (<?php echo $total_reviews; ?>)</button>
                </div>

                <div class="tab-content-card">
                    <div id="desc" class="tab-pane active">
                        <h3>Product Description</h3>
                        <?php echo $product['description']; ?>
                    </div>
                    
                     <div id="specs" class="tab-pane">
                        <h3>Technical Specifications</h3>
                        <?php if(!empty($product['specifications'])): ?>
                            <?php echo $product['specifications']; ?>
                        <?php else: ?>
                            <p>Detailed specifications will be updated soon.</p>
                        <?php endif; ?>
                    </div>

                    <div id="reviews" class="tab-pane">
                        <h3>Customer Reviews</h3>
                        <div class="review-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                            <div class="review-form">
                                <h4 style="margin-bottom: 15px;">Write a Review</h4>
                                <?php echo $review_msg; ?>
                                <form method="POST" action="">
                                    <div style="margin-bottom: 15px;">
                                        <label style="display:block; margin-bottom:5px; font-weight:600;">Name</label>
                                        <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <label style="display:block; margin-bottom:5px; font-weight:600;">Email</label>
                                        <input type="email" name="email" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <label style="display:block; margin-bottom:5px; font-weight:600;">Rating</label>
                                        <select name="rating" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Very Good</option>
                                            <option value="3">3 - Good</option>
                                            <option value="2">2 - Fair</option>
                                            <option value="1">1 - Poor</option>
                                        </select>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <label style="display:block; margin-bottom:5px; font-weight:600;">Review</label>
                                        <textarea name="comment" rows="4" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;"></textarea>
                                    </div>
                                    <button type="submit" name="submit_review" class="btn-primary">Submit Review</button>
                                </form>
                            </div>
                            
                            <div class="reviews-list">
                                <?php if ($total_reviews > 0): ?>
                                    <?php foreach($reviews_data as $rev): ?>
                                        <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                                <strong><?php echo htmlspecialchars($rev['name']); ?></strong>
                                                <span style="color:#888; font-size:12px;"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></span>
                                            </div>
                                            <div style="color:#ffc107; font-size:12px; margin-bottom:8px;">
                                                <?php for($i=1; $i<=5; $i++) echo $i <= $rev['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                                            </div>
                                            <p style="font-size:14px; color:#555;"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No reviews yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Buy From Us -->
            <div class="why-buy-section">
                <h2 class="section-title">Why Buy From SB Smart?</h2>
                <div class="reasons-grid">
                    <div class="reason-item">
                        <div class="reason-icon"><i class="fa-solid fa-certificate"></i></div>
                        <div class="reason-title">Authorized Supplier</div>
                        <div class="reason-desc">Genuine products directly from manufacturers with warranty support.</div>
                    </div>
                     <div class="reason-item">
                        <div class="reason-icon"><i class="fa-solid fa-industry"></i></div>
                        <div class="reason-title">Industrial Expertise</div>
                        <div class="reason-desc">Over 10 years of experience in industrial automation and electricals.</div>
                    </div>
                     <div class="reason-item">
                        <div class="reason-icon"><i class="fa-solid fa-truck-fast"></i></div>
                        <div class="reason-title">PAN India Delivery</div>
                        <div class="reason-desc">Fast and secure shipping to all major industrial hubs across India.</div>
                    </div>
                     <div class="reason-item">
                        <div class="reason-icon"><i class="fa-solid fa-users-gear"></i></div>
                        <div class="reason-title">Dedicated Support</div>
                        <div class="reason-desc">Expert technical team available for product selection and support.</div>
                    </div>
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
        echo "<div class='container' style='padding: 100px; text-align: center;'><h2>Product not found</h2><a href='index.php' class='btn-primary'>Go Home</a></div>";
    }
} else {
    echo "<div class='container' style='padding: 100px; text-align: center;'><h2>Invalid Product ID</h2><a href='index.php' class='btn-primary'>Go Home</a></div>";
}

require_once 'includes/footer.php';
?>
</body>
</html>
