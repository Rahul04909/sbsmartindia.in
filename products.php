<?php
session_start();
require_once 'database/db_config.php';
$page_title = "Shop - Products | SB Smart India";

try {
    // 1. Initial Logic for Filters
    // Fetch Brands
    $brands_sql = "SELECT * FROM brands ORDER BY name ASC";
    $brands_res = $conn->query($brands_sql);
    if(!$brands_res) throw new Exception("Brands Query Failed: " . $conn->error);

    // Fetch Categories with Sub-Categories
    $cats_sql = "SELECT * FROM product_categories ORDER BY name ASC";
    $cats_res = $conn->query($cats_sql);
    if(!$cats_res) throw new Exception("Categories Query Failed: " . $conn->error);

    $categories = [];
    if ($cats_res->num_rows > 0) {
        while($cat = $cats_res->fetch_assoc()) {
            $cat_id = $cat['id'];
             // Fetch Sub-Categories
            $sub_sql = "SELECT * FROM product_sub_categories WHERE category_id = $cat_id ORDER BY name ASC";
            $sub_res = $conn->query($sub_sql);
            $subs = [];
            while($sub = $sub_res->fetch_assoc()) {
                $subs[] = $sub;
            }
            $cat['sub_categories'] = $subs;
            $categories[] = $cat;
        }
    }

    // 2. Build Query based on GET params
    $where_clauses = ["1=1"];

    // Filter by Brand
    if (isset($_GET['brand']) && !empty($_GET['brand'])) {
        $brand_filter = $_GET['brand'];
        if(is_array($brand_filter)) {
            $brand_ids = implode(',', array_map('intval', $brand_filter)); // Sanitize
            $where_clauses[] = "brand_id IN ($brand_ids)";
        } else {
            $brand_id = intval($brand_filter);
            $where_clauses[] = "brand_id = $brand_id";
        }
    }

    // Filter by Category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $cat_id = intval($_GET['category']);
        $where_clauses[] = "category_id = $cat_id";
    }

    // Filter by Sub-Category
    if (isset($_GET['sub_category']) && !empty($_GET['sub_category'])) {
        $sub_cat_id = intval($_GET['sub_category']);
        $where_clauses[] = "sub_category_id = $sub_cat_id";
    }

    // Filter by Price
    $min_price_query = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
    $max_price_query = isset($_GET['max_price']) ? intval($_GET['max_price']) : 0;

    if ($max_price_query > 0) {
         $where_clauses[] = "sales_price BETWEEN $min_price_query AND $max_price_query";
    }

    // Search Query
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $search = $conn->real_escape_string($_GET['q']);
        $where_clauses[] = "(title LIKE '%$search%' OR description LIKE '%$search%' OR meta_keywords LIKE '%$search%')";
    }

    // 3. Sorting
    $sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
    $order_by = "ORDER BY created_at DESC";

    switch ($sort_option) {
        case 'price_asc': $order_by = "ORDER BY sales_price ASC"; break;
        case 'price_desc': $order_by = "ORDER BY sales_price DESC"; break;
        case 'name_asc': $order_by = "ORDER BY title ASC"; break;
        default: $order_by = "ORDER BY created_at DESC"; break;
    }

    // 4. Pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;

    // Combine Where Clauses
    $where_sql = implode(' AND ', $where_clauses);

    // Count Total Products
    $count_sql = "SELECT COUNT(*) as total FROM products WHERE $where_sql";
    $count_res = $conn->query($count_sql);
    if(!$count_res) throw new Exception("Count Query Failed: " . $conn->error);
    $total_products = $count_res->fetch_assoc()['total'];
    $total_pages = ceil($total_products / $limit);

    // Fetch Products
    $products_sql = "SELECT * FROM products WHERE $where_sql $order_by LIMIT $offset, $limit";
    $products_res = $conn->query($products_sql);
    if(!$products_res) throw new Exception("Products Query Failed: " . $conn->error);

} catch (Exception $e) {
    die("<div style='padding:50px; text-align:center;'><h1>Error</h1><p>" . $e->getMessage() . "</p></div>");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
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
    <link rel="stylesheet" href="assets/css/shop.css">
    <style>
        .filter-header-content {
            margin-bottom: 40px;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #eee;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<!-- Shop Header / Breadcrumb -->
<div class="shop-header-section">
    <div class="container">
        <h1>Shop Products</h1>
        <div class="breadcrumbs">
            <a href="index.php">Home</a> &gt; <span>Shop</span>
        </div>
    </div>
</div>

<div class="container shop-layout">
    <!-- Sidebar -->
    <button class="mobile-filter-toggle" id="mobileFilterBtn"><i class="fa-solid fa-filter"></i> Filters</button>
    <div class="shop-sidebar" id="shopSidebar">
        <div class="sidebar-header-mobile">
            <h3>Filters</h3>
            <button class="close-sidebar" id="closeSidebar">&times;</button>
        </div>

        <form action="products.php" method="GET" id="filterForm">
            <!-- Search Carry Over -->
            <?php if(isset($_GET['q'])): ?>
                <input type="hidden" name="q" value="<?php echo htmlspecialchars($_GET['q']); ?>">
            <?php endif; ?>

            <!-- Categories Filter -->
            <div class="filter-group">
                <h3>Categories</h3>
                <div class="filter-content categories-list">
                    <ul>
                        <li>
                            <a href="products.php" class="<?php echo !isset($_GET['category']) && !isset($_GET['sub_category']) ? 'active' : ''; ?>">All Categories</a>
                        </li>
                        <?php foreach($categories as $cat): ?>
                            <li class="has-sub">
                                <div class="cat-link-wrap">
                                    <a href="products.php?category=<?php echo $cat['id']; ?>" class="<?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'active' : ''; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </a>
                                     <?php if(!empty($cat['sub_categories'])): ?>
                                        <span class="toggle-sub"><i class="fa-solid fa-chevron-down"></i></span>
                                     <?php endif; ?>
                                </div>
                                
                                <?php if(!empty($cat['sub_categories'])): ?>
                                    <ul class="sub-cat-list" style="<?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'display:block' : ''; ?>">
                                        <?php foreach($cat['sub_categories'] as $sub): ?>
                                            <li>
                                                <a href="products.php?sub_category=<?php echo $sub['id']; ?>" class="<?php echo (isset($_GET['sub_category']) && $_GET['sub_category'] == $sub['id']) ? 'active' : ''; ?>">
                                                    <?php echo htmlspecialchars($sub['name']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Price Filter -->
            <div class="filter-group">
                <h3>Price Range</h3>
                <div class="filter-content price-filter">
                     <div class="price-inputs">
                         <input type="number" name="min_price" placeholder="Min" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
                         <span>-</span>
                         <input type="number" name="max_price" placeholder="Max" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
                     </div>
                     <button type="submit" class="btn-filter-apply">Apply</button>
                </div>
            </div>

            <!-- Brands Filter -->
            <div class="filter-group">
                <h3>Brands</h3>
                <div class="filter-content checkbox-list">
                    <?php 
                    $selected_brands = isset($_GET['brand']) ? (is_array($_GET['brand']) ? $_GET['brand'] : [$_GET['brand']]) : [];
                    if($brands_res->num_rows > 0):
                        while($brand = $brands_res->fetch_assoc()):
                            $checked = in_array($brand['id'], $selected_brands) ? 'checked' : '';
                    ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="brand[]" value="<?php echo $brand['id']; ?>" <?php echo $checked; ?> onchange="this.form.submit()">
                            <span class="checkmark"></span>
                            <?php echo htmlspecialchars($brand['name']); ?>
                        </label>
                    <?php 
                        endwhile;
                    endif;
                    ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="shop-content">
        <!-- Top Bar -->
        <div class="shop-top-bar">
            <div class="results-count">
                Showing <strong><?php echo $total_products > 0 ? $offset + 1 : 0; ?>-<?php echo min($offset + $limit, $total_products); ?></strong> of <strong><?php echo $total_products; ?></strong> results
            </div>
            <div class="sort-options">
                <form id="sortForm" method="GET">
                     <!-- Preserve other params -->
                     <?php foreach ($_GET as $key => $val): 
                        if($key == 'sort' || $key == 'page') continue;
                        if(is_array($val)) {
                            foreach($val as $v) {
                                echo '<input type="hidden" name="'.$key.'[]" value="'.htmlspecialchars($v).'">';
                            }
                        } else {
                            echo '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($val).'">';
                        }
                     endforeach; ?>
                    
                    <select name="sort" onchange="document.getElementById('sortForm').submit()">
                        <option value="newest" <?php echo $sort_option == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_asc" <?php echo $sort_option == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo $sort_option == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="name_asc" <?php echo $sort_option == 'name_asc' ? 'selected' : ''; ?>>Name: A-Z</option>
                    </select>
                </form>
            </div>
        </div>

        <?php 
        // Special content for Flender (Brand check by name)
        $show_flender_content = false;
        if (isset($_GET['brand'])) {
            $check_brand_ids = is_array($_GET['brand']) ? array_map('intval', $_GET['brand']) : [intval($_GET['brand'])];
            if (!empty($check_brand_ids)) {
                $ids_str = implode(',', $check_brand_ids);
                $b_res = $conn->query("SELECT name FROM brands WHERE id IN ($ids_str)");
                if ($b_res) {
                    while($b_row = $b_res->fetch_assoc()) {
                        if (stripos($b_row['name'], 'Flender') !== false) {
                            $show_flender_content = true;
                            break;
                        }
                    }
                }
            }
        }

        if ($show_flender_content): 
        ?>
            <div class="filter-header-content" style="margin-top: 20px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="font-weight: 700; color: #333; margin-bottom: 15px;">Flender – Engineered Drive Technology</h2>
                    <div style="width: 80px; height: 4px; background: #004aad; margin: 0 auto;"></div>
                </div>

                <div style="font-size: 1.1rem; line-height: 1.7; color: #555; margin-bottom: 30px;">
                    <p style="margin-bottom: 20px;">
                        Flender is a global leader in mechanical and electrical drive technology, known worldwide for its highly reliable, efficient, and application-engineered gearbox solutions. With decades of engineering excellence, Flender products are designed to perform in the most demanding industrial environments across sectors such as cement, steel, power, mining, oil & gas, material handling, and infrastructure.
                    </p>
                    <p style="margin-bottom: 20px;">
                        <strong>Flender gearboxes are not standard off-the-shelf products.</strong>
                        They are application-specific solutions, engineered based on load conditions, duty cycles, mounting positions, environmental factors, and lifecycle expectations — ensuring maximum uptime, efficiency, and long service life.
                    </p>
                </div>

                <div style="background: #f1f3f6; padding: 25px; border-radius: 6px; margin-bottom: 30px; border-left: 5px solid #004aad;">
                    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 10px; color: #333;"><i class="fas fa-check-circle" style="color: #004aad; margin-right: 10px;"></i>Authorized Partner</h3>
                    <p style="margin: 0; color: #444;">
                        <strong>S.B. Syscon Pvt. Ltd.</strong> is an Authorized Partner for Flender Gearboxes.
                        All Flender solutions offered through SBS are designed and validated with direct support from the Flender backend technical team, ensuring correct selection, compliance with application requirements, and long-term operational reliability.
                    </p>
                </div>

                <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 20px; border-radius: 6px; margin-bottom: 40px; display: flex; gap: 20px; align-items: flex-start;">
                    <i class="fas fa-info-circle" style="color: #856404; font-size: 1.5rem; margin-top: 3px;"></i>
                    <div>
                        <h4 style="margin: 0 0 10px 0; color: #856404; font-weight: 700;">Assisted Support Required</h4>
                        <p style="margin: 0; color: #856404;">
                            Due to the engineering-driven nature of Flender products, we require detailed technical inputs before recommending or quoting a solution. Hence, Flender enquiries are handled through Assisted Support rather than direct online checkout.
                        </p>
                    </div>
                </div>

                <div style="text-align: center; border-top: 1px solid #eee; padding-top: 30px;">
                    <h3 style="font-weight: 700; margin-bottom: 25px;">Connect with our Flender Experts</h3>
                    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; margin-bottom: 25px;">
                        <a href="mailto:marcom.sbsyscon@gmail.com" class="btn-view" style="padding: 12px 25px; background: #333; color: #fff; width: auto; display: inline-block;">
                            <i class="fas fa-envelope" style="margin-right: 8px;"></i> marcom.sbsyscon@gmail.com
                        </a>
                        <a href="tel:+917506943307" class="btn-view" style="padding: 12px 25px; background: #004aad; color: #fff; width: auto; display: inline-block;">
                            <i class="fas fa-phone" style="margin-right: 8px;"></i> +91-75069-43307
                        </a>
                    </div>
                    <p style="color: #777; font-size: 0.9rem;">Our team will work closely with Flender’s technical experts to study your application and deliver the most optimized, reliable, and future-ready drive solution.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Products -->
        <div class="products-grid">
            <?php if ($products_res->num_rows > 0): ?>
                <?php while($prod = $products_res->fetch_assoc()): 
                    // Calculate discount if any
                    $has_discount = ($prod['sales_price'] < $prod['mrp'] && $prod['sales_price'] > 0);
                    $discount_percent = 0;
                    if($has_discount && $prod['mrp'] > 0) {
                        $discount_percent = round((($prod['mrp'] - $prod['sales_price']) / $prod['mrp']) * 100);
                    }
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="product-details.php?id=<?php echo $prod['id']; ?>">
                            <?php if(!empty($prod['featured_image']) && file_exists($prod['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($prod['featured_image']); ?>" alt="<?php echo htmlspecialchars($prod['title']); ?>">
                            <?php else: ?>
                                <img src="assets/images/placeholder.jpg" alt="No Image">
                            <?php endif; ?>
                        </a>
                        <?php if($has_discount): ?>
                            <span class="badge-discount"><?php echo $discount_percent; ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <?php 
                        // Brand Name Fetch (Optimized: could join in main query but individual fetch is okay for pagination limits)
                        $b_name_disp = "Brand";
                        if($prod['brand_id']) {
                             $b_res = $conn->query("SELECT name FROM brands WHERE id=".$prod['brand_id']);
                             if($b_res->num_rows > 0) $b_name_disp = $b_res->fetch_assoc()['name'];
                        }
                        ?>
                        <div class="product-brand"><?php echo htmlspecialchars($b_name_disp); ?></div>
                        <h3 class="product-title">
                            <a href="product-details.php?id=<?php echo $prod['id']; ?>"><?php echo htmlspecialchars($prod['title']); ?></a>
                        </h3>
                        
                        <div class="product-price">
                             <?php if($prod['sales_price'] > 0): ?>
                                <span class="current-price">₹<?php echo number_format($prod['sales_price'], 2); ?></span>
                                <?php if($has_discount): ?>
                                    <span class="mrp-price">₹<?php echo number_format($prod['mrp'], 2); ?></span>
                                <?php endif; ?>
                             <?php else: ?>
                                <a href="contact-us.php?inquiry=<?php echo urlencode($prod['title']); ?>" class="btn-request-price">Request Price</a>
                             <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <a href="product-details.php?id=<?php echo $prod['id']; ?>" class="btn-view">View Details</a>
                            <!-- Add to Cart can be added via AJAX here later -->
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products-found">
                    <i class="fa-solid fa-box-open"></i>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters or search query.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination-wrapper">
            <div class="pagination">
                <?php 
                // Reuse GET params for pagination links
                $query_params = $_GET;
                unset($query_params['page']);
                $base_url = 'products.php?' . http_build_query($query_params);
                $conn_char = !empty($query_params) ? '&' : '';
                
                // Pagination Range logic
                $range = 2; // Number of pages either side of current
                $show_first_last = true;
                ?>

                <!-- Prev Button -->
                <a href="<?php echo ($page > 1) ? $base_url . $conn_char . 'page=' . ($page - 1) : 'javascript:void(0)'; ?>" 
                   class="page-link prev <?php echo ($page <= 1) ? 'disabled' : ''; ?>" aria-label="Previous Page">
                    <i class="fa-solid fa-angle-left"></i>
                </a>

                <?php 
                // First Page
                if ($show_first_last && $page > ($range + 1)) {
                    echo '<a href="'.$base_url . $conn_char . 'page=1" class="page-link">1</a>';
                    if ($page > ($range + 2)) echo '<span class="page-dots">...</span>';
                }

                // Page numbers
                for ($i = max(1, $page - $range); $i <= min($total_pages, $page + $range); $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<a href="'.$base_url . $conn_char . 'page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
                }

                // Last Page
                if ($show_first_last && $page < ($total_pages - $range)) {
                    if ($page < ($total_pages - $range - 1)) echo '<span class="page-dots">...</span>';
                    echo '<a href="'.$base_url . $conn_char . 'page='.$total_pages.'" class="page-link">'.$total_pages.'</a>';
                }
                ?>

                <!-- Next Button -->
                <a href="<?php echo ($page < $total_pages) ? $base_url . $conn_char . 'page=' . ($page + 1) : 'javascript:void(0)'; ?>" 
                   class="page-link next <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>" aria-label="Next Page">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Sidebar Toggle
    document.getElementById('mobileFilterBtn').addEventListener('click', function() {
        document.getElementById('shopSidebar').classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    });

    document.getElementById('closeSidebar').addEventListener('click', function() {
        document.getElementById('shopSidebar').classList.remove('active');
        document.body.style.overflow = '';
    });

    // Sub-Category Toggle
    document.querySelectorAll('.toggle-sub').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Stop link click
            var subList = this.parentElement.nextElementSibling;
            if(subList) {
                if(subList.style.display === 'block') {
                    subList.style.display = 'none';
                    this.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';
                } else {
                    subList.style.display = 'block';
                    this.innerHTML = '<i class="fa-solid fa-chevron-up"></i>';
                }
            }
        });
    });
</script>

</body>
</html>
