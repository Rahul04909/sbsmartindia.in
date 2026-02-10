<?php
require_once '../db_config.php';

// Create orders table
$orders_sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL UNIQUE,
    user_id INT DEFAULT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_city VARCHAR(50) NOT NULL,
    customer_state VARCHAR(50) NOT NULL,
    customer_zip VARCHAR(20) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(20) DEFAULT 'Pending', -- Pending, Success, Failed
    tracking_id VARCHAR(100) DEFAULT NULL, -- CCAvenue Reference
    bank_ref_no VARCHAR(100) DEFAULT NULL,
    order_status VARCHAR(20) DEFAULT 'Processing', -- Processing, Shipped, Delivered, Cancelled
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($orders_sql) === TRUE) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating table 'orders': " . $conn->error . "<br>";
}

// Create order_items table
$items_sql = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
)";

if ($conn->query($items_sql) === TRUE) {
    echo "Table 'order_items' created successfully.<br>";
} else {
    echo "Error creating table 'order_items': " . $conn->error . "<br>";
}

$conn->close();
?>
