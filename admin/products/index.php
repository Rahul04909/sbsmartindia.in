<?php
$page = 'products';
$url_prefix = '../';
include '../includes/header.php';
require_once '../../database/db_config.php';

// Pagination setup
$items_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

// Filter setup
$brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$sub_category_id = isset($_GET['sub_category_id']) ? (int)$_GET['sub_category_id'] : 0;

$where_clauses = [];
if ($brand_id > 0) $where_clauses[] = "p.brand_id = $brand_id";
if ($category_id > 0) $where_clauses[] = "p.category_id = $category_id";
if ($sub_category_id > 0) $where_clauses[] = "p.sub_category_id = $sub_category_id";

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Get total count for pagination
$total_sql = "SELECT COUNT(*) as total FROM products p $where_sql";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $items_per_page);

// Current URL for pagination persistence
$query_string = $_GET;
unset($query_string['page']);
$base_pagination_url = '?' . http_build_query($query_string);
if (count($query_string) > 0) $base_pagination_url .= '&';
else $base_pagination_url = '?';
?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Products</h1>
        <a href="add-product.php" class="btn-admin">
            <i class="fas fa-plus"></i> Add New Product
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

    <!-- Bulk Import Section -->
    <div class="table-card" style="margin-bottom: 20px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid #eee;">
            <h3 class="card-title" style="margin: 0;">Bulk Import Products</h3>
            <button type="button" class="btn-admin" onclick="document.getElementById('importFormContainer').style.display = document.getElementById('importFormContainer').style.display === 'none' ? 'block' : 'none';" style="background: #f8f9fa; color: #333; border: 1px solid #ddd;">
                <i class="fas fa-chevron-down"></i> Toggle Import
            </button>
        </div>
        <div id="importFormContainer" style="padding: 20px; display: none;">
            <form action="import_handler.php" method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Upload Excel File (.xlsx)</label>
                    <input type="file" name="import_file" accept=".xlsx, .xls" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div>
                    <button type="submit" name="import_products" class="btn-admin" style="padding: 10px 20px;">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    <a href="download_sample.php" class="btn-admin" style="background-color: #28a745; border-color: #28a745; padding: 10px 20px; margin-left: 10px; text-decoration: none; display: inline-block;">
                        <i class="fas fa-download"></i> Download Sample
                    </a>
                </div>
            </form>
             <div style="margin-top: 10px; color: #666; font-size: 0.9em;">
                <i class="fas fa-info-circle"></i> Please download the sample file to see the required format. Ensure Brand, Category, and Sub Category names match exactly with existing records.
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="table-card" style="margin-bottom: 20px; padding: 20px;">
        <form action="" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;" id="filterForm">
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Brand</label>
                <select name="brand_id" id="brand_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Brands</option>
                    <?php
                    $brands_res = $conn->query("SELECT id, name FROM brands ORDER BY name ASC");
                    while($b = $brands_res->fetch_assoc()) {
                        $selected = ($brand_id == $b['id']) ? 'selected' : '';
                        echo "<option value='{$b['id']}' $selected>".htmlspecialchars($b['name'])."</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Category</label>
                <select name="category_id" id="category_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Categories</option>
                    <?php
                    $cat_where = $brand_id > 0 ? "WHERE brand_id = $brand_id" : "";
                    $cats_res = $conn->query("SELECT id, name FROM product_categories $cat_where ORDER BY name ASC");
                    while($c = $cats_res->fetch_assoc()) {
                        $selected = ($category_id == $c['id']) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $selected>".htmlspecialchars($c['name'])."</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500;">Sub Category</label>
                <select name="sub_category_id" id="sub_category_id" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">All Sub Categories</option>
                    <?php
                    if ($category_id > 0) {
                        $scs_res = $conn->query("SELECT id, name FROM product_sub_categories WHERE category_id = $category_id ORDER BY name ASC");
                        while($sc = $scs_res->fetch_assoc()) {
                            $selected = ($sub_category_id == $sc['id']) ? 'selected' : '';
                            echo "<option value='{$sc['id']}' $selected>".htmlspecialchars($sc['name'])."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-admin" style="padding: 8px 15px;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="index.php" class="btn-admin" style="padding: 8px 15px; background: #f8f9fa; color: #333; border: 1px solid #ddd; text-decoration: none;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="wp-list-table">
                <thead>
                    <tr>
                        <th width="80">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>MRP</th>
                        <th>Sales Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, sc.name as sub_category_name, c.name as category_name, b.name as brand_name 
                            FROM products p 
                            LEFT JOIN product_sub_categories sc ON p.sub_category_id = sc.id 
                            LEFT JOIN product_categories c ON p.category_id = c.id 
                            LEFT JOIN brands b ON p.brand_id = b.id
                            $where_sql
                            ORDER BY p.id DESC
                            LIMIT $items_per_page OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status_class = $row['status'] ? 'status-active' : 'status-inactive';
                            $status_text = $row['status'] ? 'Active' : 'Inactive';
                            $image = !empty($row['featured_image']) ? '../../' . $row['featured_image'] : '../../assets/images/no-image.png';
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['featured_image'])): ?>
                                        <img src="<?php echo $image; ?>" alt="Product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                    <?php else: ?>
                                        <span style="color: #ccc;"><i class="fas fa-image fa-2x"></i></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                    <br>
                                    <small style="color: #777;">Brand: <?php echo htmlspecialchars($row['brand_name']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                    <br>
                                    <?php if(!empty($row['sub_category_name'])): ?>
                                        <small style="color: #777;">&rarr; <?php echo htmlspecialchars($row['sub_category_name']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['is_price_request']): ?>
                                        <span class="badge bg-info">Price on Request</span>
                                    <?php else: ?>
                                        ₹<?php echo number_format($row['mrp'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['is_price_request']): ?>
                                        -
                                    <?php else: ?>
                                        ₹<?php echo number_format($row['sales_price'], 2); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $row['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $row['stock']; ?>
                                    </span>
                                </td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="product_handler.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align: center; color: var(--text-muted); padding: 20px;'>No products found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination-container">
            <div class="pagination-info">
                Showing <?php echo min($offset + 1, $total_rows); ?> to <?php echo min($offset + $items_per_page, $total_rows); ?> of <?php echo $total_rows; ?> products
            </div>
            <ul class="pagination">
                <?php if ($current_page > 1): ?>
                    <li><a href="<?php echo $base_pagination_url; ?>page=<?php echo $current_page - 1; ?>" class="pagination-link"><i class="fas fa-chevron-left"></i></a></li>
                <?php endif; ?>

                <?php
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);

                if ($start_page > 1) {
                    echo '<li><a href="'.$base_pagination_url.'page=1" class="pagination-link">1</a></li>';
                    if ($start_page > 2) echo '<li><span class="pagination-link disabled">...</span></li>';
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active = ($i == $current_page) ? 'active' : '';
                    echo '<li><a href="'.$base_pagination_url.'page=' . $i . '" class="pagination-link ' . $active . '">' . $i . '</a></li>';
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) echo '<li><span class="pagination-link disabled">...</span></li>';
                    echo '<li><a href="'.$base_pagination_url.'page=' . $total_pages . '" class="pagination-link">' . $total_pages . '</a></li>';
                }
                ?>

                <?php if ($current_page < $total_pages): ?>
                    <li><a href="<?php echo $base_pagination_url; ?>page=<?php echo $current_page + 1; ?>" class="pagination-link"><i class="fas fa-chevron-right"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Brand change -> Filter categories (Optional refinement, but let's keep it simple for now as requested)
    // For now, let's just implement category change -> subcategory loading which is already working in add-product.php

    $('#category_id').change(function() {
        var category_id = $(this).val();
        if(category_id) {
            $.ajax({
                url: 'get_sub_categories.php',
                type: 'POST',
                data: {category_id: category_id},
                success: function(response) {
                    $('#sub_category_id').html(response.replace('Select Sub Category', 'All Sub Categories'));
                }
            });
        } else {
            $('#sub_category_id').html('<option value="">All Sub Categories</option>');
        }
    });

    // Optional: On Brand change, we could filter categories, but it requires categories to be brand-linked correctly.
    // Given the complexity of dynamic dropdowns, let's ensure subcategory is at least dynamic.
});
</script>
