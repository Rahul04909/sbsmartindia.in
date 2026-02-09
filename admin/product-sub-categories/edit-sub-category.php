<?php
$page = 'product-sub-categories';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM product_sub_categories WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Sub-Category not found.";
    exit();
}

$sub_category = $result->fetch_assoc();
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Edit Sub-Category</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Sub-Categories
        </a>
    </div>

    <form action="sub_category_handler.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $sub_category['id']; ?>">
        
        <div class="charts-row">
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Sub-Category Information</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category <span style="color: red;">*</span></label>
                    <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Select Category</option>
                        <?php
                        $cat_sql = "SELECT pc.id, pc.name, b.name as brand_name 
                                    FROM product_categories pc 
                                    LEFT JOIN brands b ON pc.brand_id = b.id 
                                    ORDER BY b.name ASC, pc.name ASC";
                        $cat_result = $conn->query($cat_sql);
                        if ($cat_result->num_rows > 0) {
                            while($cat = $cat_result->fetch_assoc()) {
                                $display_name = $cat['brand_name'] . ' - ' . $cat['name'];
                                $selected = ($cat['id'] == $sub_category['category_id']) ? 'selected' : '';
                                echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($display_name) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sub-Category Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($sub_category['name']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sub-Category Image</label>
                    <?php if($sub_category['image']): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="../../<?php echo $sub_category['image']; ?>" style="max-width: 100px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #646970; display: block; margin-top: 5px;">Leave empty to keep current image. Recommended size: 400x400px</small>
                </div>

                <button type="submit" name="edit_sub_category" class="btn-admin" style="padding: 12px 24px;">
                    <i class="fas fa-save"></i> Update Sub-Category
                </button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
