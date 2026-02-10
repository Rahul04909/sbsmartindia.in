<?php
session_start();
require_once 'database/db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $password = $_POST['password'];

        // Validate duplicates
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already registered']);
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, phone, password_hash) VALUES ('$name', '$email', '$phone', '$password_hash')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Registration successful! Please login.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
        }
    } 
    
    elseif ($action === 'send_otp') {
        $email = $conn->real_escape_string($_POST['email']);
        
        // Check if email exists
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email already registered. Please login.']);
            exit;
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        // Let MySQL handle time to avoid timezone mismatch
        
        // Save OTP
        $sql = "INSERT INTO email_otps (email, otp, expires_at) VALUES ('$email', '$otp', DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
        if ($conn->query($sql)) {
            // Send Email
            require_once 'includes/mail_helper.php';
            $subject = "Your OTP for Registration - SB Smart";
            $body = "Your OTP for registration is: <b>$otp</b>. It is valid for 10 minutes.";
            
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

    elseif ($action === 'verify_register') {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $password = $_POST['password'];
        $otp = $conn->real_escape_string($_POST['otp']);

        // Verify OTP
        $otp_sql = "SELECT * FROM email_otps WHERE email='$email' AND otp='$otp' AND expires_at > NOW() ORDER BY id DESC LIMIT 1";
        $otp_res = $conn->query($otp_sql);

        if ($otp_res->num_rows > 0) {
            // Valid OTP -> Create User
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, phone, password_hash) VALUES ('$name', '$email', '$phone', '$password_hash')";

            if ($conn->query($sql) === TRUE) {
                // Delete used OTPs
                $conn->query("DELETE FROM email_otps WHERE email='$email'");
                echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error creating user: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP.']);
        }
    }
    
    elseif ($action === 'login') {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'redirect' => 'user/index.php']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    }
}
?>
