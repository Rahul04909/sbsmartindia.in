<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u436737546_sbsmart";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create email_otps table
$sql = "CREATE TABLE IF NOT EXISTS email_otps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    otp VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    INDEX (email),
    INDEX (expires_at)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'email_otps' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
