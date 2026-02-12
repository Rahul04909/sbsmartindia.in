<?php
session_start();
require_once 'database/db_config.php';
$page_title = "Blog - Latest News & Updates | SB Smart India";

try {
    // 1. Fetch Blog Categories for Sidebar
    $cats_sql = "SELECT * FROM blog_categories ORDER BY name ASC";
    $cats_res = $conn->query($cats_sql);
    $categories = [];
    if($cats_res && $cats_res->num_rows > 0) {
        while($cat = $cats_res->fetch_assoc()) {
            // Count blogs in this category
            $count_sql = "SELECT COUNT(*) as count FROM blogs WHERE category_id = " . $cat['id'] . " AND status = 1";
            $count_res = $conn->query($count_sql);
            $cat['count'] = $count_res->fetch_assoc()['count'];
            $categories[] = $cat;
        }
    }

    // 2. Build Query based on GET params
    $where_clauses = ["status = 1"]; // Only detailed published blogs

    // Filter by Category
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $cat_id = intval($_GET['category']);
        $where_clauses[] = "category_id = $cat_id";
    }

    // Search Query
    $search_query = "";
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $search_query = $conn->real_escape_string($_GET['q']);
        $where_clauses[] = "(title LIKE '%$search_query%' OR content LIKE '%$search_query%')";
    }

    // 3. Pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = 9; // 9 blogs per page
    $offset = ($page - 1) * $limit;

    // Combine Where Clauses
    $where_sql = implode(' AND ', $where_clauses);

    // Count Total Blogs
    $count_sql = "SELECT COUNT(*) as total FROM blogs WHERE $where_sql";
    $count_res = $conn->query($count_sql);
    $total_blogs = $count_res->fetch_assoc()['total'];
    $total_pages = ceil($total_blogs / $limit);

    // Fetch Blogs
    $blogs_sql = "SELECT b.*, c.name as category_name 
                  FROM blogs b 
                  LEFT JOIN blog_categories c ON b.category_id = c.id 
                  WHERE $where_sql 
                  ORDER BY b.created_at DESC 
                  LIMIT $offset, $limit";
    $blogs_res = $conn->query($blogs_sql);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    <link rel="stylesheet" href="assets/css/shop.css"> <!-- Reusing layout styles -->
    <link rel="stylesheet" href="assets/css/blog.css"> <!-- Blog specific styles -->
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<!-- Blog Header / Breadcrumb -->
<div class="shop-header-section">
    <div class="container">
        <h1>Our Blog</h1>
        <div class="breadcrumbs">
            <a href="index.php">Home</a> &gt; <span>Blog</span>
        </div>
    </div>
</div>

<div class="container shop-layout">
    <!-- Sidebar -->
    <button class="mobile-filter-toggle" id="mobileFilterBtn"><i class="fa-solid fa-bars"></i> Categories</button>
    <div class="shop-sidebar" id="shopSidebar">
        <div class="sidebar-header-mobile">
            <h3>Categories</h3>
            <button class="close-sidebar" id="closeSidebar">&times;</button>
        </div>

        <form action="blogs.php" method="GET" id="filterForm">
            <!-- Search Widget -->
            <div class="filter-group">
                <h3>Search</h3>
                <div class="filter-content">
                    <div style="display: flex; gap: 5px;">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search posts..." style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="submit" class="btn-filter-apply" style="width: auto;"><i class="fa-solid fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Categories Filter -->
            <div class="filter-group">
                <h3>Categories</h3>
                <div class="filter-content categories-list">
                    <ul>
                        <li>
                            <a href="blogs.php" class="<?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All Categories</a>
                        </li>
                        <?php foreach($categories as $cat): ?>
                            <li>
                                <div class="cat-link-wrap">
                                    <a href="blogs.php?category=<?php echo $cat['id']; ?>" class="<?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'active' : ''; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?> 
                                        <span style="font-size: 11px; color: #999;">(<?php echo $cat['count']; ?>)</span>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
        </form>
    </div>

    <!-- Blog Content -->
    <div class="shop-content">
        <?php if (!empty($search_query)): ?>
            <div class="shop-top-bar">
                <div class="results-count">
                    Search results for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Blog Grid -->
        <div class="blog-grid">
            <?php if ($blogs_res->num_rows > 0): ?>
                <?php while($blog = $blogs_res->fetch_assoc()): ?>
                <div class="blog-card">
                    <div class="blog-image">
                        <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>">
                            <?php if(!empty($blog['featured_image']) && file_exists($blog['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <?php else: ?>
                                <img src="assets/images/placeholder.jpg" alt="No Image">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="fa-regular fa-calendar"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                            <span><i class="fa-solid fa-folder-open"></i> <?php echo htmlspecialchars($blog['category_name']); ?></span>
                        </div>
                        <h3 class="blog-title">
                            <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>"><?php echo htmlspecialchars($blog['title']); ?></a>
                        </h3>
                        <div class="blog-excerpt">
                            <?php 
                                $excerpt = strip_tags($blog['content']); 
                                echo strlen($excerpt) > 120 ? substr($excerpt, 0, 120) . '...' : $excerpt;
                            ?>
                        </div>
                        <div class="blog-footer">
                             <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="read-more">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products-found" style="grid-column: 1 / -1; width: 100%;">
                    <i class="fa-regular fa-newspaper"></i>
                    <h3>No Blog Posts Found</h3>
                    <p>We couldn't find any blog posts matching your criteria.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php 
            // Reuse GET params for pagination links
            $query_params = $_GET;
            unset($query_params['page']);
            $base_url = 'blogs.php?' . http_build_query($query_params);
            $conn_char = !empty($query_params) ? '&' : '';
            ?>

            <?php if($page > 1): ?>
                <a href="<?php echo $base_url . $conn_char . 'page=' . ($page - 1); ?>" class="page-link prev"><i class="fa-solid fa-angle-left"></i></a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?php echo $base_url . $conn_char . 'page=' . $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="<?php echo $base_url . $conn_char . 'page=' . ($page + 1); ?>" class="page-link next"><i class="fa-solid fa-angle-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    // Sidebar Toggle
    document.getElementById('mobileFilterBtn').addEventListener('click', function() {
        document.getElementById('shopSidebar').classList.add('active');
        document.body.style.overflow = 'hidden'; 
    });

    document.getElementById('closeSidebar').addEventListener('click', function() {
        document.getElementById('shopSidebar').classList.remove('active');
        document.body.style.overflow = '';
    });
</script>

</body>
</html>
