<?php
require_once '../db_config.php';

// Create smtp_settings table
$sql = "CREATE TABLE IF NOT EXISTS smtp_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host VARCHAR(255) NOT NULL,
    port INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    encryption VARCHAR(50) NOT NULL DEFAULT 'tls',
    from_email VARCHAR(255) NOT NULL,
    from_name VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'smtp_settings' created successfully.<br>";
    
    // Insert default row if not exists
    $check = $conn->query("SELECT id FROM smtp_settings LIMIT 1");
    if ($check->num_rows == 0) {
        $sql_insert = "INSERT INTO smtp_settings (host, port, username, password, encryption, from_email, from_name) 
                       VALUES ('smtp.example.com', 587, 'user@example.com', 'password', 'tls', 'admin@example.com', 'Admin')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Default SMTP settings inserted.";
        } else {
            echo "Error inserting default settings: " . $conn->error;
        }
    }
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
