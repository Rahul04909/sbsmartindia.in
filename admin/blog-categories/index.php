<?php
$page = 'blog_categories';
$url_prefix = '../';
require_once '../includes/header.php';
require_once '../../database/db_config.php';

// Handle Delete Action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Put delete logic here or in a separate handler
    // For now, simple delete
    $stmt = $conn->prepare("DELETE FROM blog_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Category deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting category.";
    }
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Blog Categories</h1>
        <a href="add-category.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Category
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

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM blog_categories ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $image = !empty($row['image']) ? '../../' . $row['image'] : '../../assets/images/no-image.png';
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="<?php echo $image; ?>" alt="Category" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    <?php else: ?>
                                        <span style="color: #ccc;"><i class="fas fa-image fa-2x"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['slug']); ?></td>
                                <td><?php echo substr(strip_tags($row['description']), 0, 50) . '...'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-category.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted); padding: 20px;'>No categories found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
