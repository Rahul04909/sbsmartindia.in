<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Robust Connection Logic for CLI
$configs = [
    // Live Server Credentials
    ['host' => 'localhost', 'user' => 'invest13_sbsindia', 'pass' => 'Rahul@2026', 'db' => 'invest13_sbsindia'],
    // Local WAMP Standard
    ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'db' => 'sbsmartindia'],
];

$conn = null;
foreach ($configs as $cfg) {
    try {
        $conn = @new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db']);
        if (!$conn->connect_error) {
            echo "Connected to " . $cfg['db'] . " on " . $cfg['host'] . "\n";
            break;
        }
    } catch (Exception $e) {
        continue;
    }
}

if (!$conn) {
    die("Connection failed: Could not connect to any database.\n");
}

// Create Assisted Orders Table
$sql = "CREATE TABLE IF NOT EXISTS assisted_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    company VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT,
    status ENUM('New', 'Processing', 'Completed') DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'assisted_orders' created successfully or already exists.\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$conn->close();
?>
