<?php
require_once __DIR__ . '/../db_config.php';

// Add SKU column
$sql_sku = "ALTER TABLE products ADD COLUMN sku VARCHAR(50) DEFAULT NULL AFTER title";
if ($conn->query($sql_sku) === TRUE) {
    echo "Column 'sku' added successfully.<br>";
} else {
    echo "Error adding column 'sku': " . $conn->error . "<br>";
}

// Add HSN Code column
$sql_hsn = "ALTER TABLE products ADD COLUMN hsn_code VARCHAR(20) DEFAULT NULL AFTER sku";
if ($conn->query($sql_hsn) === TRUE) {
    echo "Column 'hsn_code' added successfully.<br>";
} else {
    echo "Error adding column 'hsn_code': " . $conn->error . "<br>";
}

$conn->close();
?>
