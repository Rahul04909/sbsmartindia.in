<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$page = 'dashboard';
$url_prefix = '../'; // For assets in header/footer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | SB Smart India</title>
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
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="dashboard-header">
                <h1>My Account</h1>
                <p>Manage your orders, quotes, and account details.</p>
            </div>

            <div class="dashboard-cards">
                <!-- Orders Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="card-info">
                        <h3>Your Orders</h3>
                        <p>Track, return, or buy things again</p>
                    </div>
                </a>

                <!-- Profile Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="card-info">
                        <h3>Login & Security</h3>
                        <p>Edit name, email, and password</p>
                    </div>
                </a>

                <!-- Quotes Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div class="card-info">
                        <h3>My Quotes</h3>
                        <p>View price requests and quotations</p>
                    </div>
                </a>

                <!-- Support Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="card-info">
                        <h3>Help & Support</h3>
                        <p>Connect with our support team</p>
                    </div>
                </a>
                
                 <!-- Address Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon" style="background: #fdf2f8; color: #db2777;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="card-info">
                        <h3>Your Addresses</h3>
                        <p>Edit addresses for orders</p>
                    </div>
                </a>
                
                 <!-- Payment Card -->
                <a href="#" class="dash-card">
                    <div class="card-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="fa-regular fa-credit-card"></i>
                    </div>
                    <div class="card-info">
                        <h3>Payment Methods</h3>
                        <p>Manage cards and billing</p>
                    </div>
                </a>

            </div>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
