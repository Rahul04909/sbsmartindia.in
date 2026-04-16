<?php
$page = 'contact-requests';
$url_prefix = '../'; // Points to Admin Root
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del_sql = "DELETE FROM contact_requests WHERE id = $delete_id";
    if ($conn->query($del_sql) === TRUE) {
        header("Location: index.php?msg=deleted");
        exit();
    } else {
        $error = "Error deleting record: " . $conn->error;
    }
}

// Ensure Table Exists (Safety Check)
$table_check_sql = "CREATE TABLE IF NOT EXISTS contact_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($table_check_sql);

include '../includes/header.php';
?>

<div class="admin-content">
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="page-title">Contact Requests</h1>
            <p style="color: #64748b; margin-top: 5px;">Manage messages received from the Contact Us form.</p>
        </div>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            Contact request deleted successfully.
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
                        <th>Name</th>
                        <th>Contact Details</th>
                        <th>Message</th>
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
                    $count_sql = "SELECT COUNT(*) as total FROM contact_requests";
                    $count_res = $conn->query($count_sql);
                    $total_rows = $count_res->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Data
                    $sql = "SELECT * FROM contact_requests ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                            echo "<td>
                                    <div style='display:flex; align-items:center; gap:8px;'>
                                        <i class='fas fa-envelope' style='color:#004aad; font-size:12px; width:15px;'></i>
                                        <span>" . htmlspecialchars($row['email']) . "</span>
                                    </div>
                                    <div style='display:flex; align-items:center; gap:8px; margin-top:4px;'>
                                        <i class='fas fa-phone' style='color:#004aad; font-size:12px; width:15px;'></i>
                                        <span>" . htmlspecialchars($row['phone']) . "</span>
                                    </div>
                                  </td>";
                            echo "<td>
                                    <div style='max-width:350px; font-size:13px; color:#555; line-height:1.5;'>
                                        " . nl2br(htmlspecialchars($row['message'])) . "
                                    </div>
                                  </td>";
                            echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "<br><small style='color:#888;'>" . date('h:i A', strtotime($row['created_at'])) . "</small></td>";
                            echo "<td>
                                <a href='index.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this message?');\"><i class='fas fa-trash'></i> Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #777; padding: 30px;'>No contact requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination" style="margin-top: 20px; text-align: center; display: flex; justify-content: center; gap: 5px;">
                <?php if($page_num > 1): ?>
                    <a href="?page=<?php echo ($page_num-1); ?>" class="btn-page" style="padding: 5px 10px; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px;">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="btn-page <?php echo ($i == $page_num) ? 'active' : ''; ?>" style="padding: 5px 10px; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px; <?php echo ($i == $page_num) ? 'background: #004aad; color: #fff; border-color: #004aad;' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if($page_num < $total_pages): ?>
                    <a href="?page=<?php echo ($page_num+1); ?>" class="btn-page" style="padding: 5px 10px; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<!-- footer code included here -->
