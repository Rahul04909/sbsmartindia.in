<?php
require_once __DIR__ . '/../../database/db_config.php';

// Create product_reviews table
$sql = "CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    status TINYINT(1) DEFAULT 1, -- 1: Approved, 0: Pending/Hidden
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_reviews' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
