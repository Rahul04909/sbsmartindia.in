<?php
session_start();
require_once 'database/db_config.php';

// brand.php — Displays a paginated list of products filtered by brand or subcategory.

// --- Input Validation ---
$filterId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// We support 'brand' or 'sub' (subcategory)
$filterType = isset($_GET['type']) ? strtolower(trim($_GET['type'])) : 'brand';
if ($filterType === 'cat')
    $filterType = 'brand'; // redirect cat -> brand logic 
$filterType = in_array($filterType, ['brand', 'sub'], true) ? $filterType : 'brand';

if ($filterId <= 0) {
    header("Location: products.php");
    exit();
}

// --- Configuration ---
$limit = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$offset = ($page - 1) * $limit;

// Set filter column based on type
$filter_column = ($filterType === 'sub') ? 'sub_category_id' : 'brand_id';
$where_sql = "status = 1 AND {$filter_column} = $filterId";

$filter_name = 'Products';
$total_products = 0;
$total_pages = 1;

// Fetch filter name (brand/subcategory)
if ($filterType === 'sub') {
    $name_res = $conn->query("SELECT name FROM product_sub_categories WHERE id = $filterId LIMIT 1");
} else {
    $name_res = $conn->query("SELECT name FROM brands WHERE id = $filterId LIMIT 1");
}

if ($name_res && $name_res->num_rows > 0) {
    $filter_name = $name_res->fetch_assoc()['name'];
}

// Count total products
$count_sql = "SELECT COUNT(*) AS total FROM products WHERE $where_sql";
$count_res = $conn->query($count_sql);
$total_products = (int) ($count_res->fetch_assoc()['total'] ?? 0);
$total_pages = max(1, (int) ceil($total_products / $limit));

