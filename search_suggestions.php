<?php
require_once 'database/db_config.php';

header('Content-Type: application/json');

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = $conn->real_escape_string(trim($_GET['q']));
    
    // Search in title, sku, meta_keywords
    $sql = "SELECT p.id, p.title, p.featured_image, p.sales_price, p.mrp, p.is_price_request, c.name as category_name 
            FROM products p 
            LEFT JOIN product_categories c ON p.category_id = c.id 
            WHERE p.status = 1 AND (p.title LIKE '%$q%' OR p.sku LIKE '%$q%' OR p.meta_keywords LIKE '%$q%') 
            ORDER BY p.title ASC 
            LIMIT 8";
            
    $result = $conn->query($sql);
    $products = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $image = !empty($row['featured_image']) && file_exists($row['featured_image']) ? $row['featured_image'] : 'asstes/logo/logo.png';
            $products[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'category' => $row['category_name'],
                'image' => $image,
                'price' => (float)$row['sales_price'],
                'mrp' => (float)$row['mrp'],
                'is_price_request' => (int)$row['is_price_request']
            ];
        }
    }
    
    echo json_encode(['status' => 'success', 'data' => $products]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Query is empty']);
}
?>
