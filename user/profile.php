<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$page = 'profile';
$url_prefix = '../';
require_once '../database/db_config.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | SB Smart India</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="../asstes/css/style.css">
    <link rel="stylesheet" href="../assets/css/header-menu.css">
    <link rel="stylesheet" href="../asstes/css/footer.css">
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
    <!-- Components CSS -->
    <link rel="stylesheet" href="../assets/css/auth-modal.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .content-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 500; margin-bottom: 8px; color: #555; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; transition: border 0.3s; }
        .form-control:focus { border-color: #004aad; outline: none; }
        .form-control[readonly] { background-color: #f8f9fa; color: #6c757d; cursor: not-allowed; }
        .btn-primary { background-color: #004aad; color: #fff; border: none; padding: 12px 25px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        .btn-primary:hover { background-color: #003380; }
        .section-title { font-size: 18px; font-weight: 700; color: #333; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; display: none; }
        .alert-success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .alert-danger { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="dashboard-header">
                <h1>Profile & Security</h1>
                <p>Manage your personal details and account security.</p>
            </div>

            <!-- Profile Details -->
            <div class="content-card">
                <h3 class="section-title">Personal Information</h3>
                <div id="profile-alert" class="alert"></div>
                <form id="profile-form">
                    <input type="hidden" name="action" value="update_profile">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly title="Email cannot be changed">
                    </div>
                    <button type="submit" class="btn-primary">Update Profile</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="content-card">
                <h3 class="section-title">Change Password</h3>
                <div id="password-alert" class="alert"></div>
                <form id="password-form">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
    $(document).ready(function() {
        // Update Profile
        $('#profile-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'profile_handler.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    var alertBox = $('#profile-alert');
                    alertBox.removeClass('alert-success alert-danger').addClass(response.status === 'success' ? 'alert-success' : 'alert-danger');
                    alertBox.text(response.message).show();
                    if(response.status === 'success') {
                        setTimeout(function(){ location.reload(); }, 1500); 
                    }
                },
                error: function() {
                    $('#profile-alert').addClass('alert-danger').text('An error occurred. Please try again.').show();
                }
            });
        });

        // Change Password
        $('#password-form').on('submit', function(e) {
            e.preventDefault();
            
            var newPass = $('input[name="new_password"]').val();
            var confirmPass = $('input[name="confirm_password"]').val();
            
            if(newPass !== confirmPass) {
                $('#password-alert').removeClass('alert-success').addClass('alert-danger').text('New passwords do not match.').show();
                return;
            }

            $.ajax({
                url: 'profile_handler.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    var alertBox = $('#password-alert');
                    alertBox.removeClass('alert-success alert-danger').addClass(response.status === 'success' ? 'alert-success' : 'alert-danger');
                    alertBox.text(response.message).show();
                    if(response.status === 'success') {
                        $('#password-form')[0].reset();
                    }
                },
                error: function() {
                    $('#password-alert').addClass('alert-danger').text('An error occurred. Please try again.').show();
                }
            });
        });
    });
    </script>

</body>
</html>
