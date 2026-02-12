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

// Add columns if not exist
$columns_to_add = [
    "courier_name" => "VARCHAR(100) DEFAULT NULL",
    "dispatched_at" => "TIMESTAMP NULL DEFAULT NULL"
];

foreach ($columns_to_add as $col => $def) {
    $check = $conn->query("SHOW COLUMNS FROM orders LIKE '$col'");
    if ($check->num_rows == 0) {
        $sql = "ALTER TABLE orders ADD COLUMN $col $def";
        if ($conn->query($sql) === TRUE) {
            echo "Column '$col' added successfully.\n";
        } else {
            echo "Error adding column '$col': " . $conn->error . "\n";
        }
    } else {
        echo "Column '$col' already exists.\n";
    }
}

$conn->close();
?>
