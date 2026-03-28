<?php
session_start();
require_once '../../database/db_config.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
}

// Fetch all subscribers for CSV export
$sql = "SELECT id, name, email, mobile, status, created_at FROM newsletter_subscriptions ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $filename = "newsletter_subscribers_" . date('Y-m-d') . ".csv";
    
    // Set headers for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    // Open output stream
    $f = fopen('php://output', 'w');
    
    // Set CSV headers
    fputcsv($f, array('ID', 'Name', 'Email', 'Mobile', 'Status', 'Subscribed At'));
    
    // Add data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($f, $row);
    }
    
    fclose($f);
    exit;
} else {
    $_SESSION['error'] = "No subscribers found to export.";
    header("Location: index.php");
    exit();
}
?>
