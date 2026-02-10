<?php
// Use absolute path to ensure correct include
$db_config_path = __DIR__ . '/../db_config.php';
if (file_exists($db_config_path)) {
    require_once $db_config_path;
} else {
    die("Error: db_config.php not found at $db_config_path");
}

if (!isset($conn)) {
    die("Error: Database connection variable \$conn not set.");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add is_price_request column to products table
$sql = "ALTER TABLE products ADD COLUMN is_price_request TINYINT(1) DEFAULT 0 AFTER discount_percentage";

if ($conn->query($sql) === TRUE) {
    echo "Column 'is_price_request' added successfully to 'products' table.\n";
} else {
    echo "Error adding column: " . $conn->error . "\n";
}

$conn->close();
?>
