<?php
$page = 'product-sub-categories';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Product Sub-Categories</h1>
        <a href="add-sub-category.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Sub-Category
        </a>
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
                        <th width="80">Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT psc.*, pc.name as category_name, b.name as brand_name 
                            FROM product_sub_categories psc 
                            LEFT JOIN product_categories pc ON psc.category_id = pc.id 
                            LEFT JOIN brands b ON pc.brand_id = b.id
                            ORDER BY psc.id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>";
                            if ($row['image']) {
                                echo "<img src='../../" . $row['image'] . "' alt='" . $row['name'] . "' style='width: 40px; height: 40px; object-fit: contain; border-radius: 4px; border: 1px solid #ddd;'>";
                            } else {
                                echo "<span style='color: #ccc;'><i class='fas fa-image fa-2x'></i></span>";
                            }
                            echo "</td>";
                            echo "<td><strong>" . $row['name'] . "</strong></td>";
                            echo "<td>" . ($row['category_name'] ? $row['category_name'] : 'No Category') . "</td>";
                            echo "<td><span class='status-badge status-pending'>" . ($row['brand_name'] ? $row['brand_name'] : 'No Brand') . "</span></td>";
                            echo "<td>
                                <div class='action-buttons'>
                                    <a href='edit-sub-category.php?id=" . $row['id'] . "' class='btn-action btn-edit'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='sub_category_handler.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this sub-category?');\"><i class='fas fa-trash'></i> Delete</a>
                                </div>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: var(--text-muted);'>No sub-categories found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
