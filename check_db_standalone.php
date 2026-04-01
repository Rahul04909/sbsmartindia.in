<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db_user = 'sbsmarti_sbsindia';
$db_pass = 'Sbs@2026';
$db_name = 'sbsmarti_sbsindia';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connection successful!<br>";

$result = $conn->query("SHOW TABLES");
if ($result) {
    echo "Tables in database:<br><ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Error showing tables: " . $conn->error;
}

$conn->close();
?>
