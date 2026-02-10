<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendEmail($to, $toName, $subject, $body) {
    global $conn;

    // Fetch SMTP settings
    $settings_sql = "SELECT * FROM smtp_settings WHERE id=1";
    $result = $conn->query($settings_sql);
    
    if ($result->num_rows > 0) {
        $smtp = $result->fetch_assoc();
        
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $smtp['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtp['username'];
            $mail->Password   = $smtp['password'];
            $mail->SMTPSecure = $smtp['encryption'];
            $mail->Port       = $smtp['port'];
            
            // Recipients
            $mail->setFrom($smtp['from_email'], $smtp['from_name']);
            $mail->addAddress($to, $toName);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            $mail->send();
            return ['status' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
    } else {
        return ['status' => false, 'message' => 'SMTP settings not found'];
    }
}
?>
