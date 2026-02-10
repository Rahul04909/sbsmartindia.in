<?php
$path = dirname(__DIR__) . '/db_config.php';
if (file_exists($path)) {
    require_once $path;
} else {
    die("Error: db_config.php not found at $path");
}

// Create cart table
$cart_sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    session_id VARCHAR(255) DEFAULT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (user_id),
    INDEX (session_id)
)";

if ($conn->query($cart_sql) === TRUE) {
    echo "Table 'cart' created successfully.<br>";
} else {
    echo "Error creating table 'cart': " . $conn->error . "<br>";
}

$conn->close();
?>