// Fetch Products
$products = [];
if ($total_products > 0) {
    $sql = "SELECT * FROM products WHERE $where_sql ORDER BY title ASC LIMIT $offset, $limit";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$page_title = "$filter_name - SB Smart India";
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <?php require_once 'includes/header.php'; ?>

    <!-- Header / Breadcrumb -->
    <div class="shop-header-section">
        <div class="container">
            <h1><?php echo htmlspecialchars($filter_name); ?></h1>
            <div class="breadcrumbs">
                <a href="index.php">Home</a> &gt; <a href="products.php">Shop</a> &gt;
                <span><?php echo htmlspecialchars($filter_name); ?></span>
            </div>
        </div>
    </div>

    <div class="container shop-layout" style="display: block;">

        <?php
        // Special content for Flender (Brand ID check by name)
        $is_flender = false;
        if ($filterType === 'brand' && $filterId > 0) {
            $check_sql = "SELECT name FROM brands WHERE id = $filterId";
            $check_res = $conn->query($check_sql);
            if ($check_res && $check_res->num_rows > 0) {
                $brand_data = $check_res->fetch_assoc();
                if (stripos($brand_data['name'], 'Flender') !== false) {
                    $is_flender = true;
                }
            }
        }

        if ($is_flender):
            ?>
            <div class="filter-header-content">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="font-weight: 700; color: #333; margin-bottom: 15px;">Flender – Engineered Drive Technology
                    </h2>
                    <div style="width: 80px; height: 4px; background: #004aad; margin: 0 auto;"></div>
                </div>

                <div style="font-size: 1.1rem; line-height: 1.7; color: #555; margin-bottom: 30px;">
                    <p style="margin-bottom: 20px;">
                        Flender is a global leader in mechanical and electrical drive technology, known worldwide for its
                        highly reliable, efficient, and application-engineered gearbox solutions. With decades of
                        engineering excellence, Flender products are designed to perform in the most demanding industrial
                        environments across sectors such as cement, steel, power, mining, oil & gas, material handling, and
                        infrastructure.
                    </p>
                    <p style="margin-bottom: 20px;">
                        <strong>Flender gearboxes are not standard off-the-shelf products.</strong>
                        They are application-specific solutions, engineered based on load conditions, duty cycles, mounting
                        positions, environmental factors, and lifecycle expectations — ensuring maximum uptime, efficiency,
                        and long service life.
                    </p>
                </div>

                <div
                    style="background: #f1f3f6; padding: 25px; border-radius: 6px; margin-bottom: 30px; border-left: 5px solid #004aad;">
                    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 10px; color: #333;"><i
                            class="fas fa-check-circle" style="color: #004aad; margin-right: 10px;"></i>Authorized Partner
                    </h3>
                    <p style="margin: 0; color: #444;">
                        <strong>S.B. Syscon Pvt. Ltd.</strong> is an Authorized Partner for Flender Gearboxes.
                        All Flender solutions offered through SBS are designed and validated with direct support from the
                        Flender backend technical team, ensuring correct selection, compliance with application
                        requirements, and long-term operational reliability.
                    </p>
                </div>

                <div
                    style="background: #fff3cd; border: 1px solid #ffeeba; padding: 20px; border-radius: 6px; margin-bottom: 40px; display: flex; gap: 20px; align-items: flex-start;">
                    <i class="fas fa-info-circle" style="color: #856404; font-size: 1.5rem; margin-top: 3px;"></i>
                    <div>
                        <h4 style="margin: 0 0 10px 0; color: #856404; font-weight: 700;">Assisted Support Required</h4>
                        <p style="margin: 0; color: #856404;">
                            Due to the engineering-driven nature of Flender products, we require detailed technical inputs
                            before recommending or quoting a solution. Hence, Flender enquiries are handled through Assisted
                            Support rather than direct online checkout.
                        </p>
                    </div>
                </div>

                <div style="text-align: center; border-top: 1px solid #eee; padding-top: 30px;">
                    <h3 style="font-weight: 700; margin-bottom: 25px;">Connect with our Flender Experts</h3>
                    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; margin-bottom: 25px;">
                        <a href="mailto:marcom.sbsyscon@gmail.com" class="btn-view"
                            style="padding: 12px 25px; background: #333; color: #fff; width: auto;">
                            <i class="fas fa-envelope" style="margin-right: 8px;"></i> info@sbsyscon.in
                        </a>
                        <a href="tel:+917506943307" class="btn-view"
                            style="padding: 12px 25px; background: #004aad; color: #fff; width: auto;">
                            <i class="fas fa-phone" style="margin-right: 8px;"></i> +91 98995-98955
                        </a>
                    </div>
                    <p style="color: #777; font-size: 0.9rem;">Our team will work closely with Flender’s technical experts
                        to study your application and deliver the most optimized, reliable, and future-ready drive solution.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="shop-content" style="width: 100%;">
            <div class="shop-top-bar">
                <div class="results-count">
                    Showing
                    <strong><?php echo $total_products > 0 ? $offset + 1 : 0; ?>-<?php echo min($offset + $limit, $total_products); ?></strong>
                    of <strong><?php echo $total_products; ?></strong> products in
                    <?php echo htmlspecialchars($filter_name); ?>
                </div>
            </div>

            <div class="products-grid">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $prod):
                        $has_discount = ($prod['sales_price'] < $prod['mrp'] && $prod['sales_price'] > 0);
                        $discount_percent = 0;
                        if ($has_discount && $prod['mrp'] > 0) {
                            $discount_percent = round((($prod['mrp'] - $prod['sales_price']) / $prod['mrp']) * 100);
                        }
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <a href="product-details.php?id=<?php echo $prod['id']; ?>">
                                    <?php if (!empty($prod['featured_image']) && file_exists($prod['featured_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($prod['featured_image']); ?>"
                                            alt="<?php echo htmlspecialchars($prod['title']); ?>">
                                    <?php else: ?>
                                        <img src="assets/images/placeholder.jpg" alt="No Image">
                                    <?php endif; ?>
                                </a>
                                <?php if ($has_discount): ?>
                                    <span class="badge-discount"><?php echo $discount_percent; ?>% OFF</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <?php
                                $b_name_disp = $filterType === 'brand' ? $filter_name : "Brand";
                                if ($filterType !== 'brand' && $prod['brand_id']) {
                                    $b_res = $conn->query("SELECT name FROM brands WHERE id=" . $prod['brand_id']);
                                    if ($b_res->num_rows > 0)
                                        $b_name_disp = $b_res->fetch_assoc()['name'];
                                }
                                ?>
                                <div class="product-brand"><?php echo htmlspecialchars($b_name_disp); ?></div>
                                <h3 class="product-title">
                                    <a
                                        href="product-details.php?id=<?php echo $prod['id']; ?>"><?php echo htmlspecialchars($prod['title']); ?></a>
                                </h3>

                                <div class="product-price">
                                    <?php if ($prod['sales_price'] > 0): ?>
                                        <span class="current-price">₹<?php echo number_format($prod['sales_price'], 2); ?></span>
                                        <?php if ($has_discount): ?>
                                            <span class="mrp-price">₹<?php echo number_format($prod['mrp'], 2); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="contact-us.php?inquiry=<?php echo urlencode($prod['title']); ?>"
                                            class="btn-request-price">Request Price</a>
                                    <?php endif; ?>
                                </div>

                                <div class="product-actions">
                                    <a href="product-details.php?id=<?php echo $prod['id']; ?>" class="btn-view">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-products-found">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>Coming Soon</h3>
                        <p>Products for <?php echo htmlspecialchars($filter_name); ?> are currently being updated.</p>
                        <div style="margin-top: 20px;">
                            <a href="products.php" class="btn-view"
                                style="width: auto; display: inline-block; padding: 10px 20px;">Browse All Products</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination-wrapper">
                    <div class="pagination">
                        <?php
                        $query_params = $_GET;
                        unset($query_params['page']);
                        $base_url = 'brand.php?' . http_build_query($query_params);
                        $conn_char = !empty($query_params) ? '&' : '';

                        $range = 2;
                        ?>

                        <!-- Prev Button -->
                        <a href="<?php echo ($page > 1) ? $base_url . $conn_char . 'page=' . ($page - 1) : 'javascript:void(0)'; ?>"
                            class="page-link prev <?php echo ($page <= 1) ? 'disabled' : ''; ?>" aria-label="Previous Page">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>

                        <?php
                        if ($page > ($range + 1)) {
                            echo '<a href="' . $base_url . $conn_char . 'page=1" class="page-link">1</a>';
                            if ($page > ($range + 2))
                                echo '<span class="page-dots">...</span>';
                        }

                        for ($i = max(1, $page - $range); $i <= min($total_pages, $page + $range); $i++) {
                            $active = ($i == $page) ? 'active' : '';
                            echo '<a href="' . $base_url . $conn_char . 'page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a>';
                        }

                        if ($page < ($total_pages - $range)) {
                            if ($page < ($total_pages - $range - 1))
                                echo '<span class="page-dots">...</span>';
                            echo '<a href="' . $base_url . $conn_char . 'page=' . $total_pages . '" class="page-link">' . $total_pages . '</a>';
                        }
                        ?>

                        <!-- Next Button -->
                        <a href="<?php echo ($page < $total_pages) ? $base_url . $conn_char . 'page=' . ($page + 1) : 'javascript:void(0)'; ?>"
                            class="page-link next <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>"
                            aria-label="Next Page">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

</body>

</html>