<?php
session_start();
require_once '../../database/db_config.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Handle Delete Subscriber
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $sql = "DELETE FROM newsletter_subscriptions WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Subscriber deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Handle Status Toggle (Optional but good for management)
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $current_status = $conn->real_escape_string($_GET['current']);
    $new_status = ($current_status == 'active') ? 'unsubscribed' : 'active';
    
    $sql = "UPDATE newsletter_subscriptions SET status='$new_status' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Subscriber status updated!";
    } else {
        $_SESSION['error'] = "Error updating status: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

$conn->close();
?>
