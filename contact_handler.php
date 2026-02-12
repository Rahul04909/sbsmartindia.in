<?php
session_start();
require_once 'database/db_config.php';
require_once 'includes/mail_helper.php';

header('Content-Type: application/json');

// Enable error reporting but keep it internal to not break JSON
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'send_otp') {
        $email = $conn->real_escape_string($_POST['email']);
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Save OTP
        $sql = "INSERT INTO email_otps (email, otp, expires_at) VALUES ('$email', '$otp', DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
        if ($conn->query($sql)) {
            // Send Email
            $subject = "Verify your email for Contact Request - SB Smart";
            $body = "Your OTP for verifying your contact request is: <b>$otp</b>. It is valid for 10 minutes.";
            
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
    
    elseif ($action === 'submit_contact') {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $message = $conn->real_escape_string($_POST['message']);
        $otp = $conn->real_escape_string($_POST['otp']);

        // Verify OTP
        $otp_sql = "SELECT * FROM email_otps WHERE email='$email' AND otp='$otp' AND expires_at > NOW() ORDER BY id DESC LIMIT 1";
        $otp_res = $conn->query($otp_sql);

        if ($otp_res->num_rows > 0) {
            // Valid OTP -> Save Contact Request
            $sql = "INSERT INTO contact_requests (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

            if ($conn->query($sql) === TRUE) {
                // Delete used OTPs
                $conn->query("DELETE FROM email_otps WHERE email='$email'");
                
                echo json_encode(['status' => 'success', 'message' => 'Thank you! Your message has been sent successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error saving request: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP.']);
        }
    }

    elseif ($action === 'submit_assisted_order') {
        $name = $conn->real_escape_string($_POST['name']);
        $company = isset($_POST['company']) ? $conn->real_escape_string($_POST['company']) : '';
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $message = $conn->real_escape_string($_POST['message']);

        // Insert directly into assisted_orders table (No OTP for now)
        $sql = "INSERT INTO assisted_orders (name, company, email, phone, message) VALUES ('$name', '$company', '$email', '$phone', '$message')";

        if ($conn->query($sql) === TRUE) {
            // Optional: Send notification email to admin here
            
            echo json_encode(['status' => 'success', 'message' => 'Request received successfully! Our team will contact you shortly.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving request: ' . $conn->error]);
        }
    }
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>
