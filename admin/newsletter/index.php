<?php
$page = 'newsletter';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';

// Pagination setup
$items_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

// Search Filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_sql = "";
if (!empty($search)) {
    $where_sql = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR mobile LIKE '%$search%'";
}

// Get total count for pagination
$total_sql = "SELECT COUNT(*) as total FROM newsletter_subscriptions $where_sql";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $items_per_page);

// Fetch Subscribers
$sql = "SELECT * FROM newsletter_subscriptions $where_sql ORDER BY id DESC LIMIT $offset, $items_per_page";
$result = $conn->query($sql);
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Newsletter Subscribers</h1>
        <div class="header-actions">
            <!-- Optional: CSV Export Button could go here -->
             <a href="export_csv.php" class="btn-admin" style="background-color: #28a745; border-color: #28a745;">
                <i class="fas fa-file-csv"></i> Export to CSV
            </a>
        </div>
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

    <!-- Search Section -->
    <div class="table-card" style="margin-bottom: 20px; padding: 20px;">
        <form action="" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Search Subscribers</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name, email or mobile..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" class="btn-admin" style="padding: 10px 20px;">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn-admin" style="background: #f8f9fa; color: #333; border: 1px solid #ddd; padding: 10px 20px; text-decoration: none;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Subscribers Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Subscribed Date</th>
                        <th width="150" style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                <td>
                                    <span class="badge" style="padding: 4px 8px; border-radius: 12px; font-size: 0.8em; <?php echo ($row['status'] == 'active') ? 'background: #edfaef; color: #00a32a;' : 'background: #f8f9fa; color: #666; border: 1px solid #ddd;'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                                <td style="text-align: center;">
                                    <div class="action-buttons">
                                        <a href="newsletter_handler.php?toggle_status=<?php echo $row['id']; ?>&current=<?php echo $row['status']; ?>" class="btn-action btn-edit" title="Toggle Status">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                        <a href="newsletter_handler.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this subscriber?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px; color: #666;">No subscribers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination" style="display: flex; justify-content: center; padding: 20px; gap: 5px;">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="page-link <?php echo ($i == $current_page) ? 'active' : ''; ?>" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: <?php echo ($i == $current_page) ? '#fff' : '#333'; ?>; background: <?php echo ($i == $current_page) ? '#004aad' : '#fff'; ?>;">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($search); ?>" class="page-link" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
