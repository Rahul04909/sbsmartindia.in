<?php
require_once '../../database/db_config.php';
require_once 'auth_session.php';

header('Content-Type: application/json');

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit();
}

$sql = "SELECT id, title, featured_image FROM products 
        WHERE title LIKE '%$query%' 
        AND status = 1 
        ORDER BY title ASC 
        LIMIT 10";

$result = $conn->query($sql);
$suggestions = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image' => !empty($row['featured_image']) ? '../../' . $row['featured_image'] : '../../assets/images/no-image.png'
        ];
    }
}

echo json_encode($suggestions);
?>
