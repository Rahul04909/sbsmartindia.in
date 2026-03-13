<?php
$host = 'localhost';
$db_user = 'sbsmarti_sbsindia';
$db_pass = 'Sbs@2026';
$db_name = 'sbsmarti_sbsindia';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
