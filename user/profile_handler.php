<?php
session_start();
require_once '../database/db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'update_profile') {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Basic validation
    if (empty($name) || empty($phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Name and Phone are required.']);
        exit;
    }
    
    $sql = "UPDATE users SET name = '$name', phone = '$phone' WHERE id = $user_id";
    if ($conn->query($sql) === TRUE) {
        // Update Session Name
        $_SESSION['user_name'] = $name;
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
} 
elseif ($action === 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    
    if (empty($current_password) || empty($new_password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }
    
    // Fetch current password hash
    $sql = "SELECT password_hash FROM users WHERE id = $user_id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
    
    if (password_verify($current_password, $user['password_hash'])) {
        // Password correct, update to new one
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password_hash = '$new_hash' WHERE id = $user_id";
        
        if ($conn->query($update_sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Password changed successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating password.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect current password.']);
    }
} 
else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>
