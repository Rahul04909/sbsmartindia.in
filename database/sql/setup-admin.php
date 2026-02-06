<?php
require_once '../db_config.php';

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'admins' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Check if default admin exists
$result = $conn->query("SELECT * FROM admins WHERE username = 'admin'");
if ($result->num_rows == 0) {
    // Insert default admin
    $password = password_hash('admin123', PASSWORD_BCRYPT); // Default password: admin123
    $insert_sql = "INSERT INTO admins (username, password) VALUES ('admin', '$password')";
    
    if ($conn->query($insert_sql) === TRUE) {
        echo "Default admin user created successfully.<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
    } else {
        echo "Error creating default admin: " . $conn->error . "<br>";
    }
} else {
    echo "Default admin user already exists.<br>";
}

$conn->close();
?>
