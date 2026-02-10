<?php
$page = 'products';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Add New Product</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <form action="product_handler.php" method="POST" enctype="multipart/form-data" id="productForm">
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
                            if ($brand_result->num_rows > 0) {
                                while($brand = $brand_result->fetch_assoc()) {
                                    echo "<option value='" . $brand['id'] . "'>" . htmlspecialchars($brand['name']) . "</option>";
                                }
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
                            // Initial Load of Categories
                             $cat_sql = "SELECT id, name, brand_id FROM product_categories ORDER BY name ASC";
                             $cat_result = $conn->query($cat_sql);
                             $categories = [];
                             while($row = $cat_result->fetch_assoc()){
                                 $categories[] = $row;
                             }
                            ?>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sub Category <span style="color: red;">*</span></label>
                        <select name="sub_category_id" id="sub_category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Product Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" id="summernote"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Specifications</label>
                    <textarea name="specifications" id="summernote_specs"></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Pricing & Stock</h3>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">
                        <input type="checkbox" name="is_price_request" id="is_price_request" value="1"> 
                        Price on Request
                    </label>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">MRP (₹)</label>
                    <input type="number" step="0.01" name="mrp" id="mrp" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sales Price (₹) <span style="color: red;">*</span></label>
                    <input type="number" step="0.01" name="sales_price" id="sales_price" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Discount Percentage (%)</label>
                    <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #eee;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Stock Quantity</label>
                    <input type="number" name="stock" value="0" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                 
                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #646970; display: block; margin-top: 5px;">Recommended size: 800x800px</small>
                </div>

                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Gallery Images</label>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                     <small style="color: #646970; display: block; margin-top: 5px;">Hold Ctrl to select multiple images</small>
                </div>
            </div>
            
            <!-- SEO Column -->
            <div class="chart-card" style="grid-column: span 3;">
                <div class="card-header">
                    <h3 class="card-title">SEO Information</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Title</label>
                    <input type="text" name="meta_title" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Description</label>
                    <textarea name="meta_description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
                </div>

                 <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                     <small style="color: #646970; display: block; margin-top: 5px;">Comma separated keywords</small>
                </div>

                <button type="submit" name="add_product" class="btn-admin" style="padding: 12px 24px;">
                    <i class="fas fa-save"></i> Save Product
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
         $('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
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
            $('#sub_category_id').html('<option value="">Select Sub Category</option>');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
