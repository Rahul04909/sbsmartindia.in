<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db_user = 'invest13_sbsindia';
$db_pass = 'Rahul@2026';
$db_name = 'invest13_sbsindia';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
