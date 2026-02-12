<?php
$db_config_path = __DIR__ . '/../db_config.php';
if (file_exists($db_config_path)) {
    require_once $db_config_path;
} else {
    die("Error: db_config.php not found at $db_config_path");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create blog_categories table
$sql = "CREATE TABLE IF NOT EXISTS blog_categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description LONGTEXT,
    image VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'blog_categories' created successfully.\n";
} else {
    echo "Error creating table 'blog_categories': " . $conn->error . "\n";
}

$conn->close();
?>
