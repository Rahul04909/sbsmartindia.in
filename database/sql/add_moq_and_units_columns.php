<?php
// Use absolute path to ensure correct include
$db_config_path = __DIR__ . '/../db_config.php';
if (file_exists($db_config_path)) {
    require_once $db_config_path;
} else {
    die("Error: db_config.php not found at $db_config_path\n");
}

if (!isset($conn)) {
    die("Error: Database connection variable \$conn not set.\n");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Starting database migration...\n";

// Check if moq column exists
$check_moq = $conn->query("SHOW COLUMNS FROM products LIKE 'moq'");
if ($check_moq && $check_moq->num_rows == 0) {
    $sql_moq = "ALTER TABLE products ADD COLUMN moq INT DEFAULT 1 AFTER stock";
    if ($conn->query($sql_moq) === TRUE) {
        echo "Column 'moq' added successfully to 'products' table.\n";
    } else {
        echo "Error adding column 'moq': " . $conn->error . "\n";
    }
} else {
    echo "Column 'moq' already exists in 'products' table.\n";
}

// Check if unit column exists
$check_unit = $conn->query("SHOW COLUMNS FROM products LIKE 'unit'");
if ($check_unit && $check_unit->num_rows == 0) {
    $sql_unit = "ALTER TABLE products ADD COLUMN unit VARCHAR(50) DEFAULT 'nos' AFTER moq";
    if ($conn->query($sql_unit) === TRUE) {
        echo "Column 'unit' added successfully to 'products' table.\n";
    } else {
        echo "Error adding column 'unit': " . $conn->error . "\n";
    }
} else {
    echo "Column 'unit' already exists in 'products' table.\n";
}

$conn->close();
echo "Migration complete.\n";
?>
