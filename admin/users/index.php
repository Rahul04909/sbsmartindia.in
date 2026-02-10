<?php
$page = 'users';
$url_prefix = '../'; // Points to Admin Root
require_once '../../database/db_config.php';

include '../includes/header.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Registered Users</h1>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Name</th>
                        <th>Contact Info</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pagination Setup
                    $limit = 10;
                    $page_num = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $offset = ($page_num - 1) * $limit;

                    // Get Total Count
                    $count_sql = "SELECT COUNT(*) as total FROM users";
                    $count_res = $conn->query($count_sql);
                    $total_rows = $count_res->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Data
                    $sql = "SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                            echo "<td>
                                    <div style='display:flex; align-items:center; gap:5px; margin-bottom:3px;'>
                                        <i class='fas fa-envelope' style='width:15px; color:#555;'></i>
                                        <span>" . htmlspecialchars($row['email']) . "</span>
                                    </div>
                                    <div style='display:flex; align-items:center; gap:5px;'>
                                        <i class='fas fa-phone' style='width:15px; color:#555;'></i>
                                        <span>" . htmlspecialchars($row['phone']) . "</span>
                                    </div>
                                  </td>";
                            echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center; color: #777; padding: 20px;'>No users found.</td></tr>";
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
