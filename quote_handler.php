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

elseif ($action === 'submit_bulk_quote') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please login to submit a quote.']);
        exit;
    }

    $user_id = intval($_SESSION['user_id']);
    $message = $conn->real_escape_string($_POST['message']);
    
    // Check Table Exists (Lazy Setup)
    $conn->query("CREATE TABLE IF NOT EXISTS bulk_quotes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        message TEXT,
        file_path VARCHAR(255),
        status VARCHAR(50) DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $file_path = NULL;
    
    // File Upload
    if (isset($_FILES['quote_file']) && $_FILES['quote_file']['error'] == 0) {
        $upload_dir = 'assets/uploads/quotes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['quote_file']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $allowed = ['csv', 'xls', 'xlsx', 'pdf'];
        
        if (in_array($file_type, $allowed)) {
            if (move_uploaded_file($_FILES['quote_file']['tmp_name'], $target_file)) {
                $file_path = $target_file;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
                exit;
            }
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Allowed: CSV, Excel, PDF.']);
             exit;
        }
    }
    
    // Insert
    $sql = "INSERT INTO bulk_quotes (user_id, message, file_path) VALUES ($user_id, '$message', " . ($file_path ? "'$file_path'" : "NULL") . ")";
    
    if ($conn->query($sql)) {
         // Send Email to Admin (Optional)
         $subject = "New Bulk Quote Request";
         $body = "User ID: $user_id has requested a bulk quote.<br>Message: $message";
         // sendEmail(ADMIN_EMAIL, 'Admin', $subject, $body); // Uncomment if mail helper ready
         
         echo json_encode(['status' => 'success', 'message' => 'Bulk Quote Request Submitted!']);
    } else {
         echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>
