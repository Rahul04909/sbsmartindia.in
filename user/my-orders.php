<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$url_prefix = '../';
require_once '../database/db_config.php';
require_once '../includes/header.php';
?>
<link rel="stylesheet" href="../assets/css/user-dashboard.css">

<div class="container" style="display: flex; margin-top: 20px; margin-bottom: 20px;">
    <?php require_once 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="user-content" style="flex: 1; padding-left: 20px;">
        <div class="user-header">
            <h2>My Orders</h2>
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
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
                                    // Map to CSS classes if needed or just use inline styles for simplicity
                                    $badge_color = '#ffc107'; // yellow
                                    if($status_class == 'delivered') $badge_color = '#28a745';
                                    if($status_class == 'cancelled') $badge_color = '#dc3545';
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
                <a href="../index.php" class="btn-primary" style="margin-top:10px; display:inline-block; padding:8px 15px; font-size:14px;">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<style>
/* Additional Styles for Table */
.table-responsive { overflow-x: auto; }
.user-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.user-table th, .user-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
.user-table th { background-color: #f8f9fa; font-weight: 600; color: #555; }
.status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.action-btn { text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; gap: 5px; transition: background 0.2s; }
.view-btn { background-color: #eef4ff; color: #004aad; font-weight: 500; }
.view-btn:hover { background-color: #dbe9ff; }
</style>

</body>
</html>
