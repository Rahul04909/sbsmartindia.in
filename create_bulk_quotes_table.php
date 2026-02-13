<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'database/db_config.php';

$sql = "CREATE TABLE IF NOT EXISTS bulk_quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT,
    file_path VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'bulk_quotes' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
