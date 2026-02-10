<?php
session_start();
require_once 'database/db_config.php';
require_once 'includes/mail_helper.php'; // Ensure this file exists and works

header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'send_otp') {
    $email = $conn->real_escape_string($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        exit;
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    
    // Save OTP (Reuse email_otps table from auth system)
    // Check if previously sent OTP exists and is valid? No, just insert new.
    $sql = "INSERT INTO email_otps (email, otp, expires_at) VALUES ('$email', '$otp', DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
    
    if ($conn->query($sql)) {
        // Send Email
        $subject = "Your OTP for Quote Request - SB Smart";
        $body = "Your OTP for verifying your quote request is: <b>$otp</b>. It is valid for 10 minutes.";
        
        $mailResult = sendEmail($email, 'User', $subject, $body);
        
        if ($mailResult['status']) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent to your email.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. ' . $mailResult['message']]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
}

elseif ($action === 'verify_otp') {
    $email = $conn->real_escape_string($_POST['email']);
    $otp = $conn->real_escape_string($_POST['otp']);
    
    $sql = "SELECT * FROM email_otps WHERE email='$email' AND otp='$otp' AND expires_at > NOW() ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Mark OTP as used (optional, or just delete)
        $conn->query("DELETE FROM email_otps WHERE email='$email'");
        echo json_encode(['status' => 'success', 'message' => 'OTP Verified!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP.']);
    }
}

elseif ($action === 'submit_quote') {
    $product_id = intval($_POST['product_id']);
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $quantity = intval($_POST['quantity']);
    
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']); // Mobile/Phone
    $company = isset($_POST['company_name']) ? $conn->real_escape_string($_POST['company_name']) : '';
    
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $address = $conn->real_escape_string($_POST['address']);
    
    // Server-side validation (basic)
    if (empty($name) || empty($email) || empty($mobile) || empty($pincode)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }
    
    $sql = "INSERT INTO quote_requests (product_id, product_name, quantity, customer_name, customer_email, customer_mobile, company_name, pincode, state, city, address) 
            VALUES ($product_id, '$product_name', $quantity, '$name', '$email', '$mobile', '$company', '$pincode', '$state', '$city', '$address')";

    if ($conn->query($sql) === TRUE) {
        // Send Confirmation Email to Admin/User (Optional, good practice)
        // For now just success response
        echo json_encode(['status' => 'success', 'message' => 'Quote Request Submitted Successfully! We will contact you soon.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error saving quote: ' . $conn->error]);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>
