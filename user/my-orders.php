<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$page = 'orders';
$url_prefix = '../';
require_once '../database/db_config.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | SB Smart India</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="../asstes/css/style.css">
    <link rel="stylesheet" href="../assets/css/header-menu.css">
    <link rel="stylesheet" href="../asstes/css/footer.css">
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
    <!-- Components CSS (Important for Modal) -->
    <link rel="stylesheet" href="../assets/css/auth-modal.css">
    
    <style>
        /* Table Styles override or specific to this page */
        .table-responsive { overflow-x: auto; }
        .user-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        .user-table th, .user-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        .user-table th { background-color: #f8f9fa; font-weight: 600; color: #555; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; transition: background 0.2s; }
        .view-btn { background-color: #eef4ff; color: #004aad; font-weight: 500; }
        .view-btn:hover { background-color: #dbe9ff; }
        
        .content-card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
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
                <h1>My Orders</h1>
                <p>Track your past orders and download invoices.</p>
            </div>

            <!-- Orders Table -->
            <div class="content-card">
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $order['order_id']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                        <td>₹<?php echo number_format($order['total_amount']); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = strtolower($order['order_status']);
                                            $badge_color = '#ffc107'; // yellow
                                            if(strpos($status_class, 'delivered') !== false) $badge_color = '#28a745';
                                            if(strpos($status_class, 'cancelled') !== false) $badge_color = '#dc3545';
                                            ?>
                                            <span class="status-badge" style="background-color: <?php echo $badge_color; ?>20; color: <?php echo $badge_color; ?>;">
                                                <?php echo ucfirst($order['order_status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $order['payment_status']; ?></td>
                                        <td>
                                            <a href="invoice.php?order_id=<?php echo $order['order_id']; ?>" class="action-btn view-btn" target="_blank">
                                                <i class="fa-solid fa-file-invoice"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding: 40px; color:#666;">
                        <i class="fa-solid fa-box-open" style="font-size: 40px; margin-bottom: 15px; color:#ddd;"></i>
                        <p>No orders found.</p>
                        <a href="../index.php" class="btn-primary" style="margin-top:10px; display:inline-block; padding:8px 15px; font-size:14px; text-decoration:none;">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
