<?php
$page = 'enquiries';
$url_prefix = '../'; // Points to Admin Root
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del_sql = "DELETE FROM product_enquiries WHERE id = $delete_id";
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
        <h1 class="page-title">Product Enquiries</h1>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success" style="background: #edfaef; color: #00a32a; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            Enquiry deleted successfully.
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
                        <th>Customer Details</th>
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
                    $count_sql = "SELECT COUNT(*) as total FROM product_enquiries";
                    $count_res = $conn->query($count_sql);
                    $total_rows = $count_res->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                    // Fetch Data with Product Name
                    $sql = "SELECT e.*, p.name as product_name, p.image as product_image 
                            FROM product_enquiries e 
                            LEFT JOIN products p ON e.product_id = p.id 
                            ORDER BY e.created_at DESC 
                            LIMIT $limit OFFSET $offset";
                    
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Product Image Fallback
                            $prod_img = (!empty($row['product_image']) && file_exists('../../' . $row['product_image'])) ? '../../' . $row['product_image'] : '../../assets/images/placeholder.jpg';
                            
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>
                                    <div style='display:flex; gap:10px; align-items:center;'>
                                        <div style='width:40px; height:40px; background:#f9f9f9; border-radius:4px; overflow:hidden; flex-shrink:0;'>
                                            <img src='$prod_img' style='width:100%; height:100%; object-fit:cover;'>
                                        </div>
                                        <div>
                                            <strong>" . ($row['product_name'] ? htmlspecialchars($row['product_name']) : 'Unknown Product') . "</strong>
                                        </div>
                                    </div>
                                  </td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                                    <div style='display:flex; align-items:center; gap:5px; margin-top:3px;'>
                                        <i class='fas fa-phone' style='width:15px; color:#555; font-size:12px;'></i>
                                        <small>" . htmlspecialchars($row['mobile']) . "</small>
                                    </div>
                                    <div style='display:flex; align-items:center; gap:5px; margin-top:3px;'>
                                        <i class='fas fa-envelope' style='width:15px; color:#555; font-size:12px;'></i>
                                        <small>" . htmlspecialchars($row['email']) . "</small>
                                    </div>
                                  </td>";
                            echo "<td>
                                    <div style='max-width:300px; font-size:13px; color:#555; line-height:1.4;'>
                                        " . nl2br(htmlspecialchars($row['message'])) . "
                                    </div>
                                  </td>";
                            echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "<br><small>" . date('h:i A', strtotime($row['created_at'])) . "</small></td>";
                            echo "<td>
                                <a href='index.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this enquiry?');\"><i class='fas fa-trash'></i> Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #777; padding: 20px;'>No product enquiries found.</td></tr>";
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
