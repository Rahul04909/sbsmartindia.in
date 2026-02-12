<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database/db_config.php';

// Add columns if not exist
$columns_to_add = [
    "courier_name" => "VARCHAR(100) DEFAULT NULL",
    "tracking_id" => "VARCHAR(100) DEFAULT NULL",
    "dispatched_at" => "TIMESTAMP NULL DEFAULT NULL"
];

foreach ($columns_to_add as $col => $def) {
    try {
        $check = $conn->query("SHOW COLUMNS FROM orders LIKE '$col'");
        if ($check && $check->num_rows == 0) {
            $sql = "ALTER TABLE orders ADD COLUMN $col $def";
            if ($conn->query($sql) === TRUE) {
                echo "Column '$col' added successfully.<br>";
            } else {
                echo "Error adding column '$col': " . $conn->error . "<br>";
            }
        } elseif ($check) {
            echo "Column '$col' already exists.<br>";
        } else {
            echo "Error checking column '$col': " . $conn->error . "<br>";
        }
    } catch (Exception $e) {
        echo "Exception for $col: " . $e->getMessage() . "<br>";
    }
}
?>
