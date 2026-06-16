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
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
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
    <style>
        .brand-logo-badge {
            display: inline-block;
            margin-bottom: 15px;
            background: transparent;
            padding: 0;
            border: none;
            box-shadow: none;
        }
        .brand-logo-badge img {
            max-height: 45px;
            max-width: 140px;
            object-fit: contain;
            display: block;
        }
        .brand-name-fallback {
            display: inline-block;
            background-color: #eef4ff;
            color: #004aad;
            font-size: 13px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
    </style>
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
        
        // Fetch Brand details
        $brand_name = '';
        $brand_logo = '';
        if ($product['brand_id']) {
            $brand_sql = "SELECT name, logo FROM brands WHERE id = " . $product['brand_id'];
            $brand_res = $conn->query($brand_sql);
            if ($brand_res && $brand_res->num_rows > 0) {
                $brand_data = $brand_res->fetch_assoc();
                $brand_name = $brand_data['name'];
                $brand_logo = $brand_data['logo'];
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
                    <?php if ($brand_logo && file_exists($brand_logo)): ?>
                        <div class="brand-logo-badge">
                            <img src="<?php echo htmlspecialchars($brand_logo); ?>" alt="<?php echo htmlspecialchars($brand_name); ?> Logo">
                        </div>
                    <?php elseif (!empty($brand_name)): ?>
                        <div class="brand-name-fallback"><?php echo htmlspecialchars($brand_name); ?></div>
                    <?php endif; ?>
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
                                <?php 
                                    $sales_price = $product['sales_price'];
                                    $gst_amount = $sales_price * max(1, intval($product['moq'])) * 0.18;
                                    $total_with_gst = ($sales_price * max(1, intval($product['moq']))) + $gst_amount;
                                ?>
                                <span class="price-label">Best Price (Excl. GST):</span>
                                <div>
                                    <span class="price-currency">₹</span>
                                    <span class="price-large"><?php echo number_format($sales_price); ?></span>
                                    <span class="price-unit">/ <?php echo htmlspecialchars($product['unit']); ?></span>
                                    <span class="price-mrp" style="margin-left: 10px;">MRP: ₹<?php echo number_format($product['mrp']); ?> / <?php echo htmlspecialchars($product['unit']); ?></span>
                                </div>
                                <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #004aad;">
                                    <div style="font-size: 14px; color: #666; margin-bottom: 4px;">
                                        GST (18%): <strong>₹<span id="gstAmountVal"><?php echo number_format($gst_amount); ?></span></strong>
                                    </div>
                                    <div style="font-size: 18px; color: #333; font-weight: 700;">
                                        Total Price (Incl. GST): ₹<span id="totalWithGstVal"><?php echo number_format($total_with_gst); ?></span>
                                    </div>
                                </div>
                                <?php if($product['mrp'] > $sales_price): ?>
                                    <div style="margin-top: 8px;">
                                        <span class="price-save">You Save: ₹<?php echo number_format($product['mrp'] - $sales_price); ?> (<?php echo round((($product['mrp'] - $sales_price) / $product['mrp']) * 100); ?>%)</span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <div class="stock-dispatch-row">
                             <?php if ($product['is_price_request']): ?>
                                <span class="stock-status in-stock"><i class="fa-solid fa-clock"></i> Available as per OEM Lead Time</span>
                            <?php elseif ($product['stock'] > 0): ?>
                                <span class="stock-status in-stock"><i class="fa-solid fa-check-circle"></i> Ready to Dispatch</span>
                            <?php else: ?>
                                <span class="stock-status out-stock"><i class="fa-solid fa-clock"></i> Available as per OEM Lead Time</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!$product['is_price_request']): ?>
                            <div class="quantity-selector-section" style="margin: 15px 0 20px 0; padding: 15px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <span style="font-weight: 600; color: #444; font-size: 15px;">Quantity:</span>
                                        <div style="display: flex; align-items: center; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; background: #fff; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                                            <button type="button" onclick="adjustQty(-1)" style="border: none; background: #f1f3f6; width: 35px; height: 35px; cursor: pointer; font-size: 18px; font-weight: 600; color: #444; transition: background 0.2s;">-</button>
                                            <input type="number" id="purchase_qty" name="quantity" value="<?php echo max(1, intval($product['moq'])); ?>" min="<?php echo max(1, intval($product['moq'])); ?>" style="width: 55px; height: 35px; text-align: center; border: none; font-size: 16px; font-weight: 700; color: #333; -moz-appearance: textfield; padding: 0;" readonly>
                                            <button type="button" onclick="adjustQty(1)" style="border: none; background: #f1f3f6; width: 35px; height: 35px; cursor: pointer; font-size: 18px; font-weight: 600; color: #444; transition: background 0.2s;">+</button>
                                        </div>
                                        <span style="font-weight: 600; color: #666; font-size: 14px; text-transform: uppercase;"><?php echo htmlspecialchars($product['unit']); ?></span>
                                    </div>
                                    <?php if (intval($product['moq']) > 1): ?>
                                        <div style="font-size: 13px; color: #dc3545; font-weight: 600; display: flex; align-items: center; gap: 5px; background: #fff5f5; padding: 6px 12px; border-radius: 20px; border: 1px solid #ffd8d8;">
                                            <i class="fa-solid fa-circle-info"></i> Minimum Order: <?php echo intval($product['moq']) . ' ' . htmlspecialchars($product['unit']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="action-buttons">
                             <?php if($product['is_price_request']): ?>
                                <button type="button" class="btn-primary" onclick="openQuoteModal(<?php echo $product['id']; ?>, '<?php echo addslashes($product['title']); ?>')">Request Quote</button>
                            <?php else: ?>
                                <button type="button" class="btn-cart" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                                <button type="button" class="btn-primary" onclick="buyNow(<?php echo $product['id']; ?>)">Buy Now</button>
                                <button type="button" class="btn-secondary" onclick="openEnquiryModal(<?php echo $product['id']; ?>, '<?php echo addslashes($product['title']); ?>')">Enquire Now</button>
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

    function addToCart(productId) {
        var qtyInput = document.getElementById('purchase_qty');
        var qty = qtyInput ? parseInt(qtyInput.value) : 1;
        var btn = document.querySelector('.btn-cart');
        var originalText = btn.innerText;
        btn.innerText = "Adding...";
        btn.disabled = true;

        $.ajax({
            url: 'cart_handler.php',
            type: 'POST',
            data: { action: 'add', product_id: productId, quantity: qty },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert('Product added to cart!');
                    updateCartCount(); // Function from header
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred.');
            },
            complete: function() {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    }

    const salesPrice = <?php echo isset($sales_price) ? (float)$sales_price : 0; ?>;
    const moq = <?php echo isset($product['moq']) ? max(1, intval($product['moq'])) : 1; ?>;
    
    function adjustQty(change) {
        const input = document.getElementById('purchase_qty');
        if (!input) return;
        let currentVal = parseInt(input.value) || moq;
        let newVal = currentVal + change;
        if (newVal < moq) {
            alert('Minimum order quantity for this product is ' + moq + ' <?php echo isset($product['unit']) ? htmlspecialchars($product['unit']) : 'nos'; ?>.');
            return;
        }
        input.value = newVal;
        updateDynamicPrices(newVal);
    }
    
    function updateDynamicPrices(qty) {
        const subtotal = salesPrice * qty;
        const gst = subtotal * 0.18;
        const total = subtotal + gst;
        
        const gstEl = document.getElementById('gstAmountVal');
        const totalEl = document.getElementById('totalWithGstVal');
        if (gstEl) gstEl.innerText = formatCurrency(gst);
        if (totalEl) totalEl.innerText = formatCurrency(total);
    }
    
    function formatCurrency(num) {
        return new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(num);
    }

    function buyNow(productId) {
        const qtyInput = document.getElementById('purchase_qty');
        const qty = qtyInput ? parseInt(qtyInput.value) : 1;
        window.location.href = 'checkout.php?product_id=' + productId + '&quantity=' + qty;
    }
    </script>
    <?php
    } else {
        // Redirect to 404 if product not found or inactive
        header("Location: 404.php");
        exit();
    }
} else {
    // Redirect to 404 if invalid ID
    header("Location: 404.php");
    exit();
}
?>


<!-- Include Quote Modal -->
<?php include 'components/quote-modal.php'; ?>
<!-- Include Enquiry Modal -->
<?php include 'components/enquiry-modal.php'; ?>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
