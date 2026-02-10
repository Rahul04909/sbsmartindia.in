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

// Create products table
$sql_products = "CREATE TABLE IF NOT EXISTS products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sub_category_id INT(11) NOT NULL,
    brand_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT,
    mrp DECIMAL(10, 2) DEFAULT 0.00,
    sales_price DECIMAL(10, 2) DEFAULT 0.00,
    discount_percentage DECIMAL(5, 2) DEFAULT 0.00,
    stock INT(11) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    featured_image VARCHAR(255) DEFAULT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sub_category_id) REFERENCES product_sub_categories(id) ON DELETE CASCADE
)";

if ($conn->query($sql_products) === TRUE) {
    echo "Table 'products' created successfully.\n";
} else {
    echo "Error creating table 'products': " . $conn->error . "\n";
}

// Create product_images table for gallery
$sql_images = "CREATE TABLE IF NOT EXISTS product_images (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    display_order INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";

if ($conn->query($sql_images) === TRUE) {
    echo "Table 'product_images' created successfully.\n";
} else {
    echo "Error creating table 'product_images': " . $conn->error . "\n";
}

$conn->close();
?>
