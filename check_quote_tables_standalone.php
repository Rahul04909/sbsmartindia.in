<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db_user = 'invest13_sbsindia';
$db_pass = 'Rahul@2026';
$db_name = 'invest13_sbsindia';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
