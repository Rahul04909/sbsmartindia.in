$page = 'settings';
$url_prefix = '../'; // Fix for relative assets
require_once '../../database/db_config.php';
require_once '../../vendor/autoload.php'; // Ensure PHPMailer is loaded

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle Form Submission
$message = '';
$msg_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // SAVE SETTINGS
    if (isset($_POST['save_settings'])) {
        $host = $conn->real_escape_string($_POST['host']);
        $port = intval($_POST['port']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $encryption = $conn->real_escape_string($_POST['encryption']);
        $from_email = $conn->real_escape_string($_POST['from_email']);
        $from_name = $conn->real_escape_string($_POST['from_name']);
        
        // Update (assuming single row with ID 1)
        $sql = "UPDATE smtp_settings SET 
                host='$host', port=$port, username='$username', password='$password', 
                encryption='$encryption', from_email='$from_email', from_name='$from_name' 
                WHERE id=1";
                
        if ($conn->query($sql) === TRUE) {
            $message = "SMTP Settings updated successfully!";
            $msg_type = "success";
        } else {
            $message = "Error updating settings: " . $conn->error;
            $msg_type = "danger";
        }
    }
    
    // SEND TEST EMAIL
    if (isset($_POST['send_test_email'])) {
        $test_email = $_POST['test_email_address'];
        
        // Fetch current settings
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
                $mail->addAddress($test_email);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Test Email from SB Smart Admin';
                $mail->Body    = 'This is a test email sent from the <b>SB Smart Admin Panel</b> to verify SMTP settings.';
                
                $mail->send();
                $message = "Test email sent successfully to $test_email";
                $msg_type = "success";
            } catch (Exception $e) {
                $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $msg_type = "danger";
            }
        }
    }
}

// Fetch Current Settings
$curr_settings = null;
$res = $conn->query("SELECT * FROM smtp_settings WHERE id=1");
if ($res->num_rows > 0) {
    $curr_settings = $res->fetch_assoc();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">SMTP Configuration</h1>
</div>

<?php if($message): ?>
<div class="alert alert-<?php echo $msg_type; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #fff; background-color: <?php echo $msg_type == 'success' ? '#28a745' : '#dc3545'; ?>;">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<div class="dashboard-widgets" style="display: block;"> <!-- Override grid -->
    
    <div class="row" style="display: flex; gap: 20px;">
        <!-- Settings Form -->
        <div class="widget-card" style="flex: 2; cursor: default;">
            <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">Mail Server Settings</h3>
            </div>
            
            <form method="POST" action="">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">SMTP Host</label>
                        <input type="text" name="host" class="form-control" value="<?php echo $curr_settings['host'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">SMTP Port</label>
                        <input type="number" name="port" class="form-control" value="<?php echo $curr_settings['port'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $curr_settings['username'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Password</label>
                        <input type="password" name="password" class="form-control" value="<?php echo $curr_settings['password'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Encryption</label>
                    <select name="encryption" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="tls" <?php echo ($curr_settings['encryption'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                        <option value="ssl" <?php echo ($curr_settings['encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">From Email</label>
                        <input type="email" name="from_email" class="form-control" value="<?php echo $curr_settings['from_email'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">From Name</label>
                        <input type="text" name="from_name" class="form-control" value="<?php echo $curr_settings['from_name'] ?? ''; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <button type="submit" name="save_settings" class="btn-admin" style="margin-top: 10px;">Save Settings</button>
            </form>
        </div>

        <!-- Test Email -->
        <div class="widget-card" style="flex: 1; height: fit-content; cursor: default;">
            <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">Send Test Email</h3>
            </div>
            
            <p style="font-size: 14px; color: #666; margin-bottom: 15px;">Enter an email address to verify your SMTP configuration.</p>
            
            <form method="POST" action="">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Recipient Email</label>
                    <input type="email" name="test_email_address" class="form-control" placeholder="test@example.com" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <button type="submit" name="send_test_email" class="btn-admin" style="background-color: #28a745;">Send Test Email</button>
            </form>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
