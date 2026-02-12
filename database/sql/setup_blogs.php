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

// Create blogs table
$sql = "CREATE TABLE IF NOT EXISTS blogs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT,
    featured_image VARCHAR(255),
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    schema_markup TEXT,
    views INT(11) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'blogs' created successfully.\n";
} else {
    echo "Error creating table 'blogs': " . $conn->error . "\n";
}

$conn->close();
?>
