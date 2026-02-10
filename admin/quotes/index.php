<?php
$page = 'quotes';
$url_prefix = '../'; // Points to Admin Root
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del_sql = "DELETE FROM quote_requests WHERE id = $delete_id";
    if ($conn->query($del_sql) === TRUE) {
        // Redirect to same page to avoid resubmission
        header("Location: index.php?msg=deleted");
        exit();
    } else {
        $error = "Error deleting record: " . $conn->error;
    }
}

include '../includes/header.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Quote Requests</h1>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            Quote request deleted successfully.
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-error" style="background: #fce8e8; color: #d63638; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="40">ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Company</th>
                        <th width="150">Location</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pagination Setup
                    $limit = 10;
                    $page_num = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $offset = ($page_num - 1) * $limit;

                    // Get Total Count
                    $count_sql = "SELECT COUNT(*) as total FROM quote_requests";
                    $count_res = $conn->query($count_sql);
                    $total_rows = $count_res->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Data
                    $sql = "SELECT * FROM quote_requests ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['product_name']) . "</strong><br>
                                    <small>Qty: " . $row['quantity'] . "</small>
                                  </td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['customer_name']) . "</strong><br>
                                    <small><i class='fas fa-phone'></i> " . htmlspecialchars($row['customer_mobile']) . "</small><br>
                                    <small><i class='fas fa-envelope'></i> " . htmlspecialchars($row['customer_email']) . "</small>
                                  </td>";
                            echo "<td>" . ($row['company_name'] ? htmlspecialchars($row['company_name']) : '-') . "</td>";
                            echo "<td>
                                    " . htmlspecialchars($row['city']) . ", " . htmlspecialchars($row['state']) . "<br>
                                    <small>" . htmlspecialchars($row['pincode']) . "</small>
                                  </td>";
                            echo "<td><span class='status-badge'>" . htmlspecialchars($row['status']) . "</span></td>";
                            echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "</td>";
                            echo "<td>
                                <a href='index.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this Quote Request?');\"><i class='fas fa-trash'></i> Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center; color: #777; padding: 20px;'>No quote requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination" style="margin-top: 20px; text-align: center;">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="btn-page <?php echo ($i == $page_num) ? 'active' : ''; ?>" style="padding: 5px 10px; border: 1px solid #ddd; margin: 0 2px; text-decoration: none; color: #333; <?php echo ($i == $page_num) ? 'background: #004aad; color: #fff; border-color: #004aad;' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
