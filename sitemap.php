<?php
header("Content-Type: application/xml; charset=utf-8");
require_once 'database/db_config.php';
require_once 'includes/url_helper.php';

// Set Base URL
$base_url = "https://sbsmart.in/";

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// 1. Static Pages
$static_pages = [
    '' => ['priority' => '1.0', 'changefreq' => 'daily'],
    'products.php' => ['priority' => '0.9', 'changefreq' => 'weekly'],
    'blogs.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'contact-us.php' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'pages/about-us.php' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'pages/faqs.php' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    'pages/privacy-policy.php' => ['priority' => '0.5', 'changefreq' => 'yearly'],
    'pages/terms-and-conditions.php' => ['priority' => '0.5', 'changefreq' => 'yearly'],
    'pages/shipping-and-delivery-policy.php' => ['priority' => '0.5', 'changefreq' => 'yearly'],
    'pages/refund-and-cancellation-policy.php' => ['priority' => '0.5', 'changefreq' => 'yearly'],
];

foreach ($static_pages as $page => $meta) {
    echo '<url>';
    echo '<loc>' . $base_url . $page . '</loc>';
    echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
    echo '<changefreq>' . $meta['changefreq'] . '</changefreq>';
    echo '<priority>' . $meta['priority'] . '</priority>';
    echo '</url>';
}

// 2. Dynamic Products
$product_sql = "SELECT id, title, slug, updated_at FROM products WHERE status = 1 ORDER BY id DESC";
$product_res = $conn->query($product_sql);
if ($product_res && $product_res->num_rows > 0) {
    while ($product = $product_res->fetch_assoc()) {
        echo '<url>';
        echo '<loc>' . $base_url . getProductUrl($product) . '</loc>';
        echo '<lastmod>' . date('Y-m-d', strtotime($product['updated_at'])) . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.8</priority>';
        echo '</url>';
    }
}

// 3. Dynamic Blogs
$blog_sql = "SELECT slug, updated_at FROM blogs WHERE status = 1 ORDER BY id DESC";
$blog_res = $conn->query($blog_sql);
if ($blog_res && $blog_res->num_rows > 0) {
    while ($blog = $blog_res->fetch_assoc()) {
        echo '<url>';
        echo '<loc>' . $base_url . 'blog-details.php?slug=' . urlencode($blog['slug']) . '</loc>';
        echo '<lastmod>' . date('Y-m-d', strtotime($blog['updated_at'])) . '</lastmod>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.7</priority>';
        echo '</url>';
    }
}

echo '</urlset>';
?>
