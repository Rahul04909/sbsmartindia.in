<?php
header('Content-Type: application/json');
require_once '../database/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
    $mobile = isset($_POST['mobile']) ? mysqli_real_escape_string($conn, trim($_POST['mobile'])) : '';

    if (empty($name) || empty($email) || empty($mobile)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    // Check if duplicate email
    $check_sql = "SELECT id FROM newsletter_subscriptions WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already subscribed.']);
        exit;
    }

    // Insert into table
    $sql = "INSERT INTO newsletter_subscriptions (name, email, mobile) VALUES ('$name', '$email', '$mobile')";
    
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Thank you for subscribing to our newsletter!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again later.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
