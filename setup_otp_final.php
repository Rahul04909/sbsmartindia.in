<?php
// Try local default first, then the config one
$configs = [
    ['localhost', 'root', '', 'u436737546_sbsmart'], // My guess/local default
    ['localhost', 'invest13_sbsindia', 'Rahul@2026', 'invest13_sbsindia'] // From config file
];

$conn = null;
foreach ($configs as $conf) {
    $c = new mysqli($conf[0], $conf[1], $conf[2], $conf[3]);
    if (!$c->connect_error) {
        $conn = $c;
        echo "Connected using user: " . $conf[1] . "\n";
        break;
    }
}

if (!$conn) {
    // If all fail, try to connect without DB and create it if needed
    $conn = new mysqli('localhost', 'root', '');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Try to use the DB
    if (!$conn->select_db('u436737546_sbsmart')) {
        // Maybe DB doesn't exist? Try the other name
        if (!$conn->select_db('invest13_sbsindia')) {
             echo "Database not found.\n";
        }
    }
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
    echo "Table 'email_otps' check/create successful.\n";
} else {
    echo "Error checking/creating table: " . $conn->error . "\n";
}

$conn->close();
?>
