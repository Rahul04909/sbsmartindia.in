<?php
$page = 'product-categories';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Add New Category</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    <form action="category_handler.php" method="POST" enctype="multipart/form-data">
        <div class="charts-row">
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Category Information</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Brand <span style="color: red;">*</span></label>
                    <select name="brand_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Select Brand</option>
                        <?php
                        $brand_sql = "SELECT id, name FROM brands ORDER BY name ASC";
                        $brand_result = $conn->query($brand_sql);
                        if ($brand_result->num_rows > 0) {
                            while($brand = $brand_result->fetch_assoc()) {
                                echo "<option value='" . $brand['id'] . "'>" . $brand['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category Image</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #646970; display: block; margin-top: 5px;">Recommended size: 400x400px</small>
                </div>

                <button type="submit" name="add_category" class="btn-admin" style="padding: 12px 24px;">
                    <i class="fas fa-save"></i> Save Category
                </button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
