<?php
$page = 'blogs';
$url_prefix = '../';
require_once '../includes/header.php';
require_once '../../database/db_config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$blog = $result->fetch_assoc();
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Edit Blog Post</h1>
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
        <input type="hidden" name="id" value="<?php echo $blog['id']; ?>">
        <input type="hidden" name="update_blog" value="1">

        <div class="charts-row">
            <!-- Left Column -->
            <div class="chart-card" style="grid-column: span 2;">
                <div class="card-header">
                    <h3 class="card-title">Blog Content</h3>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Title <span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Slug <span style="color: red;">*</span></label>
                    <input type="text" name="slug" id="slug" value="<?php echo htmlspecialchars($blog['slug']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
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
                            $selected = ($cat['id'] == $blog['category_id']) ? 'selected' : '';
                            echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($cat['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Content <span style="color: red;">*</span></label>
                    <textarea name="content" id="summernote"><?php echo htmlspecialchars($blog['content']); ?></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Schema Markup (JSON-LD)</label>
                    <div style="margin-bottom: 5px;">
                        <small style="color: #666;">Auto-generated. You can manually edit it.</small>
                    </div>
                    <textarea name="schema_markup" id="schema_markup" rows="10" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; background: #f4f4f4;"><?php echo htmlspecialchars($blog['schema_markup']); ?></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="chart-card">
                <div class="card-header">
                    <h3 class="card-title">Image & SEO</h3>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Featured Image</label>
                    <?php if (!empty($blog['featured_image'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="../../<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="Current Image" style="max-width: 100%; height: auto; border-radius: 4px; border: 1px solid #ddd;">
                            <p style="font-size: 0.9em; color: #666;">Current Image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="featured_image" id="imageInput" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <div id="imagePreview" style="margin-top: 10px; display: none;">
                        <img src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 4px; border: 1px solid #ddd;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="1" <?php echo ($blog['status'] == 1) ? 'selected' : ''; ?>>Published</option>
                        <option value="0" <?php echo ($blog['status'] == 0) ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <h4 style="margin-bottom: 15px;">SEO Settings</h4>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="<?php echo htmlspecialchars($blog['meta_title']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"><?php echo htmlspecialchars($blog['meta_description']); ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Meta Keywords</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo htmlspecialchars($blog['meta_keywords']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <button type="submit" name="update_blog" class="btn-admin" style="padding: 12px 24px; width: 100%;">
                    <i class="fas fa-save"></i> Update Blog Post
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

    // Slug Generator
    $('#title').on('input', function() {
        var title = $(this).val();
        var slug = title.toLowerCase()
                       .replace(/[^a-z0-9 -]/g, '') 
                       .replace(/\s+/g, '-')       
                       .replace(/-+/g, '-');       
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

    // We do NOT auto-update Schema here to prevent overwriting manual edits on existing blogs
    // Unless the user wants a "Reset Schema" button, which we can add later if requested.
});
</script>

<?php include '../includes/footer.php'; ?>
