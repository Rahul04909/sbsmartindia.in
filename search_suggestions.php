<?php
require_once 'database/db_config.php';

header('Content-Type: application/json');

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = $conn->real_escape_string(trim($_GET['q']));
    
    // Search in title, sku, meta_keywords
    $sql = "SELECT id, title, featured_image, sales_price, mrp, is_price_request 
            FROM products 
            WHERE status = 1 AND (title LIKE '%$q%' OR sku LIKE '%$q%' OR meta_keywords LIKE '%$q%') 
            ORDER BY title ASC 
            LIMIT 6";
            
    $result = $conn->query($sql);
    $products = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $image = !empty($row['featured_image']) ? $row['featured_image'] : 'assets/images/placeholder.jpg';
            $products[] = [
                'id' => $row['id'],
                'title' => $row['title'],
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
