<?php
$page = 'blogs';
$url_prefix = '../';
require_once '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Add New Blog Post</h1>
        <a href="index.php" class="btn-admin" style="background-color: #646970; border-color: #646970;">
            <i class="fas fa-arrow-left"></i> Back to Blogs
        </a>
    </div>

    <!-- Feedback Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error" style="background: #fce8e8; color: #d63638; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="blog_handler.php" method="POST" enctype="multipart/form-data" id="blogForm">
        <div class="charts-row">
            <!-- Left Column -->
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Blog Content</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Slug <span style="color: red;">*</span></label>
                    <input type="text" name="slug" id="slug" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                    <small style="color: #666;">Auto-generated from title</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category <span style="color: red;">*</span></label>
                    <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Select Category</option>
                        <?php
                        $cat_sql = "SELECT id, name FROM blog_categories ORDER BY name ASC";
                        $cat_res = $conn->query($cat_sql);
                        while($cat = $cat_res->fetch_assoc()) {
                            echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Content <span style="color: red;">*</span></label>
                    <textarea name="content" id="summernote"></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Schema Markup (JSON-LD)</label>
                    <div style="margin-bottom: 5px;">
                        <small style="color: #666;">Auto-generated. You can manually edit it.</small>
                    </div>
                    <textarea name="schema_markup" id="schema_markup" rows="10" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; background: #f4f4f4;"></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Image & SEO</h3>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Featured Image</label>
                    <input type="file" name="featured_image" id="imageInput" accept="image/*" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <div id="imagePreview" style="margin-top: 10px; display: none;">
                        <img src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; border: 1px solid #ddd;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="1">Published</option>
                        <option value="0">Draft</option>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <h4 style="margin-bottom: 15px;">SEO Settings</h4>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <button type="submit" name="add_blog" class="btn-admin" style="padding: 12px 24px; width: 100%;">
                    <i class="fas fa-save"></i> Save Blog Post
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
        height: 400,
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

    // Slug Generator & Schema Auto-Update
    $('#title').on('input', function() {
        var title = $(this).val();
        
        // Update Slug
        var slug = title.toLowerCase()
                       .replace(/[^a-z0-9 -]/g, '') 
                       .replace(/\s+/g, '-')       
                       .replace(/-+/g, '-');       
        $('#slug').val(slug);
        
        // Update Meta Title if empty
        if($('#meta_title').val() === '') {
            $('#meta_title').val(title);
        }
        
        updateSchema();
    });
    
    $('#meta_description').on('input', updateSchema);

    // Image Preview
    $('#imageInput').change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#imagePreview img').attr('src', event.target.result);
                $('#imagePreview').show();
                updateSchema(event.target.result); // Pass base64 for preview, but real schema needs URL
            }
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });
    
    function updateSchema(imageUrl = '') {
        var title = $('#title').val();
        var desc = $('#meta_description').val() || '';
        var slug = $('#slug').val();
        var date = new Date().toISOString();
        
        var schema = {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": title,
            "image": imageUrl || "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/default.jpg'; ?>", // Placeholder
            "editor": "Admin", 
            "keywords": $('#meta_keywords').val() || "",
            "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/blog/'; ?>" + slug,
            "datePublished": date,
            "dateCreated": date,
            "dateModified": date,
            "description": desc,
            "articleBody": "Content...", // We don't grab full summernote content here to avoid bloat
            "author": {
                "@type": "Organization",
                "name": "SB Smart India"
            }
        };
        
        // Only update if user hasn't heavily modified it manually? 
        // For now, simple overwrite or init. 
        // Let's just update it.
        $('#schema_markup').val(JSON.stringify(schema, null, 2));
    }
});
</script>

<?php include '../includes/footer.php'; ?>
