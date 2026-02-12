<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
echo "Starting test...<br>";

session_start();
echo "Session started.<br>";
$_SESSION['user_id'] = 1; // Dummy

$url_prefix = '../';
require_once '../database/db_config.php';
echo "DB connected.<br>";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Including header...<br>";
include '../includes/header.php';
echo "Header included.<br>";

echo "Including sidebar...<br>";
include 'includes/sidebar.php';
echo "Sidebar included.<br>";

echo "Including footer...<br>";
include '../includes/footer.php';
echo "Footer included.<br>";
?>
