<?php
$host = 'localhost';
$db_user = 'invest13_sbsindia';
$db_pass = 'Rahul@2026';
$db_name = 'invest13_sbsindia';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
