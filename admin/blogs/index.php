<?php
$page = 'blogs';
$url_prefix = '../';
require_once '../includes/header.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image to delete
    $img_sql = "SELECT featured_image FROM blogs WHERE id = $id";
    $img_res = $conn->query($img_sql);
    if($img_res->num_rows > 0) {
        $row = $img_res->fetch_assoc();
        if(!empty($row['featured_image']) && file_exists("../../" . $row['featured_image'])) {
            unlink("../../" . $row['featured_image']);
        }
    }

    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Blog post deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting blog post.";
    }
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Filters
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : '';
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$where_clauses = [];
if ($category_filter) {
    $where_clauses[] = "b.category_id = $category_filter";
}
if ($search_query) {
    $where_clauses[] = "(b.title LIKE '%$search_query%' OR b.content LIKE '%$search_query%')";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

// Pagination
$limit = 10;
$page_num = isset($_GET['p']) ? intval($_GET['p']) : 1;
$offset = ($page_num - 1) * $limit;

$total_sql = "SELECT COUNT(*) as count FROM blogs b $where_sql";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_rows / $limit);
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Blog Posts</h1>
        <a href="add-blog.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Blog
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

    <!-- Filter Section -->
    <div class="table-card" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category</label>
                <select name="category" class="form-control" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Categories</option>
                    <?php
                    $cat_sql = "SELECT id, name FROM blog_categories ORDER BY name ASC";
                    $cat_res = $conn->query($cat_sql);
                    while($cat = $cat_res->fetch_assoc()) {
                        $selected = ($category_filter == $cat['id']) ? 'selected' : '';
                        echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($cat['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Search</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" class="btn-admin" style="padding: 9px 20px;">Filter</button>
            <?php if($category_filter || $search_query): ?>
                <a href="index.php" class="btn-admin" style="background: #6c757d; border-color: #6c757d; padding: 9px 20px; text-decoration: none;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT b.*, c.name as category_name 
                            FROM blogs b 
                            LEFT JOIN blog_categories c ON b.category_id = c.id 
                            $where_sql 
                            ORDER BY b.created_at DESC 
                            LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $image = !empty($row['featured_image']) ? '../../' . $row['featured_image'] : '../../assets/images/no-image.png';
                            $status_class = $row['status'] ? 'status-active' : 'status-inactive';
                            $status_text = $row['status'] ? 'Published' : 'Draft';
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['featured_image'])): ?>
                                        <img src="<?php echo $image; ?>" alt="Blog" style="width: 50px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    <?php else: ?>
                                        <span style="color: #ccc;"><i class="fas fa-image fa-2x"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    <br>
                                    <small style="color: #777;"><?php echo htmlspecialchars($row['slug']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td><i class="fas fa-eye"></i> <?php echo $row['views']; ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-blog.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this blog post?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center; color: var(--text-muted); padding: 20px;'>No blog posts found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="padding: 20px; display: flex; justify-content: center; gap: 5px;">
                <?php if ($page_num > 1): ?>
                    <a href="?p=<?php echo $page_num - 1; ?><?php echo $category_filter ? '&category=' . $category_filter : ''; ?><?php echo $search_query ? '&search=' . $search_query : ''; ?>" class="btn-admin" style="padding: 5px 10px;">&laquo; Prev</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?p=<?php echo $i; ?><?php echo $category_filter ? '&category=' . $category_filter : ''; ?><?php echo $search_query ? '&search=' . $search_query : ''; ?>" class="btn-admin" style="padding: 5px 10px; <?php echo $i == $page_num ? 'background-color: #333; border-color: #333;' : 'background-color: #fff; color: #333;'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page_num < $total_pages): ?>
                     <a href="?p=<?php echo $page_num + 1; ?><?php echo $category_filter ? '&category=' . $category_filter : ''; ?><?php echo $search_query ? '&search=' . $search_query : ''; ?>" class="btn-admin" style="padding: 5px 10px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
