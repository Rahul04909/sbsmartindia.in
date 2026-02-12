<?php
$page = 'assisted-orders';
$url_prefix = '../'; // Admin subfolder depth
require_once '../../database/db_config.php';
include '../includes/header.php'; // Adjust path if header is in root/includes or admin/includes. 
// Wait, admin/brands/index.php used `../includes/header.php`.
// `admin/brands` is 1 level deep from `admin`.
// `admin/assisted-orders` is also 1 level deep from `admin`.
// So `../includes/header.php` works if `includes` is inside `admin`.
// Let's check `admin/brands/index.php` again.
// Line 4: `include '../includes/header.php';`
// This implies `d:\wamp\www\sbsmartindia.in\admin\includes\header.php` exists?
// OR `d:\wamp\www\sbsmartindia.in\includes\header.php`?
// If `admin/brands/index.php` is in `d:\wamp\www\sbsmartindia.in\admin\brands\`, then `../includes` is `d:\wamp\www\sbsmartindia.in\admin\includes`.
// If the global header is in `d:\wamp\www\sbsmartindia.in\includes\header.php`, then from `admin/brands`, it should be `../../includes/header.php`.
// `admin/brands/index.php` line 4 says `include '../includes/header.php';`.
// Code view of `admin/brands/index.php` confirmed this.
// `admin/brands/index.php` line 5 says `require_once '../../database/db_config.php';`. This goes up 2 levels.
// So `include '../includes/header.php'` goes up 1 level -> `admin/includes`.
// Does `admin/includes/header.php` exist?
// User said `admin\assisted-orders`.
// I will assume `include '../includes/header.php';` is correct for admin pages if they share a common admin header.
// I will use `../includes/header.php` matching existing pattern.

// Handle Delete Action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del_sql = "DELETE FROM assisted_orders WHERE id = $delete_id";
    if ($conn->query($del_sql)) {
        $_SESSION['success'] = "Order deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting order: " . $conn->error;
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
$count_sql = "SELECT COUNT(*) as total FROM assisted_orders";
$count_res = $conn->query($count_sql);
$total_rows = $count_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch Data
$sql = "SELECT * FROM assisted_orders ORDER BY created_at DESC LIMIT $offset, $limit";
$result = $conn->query($sql);
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Assisted Orders</h1>
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
                        <th width="120">Date</th>
                        <th>Client Details</th>
                        <th>Company</th>
                        <th>Message</th>
                        <th width="100">Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $msg = strip_tags($row['message']);
                            if (strlen($msg) > 50) $msg = substr($msg, 0, 50) . '...';
                            
                            $date = date('d M Y', strtotime($row['created_at']));
                            
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $date . "</td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                                    <small><i class='fas fa-envelope'></i> " . htmlspecialchars($row['email']) . "</small><br>
                                    <small><i class='fas fa-phone'></i> " . htmlspecialchars($row['phone']) . "</small>
                                  </td>";
                            echo "<td>" . htmlspecialchars($row['company']) . "</td>";
                            echo "<td title='" . htmlspecialchars($row['message']) . "'>" . $msg . "</td>";
                            
                            $status_class = 'status-pending';
                            if($row['status'] == 'Completed') $status_class = 'status-active'; // Assuming these classes exist
                            
                            echo "<td><span class='status-badge $status_class'>" . $row['status'] . "</span></td>";
                            
                            echo "<td>
                                <div class='action-buttons'>
                                    <a href='index.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this order?');\"><i class='fas fa-trash'></i></a>
                                </div>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center; color: var(--text-muted); padding: 30px;'>No assisted orders found.</td></tr>";
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

<?php 
$conn->close();
include '../includes/footer.php'; 
?>
