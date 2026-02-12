<?php
$page = 'orders';
$url_prefix = '../../';
require_once '../../database/db_config.php';
include '../includes/header.php';

// Handle Dispatch Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'dispatch_order') {
    $order_id = intval($_POST['order_id']);
    $courier_name = $conn->real_escape_string($_POST['courier_name']);
    $tracking_id = $conn->real_escape_string($_POST['tracking_id']);
    
    $update_sql = "UPDATE orders SET order_status = 'Shipped', courier_name = '$courier_name', tracking_id = '$tracking_id', dispatched_at = NOW() WHERE id = $order_id";
    
    if ($conn->query($update_sql)) {
        $_SESSION['success'] = "Order dispatched successfully.";
    } else {
        $_SESSION['error'] = "Error dispatching order: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Pagination Setup
$limit = 10;
$page_num = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page_num < 1) $page_num = 1;
$offset = ($page_num - 1) * $limit;

// Count Total
$count_sql = "SELECT COUNT(*) as total FROM orders";
$count_res = $conn->query($count_sql);
$total_rows = $count_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch Orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT $offset, $limit";
$result = $conn->query($sql);
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Orders</h1>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="background: #fce8e8; color: #d63638; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Tracking Info</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $date = date('d M Y', strtotime($row['created_at']));
                            
                            // Status Badges
                            $payment_class = 'status-pending';
                            if($row['payment_status'] == 'Success') $payment_class = 'status-active';
                            elseif($row['payment_status'] == 'Failed') $payment_class = 'status-inactive';
                            
                            $order_class = 'status-pending';
                            if($row['order_status'] == 'Shipped') $order_class = 'status-active';
                            elseif($row['order_status'] == 'Delivered') $order_class = 'status-active';
                            elseif($row['order_status'] == 'Cancelled') $order_class = 'status-inactive';

                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['order_id']) . "</strong></td>";
                            echo "<td>" . $date . "</td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['customer_name']) . "</strong><br>
                                    <small>" . htmlspecialchars($row['customer_phone']) . "</small>
                                  </td>";
                            echo "<td>₹" . number_format($row['total_amount'], 2) . "</td>";
                            echo "<td><span class='status-badge $payment_class'>" . $row['payment_status'] . "</span></td>";
                            echo "<td><span class='status-badge $order_class'>" . $row['order_status'] . "</span></td>";
                            
                            echo "<td>";
                            if($row['tracking_id']) {
                                echo "<small><strong>Courier:</strong> " . htmlspecialchars($row['courier_name']) . "<br>";
                                echo "<strong>Ref:</strong> " . htmlspecialchars($row['tracking_id']) . "</small>";
                            } else {
                                echo "<span class='text-muted'>-</span>";
                            }
                            echo "</td>";
                            
                            echo "<td>
                                <div class='action-buttons'>";
                            
                            if($row['order_status'] == 'Processing' || $row['order_status'] == 'Pending') {
                                echo "<button type='button' class='btn-action btn-edit' onclick='openDispatchModal(" . $row['id'] . ", \"" . $row['order_id'] . "\")' title='Dispatch Order'><i class='fas fa-shipping-fast'></i></button>";
                            }
                            
                            echo "</div>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='text-align: center; color: var(--text-muted); padding: 30px;'>No orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination" style="margin-top: 20px; text-align: right;">
            <?php if ($page_num > 1): ?>
                <a href="?page=<?php echo ($page_num - 1); ?>" class="btn-admin" style="display:inline-block; padding: 5px 10px; font-size: 14px;">&laquo; Prev</a>
            <?php endif; ?>
            
            <span style="margin: 0 10px; font-weight: 600;">Page <?php echo $page_num; ?> of <?php echo $total_pages; ?></span>
            
            <?php if ($page_num < $total_pages): ?>
                <a href="?page=<?php echo ($page_num + 1); ?>" class="btn-admin" style="display:inline-block; padding: 5px 10px; font-size: 14px;">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Dispatch Modal -->
<div id="dispatchModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:30px; border-radius:8px; width:400px; max-width:90%; position:relative;">
        <h2 style="margin-top:0; margin-bottom:20px;">Dispatch Order <span id="modalOrderId" style="font-size:16px; color:#666;"></span></h2>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="dispatch_order">
            <input type="hidden" name="order_id" id="modalOrderIdInput">
            
            <div class="form-group" style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Courier Name</label>
                <input type="text" name="courier_name" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px; font-weight:600;">Tracking ID / Ref No.</label>
                <input type="text" name="tracking_id" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div style="text-align:right;">
                <button type="button" onclick="closeDispatchModal()" style="background:#ccc; border:none; padding:10px 20px; border-radius:4px; margin-right:10px; cursor:pointer;">Cancel</button>
                <button type="submit" style="background:#004aad; color:#fff; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">Dispatch Order</button>
            </div>
        </form>
    </div>
</div>

<script>
function openDispatchModal(id, orderId) {
    document.getElementById('modalOrderIdInput').value = id;
    document.getElementById('modalOrderId').innerText = '#' + orderId;
    document.getElementById('dispatchModal').style.display = 'flex';
}

function closeDispatchModal() {
    document.getElementById('dispatchModal').style.display = 'none';
}

// Close modal if clicked outside
window.onclick = function(event) {
    var modal = document.getElementById('dispatchModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php 
$conn->close();
include '../includes/footer.php'; 
?>
