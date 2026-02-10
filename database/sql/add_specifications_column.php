<?php
require_once '../db_config.php';

// Add specifications column if not exists
$sql = "SHOW COLUMNS FROM products LIKE 'specifications'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $alter_sql = "ALTER TABLE products ADD COLUMN specifications TEXT DEFAULT NULL AFTER description";
    if ($conn->query($alter_sql) === TRUE) {
        echo "Column 'specifications' added successfully.";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'specifications' already exists.";
}

$conn->close();
?>
