<?php
$page = 'bulk-quotes';
$url_prefix = '../'; // Points to Admin Root
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Fetch file path to delete file
    $file_sql = "SELECT file_path FROM bulk_quotes WHERE id = $delete_id";
    $file_res = $conn->query($file_sql);
    if ($file_res->num_rows > 0) {
        $file_row = $file_res->fetch_assoc();
        if (!empty($file_row['file_path']) && file_exists('../../' . $file_row['file_path'])) {
            unlink('../../' . $file_row['file_path']);
        }
    }

    $del_sql = "DELETE FROM bulk_quotes WHERE id = $delete_id";
    if ($conn->query($del_sql) === TRUE) {
        header("Location: bulk-quotes.php?msg=deleted");
        exit();
    } else {
        $error = "Error deleting record: " . $conn->error;
    }
}

// Handle Status Update
if (isset($_POST['update_status'])) {
    $quote_id = intval($_POST['quote_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $update_sql = "UPDATE bulk_quotes SET status = '$status' WHERE id = $quote_id";
    if ($conn->query($update_sql) === TRUE) {
        $success = "Status updated successfully.";
    } else {
        $error = "Error updating status: " . $conn->error;
    }
}

include '../includes/header.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Bulk Quote Requests</h1>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            Bulk quote request deleted successfully.
        </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $success; ?>
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
                        <th>User Details</th>
                        <th>Message</th>
                        <th>File</th>
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
                    $count_sql = "SELECT COUNT(*) as total FROM bulk_quotes";
                    $count_res = $conn->query($count_sql);
                    $total_rows = $count_res->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Data
                    $sql = "SELECT b.*, u.name as user_name, u.email as user_email 
                            FROM bulk_quotes b 
                            LEFT JOIN users u ON b.user_id = u.id 
                            ORDER BY b.id DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['user_name']) . "</strong><br>
                                    <div style='display:flex; align-items:center; gap:5px; margin-top:3px;'>
                                        <i class='fas fa-envelope' style='width:15px; color:#555;'></i>
                                        <small>" . htmlspecialchars($row['user_email']) . "</small>
                                    </div>
                                  </td>";
                            echo "<td>
                                    <div style='max-width: 200px; overflow:hidden; text-overflow: ellipsis;' title='" . htmlspecialchars($row['message']) . "'>
                                        " . htmlspecialchars($row['message']) . "
                                    </div>
                                  </td>";
                            echo "<td>";
                            if (!empty($row['file_path']) && file_exists('../../' . $row['file_path'])) {
                                echo "<a href='../../" . $row['file_path'] . "' target='_blank' style='color:#004aad; text-decoration:none; font-weight:500;' download>
                                        <i class='fas fa-download'></i> Download
                                      </a>";
                            } else {
                                echo "<span style='color:#999;'>No File</span>";
                            }
                            echo "</td>";
                            
                            // Status Dropdown Form
                            $status = $row['status'];
                            echo "<td>
                                    <form method='POST' style='display:flex; gap:5px;'>
                                        <input type='hidden' name='quote_id' value='" . $row['id'] . "'>
                                        <select name='status' style='padding:4px; border:1px solid #ddd; border-radius:4px; font-size:12px;' onchange='this.form.submit()'>
                                            <option value='pending' " . ($status == 'pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='approved' " . ($status == 'approved' ? 'selected' : '') . ">Approved</option>
                                            <option value='rejected' " . ($status == 'rejected' ? 'selected' : '') . ">Rejected</option>
                                            <option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Completed</option>
                                        </select>
                                        <input type='hidden' name='update_status' value='1'>
                                    </form>
                                  </td>";
                                  
                            echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "</td>";
                            echo "<td>
                                <a href='bulk-quotes.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this Bulk Quote?');\"><i class='fas fa-trash'></i> Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center; color: #777; padding: 20px;'>No bulk quote requests found.</td></tr>";
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
