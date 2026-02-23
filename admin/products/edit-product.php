<?php
$page = 'products';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';

// Fetch Product Data
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$product_sql = "SELECT * FROM products WHERE id = $id";
$product_res = $conn->query($product_sql);

if ($product_res->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$product = $product_res->fetch_assoc();

// Fetch Gallery Images
$gallery_sql = "SELECT * FROM product_images WHERE product_id = $id";
$gallery_res = $conn->query($gallery_sql);
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Edit Product: <?php echo htmlspecialchars($product['title']); ?></h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <form action="product_handler.php" method="POST" enctype="multipart/form-data" id="productForm">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        
        <div class="charts-row">
            <!-- Left Column -->
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
                </div>
                
                <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Brand <span style="color: red;">*</span></label>
                        <select name="brand_id" id="brand_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Brand</option>
                            <?php
                            $brand_sql = "SELECT id, name FROM brands ORDER BY name ASC";
                            $brand_result = $conn->query($brand_sql);
                            while($brand = $brand_result->fetch_assoc()) {
                                $selected = ($brand['id'] == $product['brand_id']) ? 'selected' : '';
                                echo "<option value='" . $brand['id'] . "' $selected>" . htmlspecialchars($brand['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                   <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category <span style="color: red;">*</span></label>
                        <select name="category_id" id="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Category</option>
                            <?php 
                             $cat_sql = "SELECT id, name, brand_id FROM product_categories ORDER BY name ASC";
                             $cat_result = $conn->query($cat_sql);
                             $categories = [];
                             while($row = $cat_result->fetch_assoc()){
                                 $categories[] = $row;
                                 $selected = ($row['id'] == $product['category_id']) ? 'selected' : '';
                                 // Only show categories for current brand initially (or let JS handle it, but for SSR we pre-select)
                                 if($row['brand_id'] == $product['brand_id']) {
                                     echo "<option value='".$row['id']."' $selected>".htmlspecialchars($row['name'])."</option>";
                                 }
                             }
                            ?>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sub Category <span style="color: #999; font-weight: normal;">(Optional)</span></label>
                        <select name="sub_category_id" id="sub_category_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Sub Category</option>
                            <?php
                            if($product['category_id']) {
                                $sub_sql = "SELECT id, name FROM product_sub_categories WHERE category_id = ".$product['category_id']." ORDER BY name ASC";
                                $sub_res = $conn->query($sub_sql);
                                while($sub = $sub_res->fetch_assoc()) {
                                    $selected = ($sub['id'] == $product['sub_category_id']) ? 'selected' : '';
                                    echo "<option value='".$sub['id']."' $selected>".htmlspecialchars($sub['name'])."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Product Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">SKU</label>
                        <input type="text" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">HSN Code</label>
                        <input type="text" name="hsn_code" value="<?php echo htmlspecialchars($product['hsn_code']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" id="summernote"><?php echo $product['description']; ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Specifications</label>
                    <textarea name="specifications" id="summernote_specs"><?php echo $product['specifications']; ?></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Pricing & Stock</h3>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">
                        <input type="checkbox" name="is_price_request" id="is_price_request" value="1" <?php echo $product['is_price_request'] ? 'checked' : ''; ?>> 
                        Price on Request
                    </label>
                </div>

                <?php $disabled = $product['is_price_request'] ? 'disabled' : ''; ?>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">MRP (₹)</label>
                    <input type="number" step="0.01" name="mrp" id="mrp" value="<?php echo $product['mrp']; ?>" <?php echo $disabled; ?> style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sales Price (₹) <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" name="sales_price" id="sales_price" value="<?php echo $product['sales_price']; ?>" <?php echo $disabled; ?> <?php echo !$product['is_price_request'] ? 'required' : ''; ?> style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Discount Percentage (%)</label>
                    <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" value="<?php echo $product['discount_percentage']; ?>" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #eee;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Stock Quantity</label>
                    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                 
                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Featured Image</label>
                    <?php if($product['featured_image']): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="../../<?php echo $product['featured_image']; ?>" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="featured_image" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #646970; display: block; margin-top: 5px;">Recommended size: 800x800px. Leave empty to keep current image.</small>
                </div>

                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Gallery Images</label>
                    <?php if($gallery_res->num_rows > 0): ?>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                            <?php while($g_img = $gallery_res->fetch_assoc()): ?>
                                <div style="position: relative;">
                                    <img src="../../<?php echo $g_img['image_path']; ?>" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                                    <a href="product_handler.php?delete_image=<?php echo $g_img['id']; ?>&product_id=<?php echo $id; ?>" 
                                       onclick="return confirm('Delete this image?')"
                                       style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; text-decoration: none;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                     <small style="color: #646970; display: block; margin-top: 5px;">You can upload more images. Hold Ctrl to select multiple.</small>
                </div>
            </div>
            
            <!-- SEO Column -->
            <div class="chart-card" style="grid-column: span 3;">
                <div class="card-header">
                    <h3 class="card-title">SEO Information</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Title</label>
                    <input type="text" name="meta_title" value="<?php echo htmlspecialchars($product['meta_title']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Description</label>
                    <textarea name="meta_description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"><?php echo htmlspecialchars($product['meta_description']); ?></textarea>
                </div>

                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($product['meta_keywords']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                     <small style="color: #646970; display: block; margin-top: 5px;">Comma separated keywords</small>
                </div>

                <button type="submit" name="update_product" class="btn-admin" style="padding: 12px 24px; background-color: #0d6efd; border-color: #0d6efd;">
                    <i class="fas fa-save"></i> Update Product
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Libraries for Summernote and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- Scripts for Dynamic Behavior -->
<script>
$(document).ready(function() {
    // Summernote
    $('#summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#summernote_specs').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Discount Calculation
    $('#mrp, #sales_price').on('input', function() {
        var mrp = parseFloat($('#mrp').val()) || 0;
        var sales = parseFloat($('#sales_price').val()) || 0;
        
        if(mrp > 0 && sales > 0 && mrp > sales) {
            var discount = ((mrp - sales) / mrp) * 100;
            $('#discount_percentage').val(discount.toFixed(2));
        } else {
            $('#discount_percentage').val(0);
        }
    });

    // Price on Request Toggle
    $('#is_price_request').change(function() {
        if($(this).is(':checked')) {
            $('#mrp, #sales_price').prop('disabled', true).val('');
            $('#mrp, #sales_price').prop('required', false);
            $('#discount_percentage').val('');
        } else {
            $('#mrp, #sales_price').prop('disabled', false);
             $('#sales_price').prop('required', true);
        }
    });

    // Validations: Sales price should not be greater than MRP
    $('#productForm').on('submit', function(e) {
        if(!$('#is_price_request').is(':checked')) {
            var mrp = parseFloat($('#mrp').val()) || 0;
            var sales = parseFloat($('#sales_price').val()) || 0;
    
            if (sales > mrp && mrp > 0) {
                alert('Sales Price cannot be greater than MRP');
                e.preventDefault();
            }
        }
    });
    
    // JS side data for categories
    const categories = <?php echo json_encode($categories); ?>;

    $('#brand_id').change(function(){
        const brandId = $(this).val();
        const catSelect = $('#category_id');
        catSelect.empty().append('<option value="">Select Category</option>');
        
        if(brandId) {
             const filtered = categories.filter(c => c.brand_id == brandId);
             filtered.forEach(c => {
                 catSelect.append(`<option value="${c.id}">${c.name}</option>`);
             });
        }
         $('#sub_category_id').empty().append('<option value="">Select Sub Category (Optional)</option>');
    });

    $('#category_id').change(function() {
        var category_id = $(this).val();
        if(category_id) {
            $.ajax({
                url: 'get_sub_categories.php',
                type: 'POST',
                data: {category_id: category_id},
                success: function(response) {
                    $('#sub_category_id').html(response);
                }
            });
        } else {
            $('#sub_category_id').html('<option value="">Select Sub Category (Optional)</option>');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
