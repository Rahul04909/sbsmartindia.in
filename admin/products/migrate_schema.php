<?php
require_once '../../database/db_config.php';

echo "Starting migration...\n";

// 1. Add category_id column if not exists
$check_col = $conn->query("SHOW COLUMNS FROM products LIKE 'category_id'");
if ($check_col->num_rows == 0) {
    $sql = "ALTER TABLE products ADD COLUMN category_id INT(11) AFTER brand_id";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'category_id' added successfully.\n";
    } else {
        die("Error adding column: " . $conn->error . "\n");
    }
} else {
    echo "Column 'category_id' already exists.\n";
}

// 2. Migrate Data: Update category_id based on sub_category_id
$sql_update = "UPDATE products p 
               JOIN product_sub_categories sc ON p.sub_category_id = sc.id 
               SET p.category_id = sc.category_id 
               WHERE p.category_id IS NULL OR p.category_id = 0";

if ($conn->query($sql_update) === TRUE) {
    echo "Data migration completed. Rows updated: " . $conn->affected_rows . "\n";
} else {
    echo "Error migrating data: " . $conn->error . "\n";
}

// 3. Make sub_category_id nullable (optional, but good practice if we allow empty)
// We will treat 0 as empty in PHP, but let's allow NULL in DB to be safe
$sql_alter = "ALTER TABLE products MODIFY sub_category_id INT(11) NULL DEFAULT NULL";
if ($conn->query($sql_alter) === TRUE) {
    echo "Modified sub_category_id to be NULLABLE.\n";
} else {
    echo "Error modifying sub_category_id: " . $conn->error . "\n";
}

echo "Migration finished.\n";
?>
