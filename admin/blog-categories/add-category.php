<?php
$page = 'blog_categories';
$url_prefix = '../';
require_once '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Add New Blog Category</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="background: #fce8e8; color: #d63638; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="category_handler.php" method="POST" enctype="multipart/form-data" id="categoryForm">
        <div class="charts-row">
            <!-- Left Column -->
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Category Information</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Slug <span style="color: red;">*</span></label>
                    <input type="text" name="slug" id="slug" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #666;">Auto-generated from name</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" id="summernote"></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Image & SEO</h3>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category Image</label>
                    <input type="file" name="image" id="imageInput" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <div id="imagePreview" style="margin-top: 10px; display: none;">
                        <img src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; border: 1px solid #ddd;">
                    </div>
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
                </div>

                <button type="submit" name="add_category" class="btn-admin" style="padding: 12px 24px; width: 100%;">
                    <i class="fas fa-save"></i> Save Category
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Libraries for Summernote and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Summernote
    $('#summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Slug Generator
    $('#name').on('input', function() {
        var name = $(this).val();
        var slug = name.toLowerCase()
                       .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                       .replace(/\s+/g, '-')       // collapse whitespace and replace by -
                       .replace(/-+/g, '-');       // collapse dashes
        $('#slug').val(slug);
    });

    // Image Preview
    $('#imageInput').change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#imagePreview img').attr('src', event.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>
