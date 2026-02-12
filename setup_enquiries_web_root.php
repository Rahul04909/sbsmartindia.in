<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database/db_config.php';

// Create product_enquiries table
$sql = "CREATE TABLE IF NOT EXISTS product_enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -- FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE -- Optional, can cause issues if products table engine differs
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'product_enquiries' created successfully.<br>";
} else {
    echo "Error creating table 'product_enquiries': " . $conn->error . "<br>";
}

$conn->close();
?>
