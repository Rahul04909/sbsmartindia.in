<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'database/db_config.php';

echo "Checking tables like '%quote%'...\n";
$sql = "SHOW TABLES LIKE '%quote%'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_array()) {
        echo "Table found: " . $row[0] . "\n";
        // Show columns
        $cols = $conn->query("SHOW COLUMNS FROM " . $row[0]);
        while($col = $cols->fetch_assoc()) {
            echo " - " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    }
} else {
    echo "No tables found matching '%quote%'.\n";
}
?>
