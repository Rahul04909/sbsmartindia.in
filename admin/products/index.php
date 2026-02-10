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

    <div class="charts-row">
        <div class="chart-card" style="grid-column: span 2;">
            <div class="card-header">
                <h3 class="card-title">All Products</h3>
            </div>
            
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>MRP</th>
                            <th>Sales Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, sc.name as sub_category_name, c.name as category_name 
                                FROM products p 
                                LEFT JOIN product_sub_categories sc ON p.sub_category_id = sc.id 
                                LEFT JOIN product_categories c ON sc.category_id = c.id 
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
                                        <img src="<?php echo $image; ?>" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    </td>
                                    <td>
                                        <small><?php echo htmlspecialchars($row['category_name']); ?></small><br>
                                        <?php echo htmlspecialchars($row['sub_category_name']); ?>
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
                                            <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn-action edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="product_handler.php?delete=<?php echo $row['id']; ?>" class="btn-action delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
