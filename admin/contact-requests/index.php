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

<style>
    .contact-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .contact-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .contact-info h3 {
        margin: 0 0 5px 0;
        color: #1e293b;
        font-size: 18px;
    }
    .contact-meta {
        font-size: 13px;
        color: #64748b;
        display: flex;
        gap: 15px;
    }
    .contact-meta span i {
        margin-right: 5px;
        color: #004aad;
    }
    .contact-message {
        background: #f8fafc;
        padding: 15px;
        border-radius: 6px;
        border-left: 4px solid #004aad;
        font-size: 14px;
        color: #334155;
        line-height: 1.6;
    }
    .badge-date {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .btn-delete-simple {
        color: #ef4444;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }
    .btn-delete-simple:hover {
        text-decoration: underline;
    }
</style>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Contact Requests</h1>
        <p style="color: #64748b; margin-top: 5px;">Manage messages received from the Contact Us form.</p>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success" style="background: #ecfdf5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
            <i class="fas fa-check-circle"></i> Contact request deleted successfully.
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-error" style="background: #fff1f2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="requests-list">
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
                ?>
                <div class="contact-card">
                    <div class="contact-item-header">
                        <div class="contact-info">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <div class="contact-meta">
                                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge-date"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></span>
                            <div style="margin-top: 10px;">
                                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-delete-simple" onclick="return confirm('Are you sure you want to delete this message?');">
                                    <i class="fas fa-trash-alt"></i> Delete Message
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-message">
                        <strong>Message:</strong><br>
                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div style='text-align: center; padding: 60px; background: #fff; border-radius: 8px; border: 1px dashed #cbd5e1;'>
                    <i class='fas fa-inbox' style='font-size: 40px; color: #cbd5e1; margin-bottom: 15px;'></i>
                    <h3 style='color: #64748b;'>No contact requests found</h3>
                    <p style='color: #94a3b8;'>Messages from the Contact Us form will appear here.</p>
                  </div>";
        }
        ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination" style="margin-top: 30px; display: flex; justify-content: center; gap: 8px;">
            <?php if($page_num > 1): ?>
                <a href="?page=<?php echo ($page_num-1); ?>" style="padding: 8px 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; font-weight: 500;">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" style="padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; <?php echo ($i == $page_num) ? 'background: #004aad; color: #fff; border: 1px solid #004aad;' : 'background: #fff; border: 1px solid #e2e8f0; color: #475569;'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if($page_num < $total_pages): ?>
                <a href="?page=<?php echo ($page_num+1); ?>" style="padding: 8px 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; text-decoration: none; color: #475569; font-weight: 500;">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
