<?php
$page = 'brands';
$url_prefix = '../';
include '../includes/header.php';
?>
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Add New Brand</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Brands
        </a>
    </div>

    <form action="brand_handler.php" method="POST" enctype="multipart/form-data">
        <div class="charts-row">
            <!-- Left Column: Main Info -->
            <div class="chart-card" style="grid-column: span 1;">
                <div class="card-header">
                    <h3 class="card-title">Brand Information</h3>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Brand Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea id="summernote" name="description"></textarea>
                </div>
            </div>

            <!-- Right Column: Logo & SEO -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Logo & SEO</h3>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Brand Logo</label>
                    <input type="file" name="logo" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #646970; display: block; margin-top: 5px;">Recommended size: 200x200px</small>
                </div>

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                
                <h4 style="margin-bottom: 15px; font-size: 14px; font-weight: 600;">SEO Settings</h4>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px;">Meta Title</label>
                    <input type="text" name="meta_title" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px;">Meta Description</label>
                    <textarea name="meta_description" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-size: 13px;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" placeholder="comma, separated, keywords">
                </div>

                <button type="submit" name="add_brand" class="btn-admin" style="width: 100%; justify-content: center; padding: 12px;">
                    <i class="fas fa-save"></i> Save Brand
                </button>
            </div>
        </div>
    </form>
</div>

<!-- jQuery and Summernote JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $('#summernote').summernote({
        placeholder: 'Enter brand description...',
        tabsize: 2,
        height: 200,
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
</script>

<?php include '../includes/footer.php'; ?>
