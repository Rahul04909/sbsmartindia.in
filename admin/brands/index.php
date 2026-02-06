<?php
$page = 'brands';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Brands</h1>
        <a href="add-brand.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Brand
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
                        <th width="50">ID</th>
                        <th width="80">Logo</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM brands ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $desc = strip_tags($row['description']);
                            if (strlen($desc) > 50) $desc = substr($desc, 0, 50) . '...';
                            
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>";
                            if ($row['logo']) {
                                echo "<img src='../../" . $row['logo'] . "' alt='" . $row['name'] . "' style='width: 40px; height: 40px; object-fit: contain; border-radius: 4px; border: 1px solid #ddd;'>";
                            } else {
                                echo "<span style='color: #ccc;'><i class='fas fa-image fa-2x'></i></span>";
                            }
                            echo "</td>";
                            echo "<td><strong>" . $row['name'] . "</strong></td>";
                            echo "<td>" . $desc . "</td>";
                            echo "<td>
                                <div class='action-buttons'>
                                    <a href='edit-brand.php?id=" . $row['id'] . "' class='btn-action btn-edit'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='brand_handler.php?delete=" . $row['id'] . "' class='btn-action btn-delete' onclick=\"return confirm('Are you sure you want to delete this brand?');\"><i class='fas fa-trash'></i> Delete</a>
                                </div>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted);'>No brands found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
