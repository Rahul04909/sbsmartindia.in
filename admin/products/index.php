<?php
$page = 'products';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Products</h1>
        <a href="add-product.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Product
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
                        <th width="80">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>MRP</th>
                        <th>Sales Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, sc.name as sub_category_name, c.name as category_name, b.name as brand_name 
                            FROM products p 
                            LEFT JOIN product_sub_categories sc ON p.sub_category_id = sc.id 
                            LEFT JOIN product_categories c ON sc.category_id = c.id 
                            LEFT JOIN brands b ON p.brand_id = b.id
                            ORDER BY p.id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status_class = $row['status'] ? 'status-active' : 'status-inactive';
                            $status_text = $row['status'] ? 'Active' : 'Inactive';
                            $image = !empty($row['featured_image']) ? '../../' . $row['featured_image'] : '../../assets/images/no-image.png';
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['featured_image'])): ?>
                                        <img src="<?php echo $image; ?>" alt="Product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    <?php else: ?>
                                        <span style="color: #ccc;"><i class="fas fa-image fa-2x"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    <br>
                                    <small style="color: #777;">Brand: <?php echo htmlspecialchars($row['brand_name']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                    <br>
                                    <small style="color: #777;">&rarr; <?php echo htmlspecialchars($row['sub_category_name']); ?></small>
                                </td>
                                <td>₹<?php echo number_format($row['mrp'], 2); ?></td>
                                <td>₹<?php echo number_format($row['sales_price'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $row['stock']; ?>
                                    </span>
                                </td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="product_handler.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center; color: var(--text-muted); padding: 20px;'>No products found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
