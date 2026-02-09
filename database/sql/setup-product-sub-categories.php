<?php
require_once '../db_config.php';

// Create product_sub_categories table
$sql = "CREATE TABLE IF NOT EXISTS product_sub_categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_sub_categories' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>
