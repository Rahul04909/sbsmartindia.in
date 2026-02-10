<?php
require_once __DIR__ . '/../db_config.php';

// Create quote_requests table
$sql = "CREATE TABLE IF NOT EXISTS quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_mobile VARCHAR(20) NOT NULL,
    company_name VARCHAR(150),
    pincode VARCHAR(10),
    state VARCHAR(50),
    city VARCHAR(50),
    address TEXT,
    status VARCHAR(20) DEFAULT 'Pending', -- Pending, Contacted, Converted, Closed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'quote_requests' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
