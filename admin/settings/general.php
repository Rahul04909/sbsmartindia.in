<?php
$page = 'settings';
$url_prefix = '../'; 
require_once '../../database/db_config.php';

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$success_msg = '';
$error_msg = '';

// Handle Username Update
if (isset($_POST['update_profile'])) {
    $new_username = $conn->real_escape_string(trim($_POST['username']));
    
    if (empty($new_username)) {
        $error_msg = "Username cannot be empty.";
    } else {
        $sql = "UPDATE admins SET username = '$new_username' WHERE id = $admin_id";
        if ($conn->query($sql)) {
            $_SESSION['admin_username'] = $new_username;
            $success_msg = "Username updated successfully.";
        } else {
            $error_msg = "Error updating username: " . $conn->error;
        }
    }
}

// Handle Password Update
if (isset($_POST['change_password'])) {
    $current_pw = $_POST['current_password'];
    $new_pw = $_POST['new_password'];
    $confirm_pw = $_POST['confirm_password'];

    // Verify current password first
    $check_sql = "SELECT password FROM admins WHERE id = $admin_id";
    $res = $conn->query($check_sql);
    $admin_data = $res->fetch_assoc();

    if (!password_verify($current_pw, $admin_data['password'])) {
        $error_msg = "Current password is incorrect.";
    } elseif ($new_pw !== $confirm_pw) {
        $error_msg = "New passwords do not match.";
    } elseif (strlen($new_pw) < 6) {
        $error_msg = "New password must be at least 6 characters.";
    } else {
        $hashed_pw = password_hash($new_pw, PASSWORD_DEFAULT);
        $update_pw_sql = "UPDATE admins SET password = '$hashed_pw' WHERE id = $admin_id";
        if ($conn->query($update_pw_sql)) {
            $success_msg = "Password changed successfully.";
        } else {
            $error_msg = "Error updating password: " . $conn->error;
        }
    }
}

// Fetch Current Admin Data
$admin_res = $conn->query("SELECT username FROM admins WHERE id = $admin_id");
$admin = $admin_res->fetch_assoc();

include '../includes/header.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">General Settings</h1>
        <p style="color: #64748b; margin-top: 5px;">Manage your admin profile and security credentials.</p>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success" style="background: #ecfdf5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #a7f3d0;">
            <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-error" style="background: #fff1f2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #fecaca;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        
        <!-- Profile Settings -->
        <div class="card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 16px; color: #1e293b;"><i class="fas fa-user-cog" style="margin-right:8px; color:#004aad;"></i> Admin Profile</h3>
            </div>
            <div style="padding: 20px;">
                <form method="POST" action="">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
                        <small style="color: #64748b; display: block; margin-top: 5px;">This username is used for logging into the admin panel.</small>
                    </div>
                    <button type="submit" name="update_profile" style="background: #004aad; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.2s;">Save Changes</button>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                <h3 style="margin: 0; font-size: 16px; color: #1e293b;"><i class="fas fa-shield-alt" style="margin-right:8px; color:#004aad;"></i> Change Password</h3>
            </div>
            <div style="padding: 20px;">
                <form method="POST" action="">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">Current Password</label>
                        <input type="password" name="current_password" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">New Password</label>
                        <input type="password" name="new_password" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required minlength="6">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569;">Confirm New Password</label>
                        <input type="password" name="confirm_password" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
                    </div>
                    <button type="submit" name="change_password" style="background: #004aad; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: background 0.2s;">Update Password</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>
