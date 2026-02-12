<?php
session_start();
require_once 'database/db_config.php';

// 1. Get Blog Slug
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: blogs.php");
    exit();
}

$slug = $conn->real_escape_string($_GET['slug']);

// 2. Fetch Blog Details
$sql = "SELECT b.*, c.name as category_name, c.id as category_id 
        FROM blogs b 
        LEFT JOIN blog_categories c ON b.category_id = c.id 
        WHERE b.slug = '$slug' AND b.status = 1";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: blogs.php"); // Or 404 page
    exit();
}

$blog = $result->fetch_assoc();

// 3. Increment Views (Simple logic, can be improved with cookies/IP to prevent spam)
$conn->query("UPDATE blogs SET views = views + 1 WHERE id = " . $blog['id']);

// 4. Fetch Recent Posts (Sidebar)
$recent_sql = "SELECT title, slug, featured_image, created_at FROM blogs WHERE status = 1 AND id != " . $blog['id'] . " ORDER BY created_at DESC LIMIT 5";
$recent_res = $conn->query($recent_sql);

// 5. Fetch Categories (Sidebar)
$cats_sql = "SELECT * FROM blog_categories ORDER BY name ASC";
$cats_res = $conn->query($cats_sql);

// Page Title & SEO
$page_title = $blog['meta_title'] ? $blog['meta_title'] : $blog['title'] . " | SB Smart India";
$meta_desc = $blog['meta_description'];
$meta_keywords = $blog['meta_keywords'];
$canonical_url = 'https://' . $_SERVER['HTTP_HOST'] . '/blog-details.php?slug=' . $blog['slug']; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <?php if(!empty($blog['featured_image'])): ?>
    <meta property="og:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/' . $blog['featured_image']; ?>">
    <?php endif; ?>

    <!-- Schema Markup -->
    <?php if(!empty($blog['schema_markup'])): ?>
    <script type="application/ld+json">
    <?php echo $blog['schema_markup']; ?>
    </script>
    <?php endif; ?>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    <link rel="stylesheet" href="assets/css/shop.css"> 
    <link rel="stylesheet" href="assets/css/blog.css">
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<!-- Blog Header / Breadcrumb -->
<div class="shop-header-section">
    <div class="container">
        <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
        <div class="breadcrumbs">
            <a href="index.php">Home</a> &gt; <a href="blogs.php">Blog</a> &gt; <span><?php echo htmlspecialchars($blog['title']); ?></span>
        </div>
    </div>
</div>

<div class="container blog-details-layout">
    
    <!-- Main Content -->
    <article class="blog-article">
        <?php if(!empty($blog['featured_image']) && file_exists($blog['featured_image'])): ?>
            <div class="article-image">
                <img src="<?php echo htmlspecialchars($blog['featured_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
            </div>
        <?php endif; ?>

        <div class="article-header">
            <div class="article-meta">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                <span><i class="fa-solid fa-folder-open"></i> <a href="blogs.php?category=<?php echo $blog['category_id']; ?>"><?php echo htmlspecialchars($blog['category_name']); ?></a></span>
                <span><i class="fa-regular fa-eye"></i> <?php echo $blog['views']; ?> Views</span>
            </div>
            <!-- Title is already in header, optional to repeat or keep specific to article structure -->
            <!-- <h1 class="article-title"><?php echo htmlspecialchars($blog['title']); ?></h1> -->
        </div>

        <div class="article-content">
            <?php echo $blog['content']; ?>
        </div>

        <div class="share-buttons">
            <span class="share-label">Share this post:</span>
            <!-- Simple Share Links (No JS SDKs for speed) -->
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($canonical_url); ?>" target="_blank" class="btn-share share-fb"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($canonical_url); ?>&text=<?php echo urlencode($blog['title']); ?>" target="_blank" class="btn-share share-tw"><i class="fab fa-twitter"></i></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($canonical_url); ?>" target="_blank" class="btn-share share-li"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://wa.me/?text=<?php echo urlencode($blog['title'] . ' ' . $canonical_url); ?>" target="_blank" class="btn-share share-wa"><i class="fab fa-whatsapp"></i></a>
        </div>
    </article>

    <!-- Sidebar -->
    <aside class="blog-sidebar">
        <!-- Search Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Search</h3>
            <form action="blogs.php" method="GET">
                <div style="display: flex; gap: 5px;">
                    <input type="text" name="q" placeholder="Search posts..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="submit" class="btn-filter-apply" style="width: auto;"><i class="fa-solid fa-search"></i></button>
                </div>
            </form>
        </div>

        <!-- Categories Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Categories</h3>
            <div class="categories-list">
                <ul>
                    <?php if($cats_res->num_rows > 0): ?>
                        <?php while($cat = $cats_res->fetch_assoc()): 
                             // Count for sidebar (optional, reusing query or separate)
                             // For speed, maybe just list link? Or simple count query
                             $c_count_q = $conn->query("SELECT COUNT(*) as count FROM blogs WHERE category_id=".$cat['id']." AND status=1");
                             $c_count = $c_count_q->fetch_assoc()['count'];
                        ?>
                        <li>
                            <div class="cat-link-wrap">
                                <a href="blogs.php?category=<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                                <span style="font-size: 12px; color: #999;">(<?php echo $c_count; ?>)</span>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Recent Posts Widget -->
        <div class="sidebar-widget">
            <h3 class="widget-title">Recent Posts</h3>
            <ul class="recent-posts-list">
                <?php while($recent = $recent_res->fetch_assoc()): ?>
                <li class="recent-post-item">
                    <div class="rp-thumb">
                        <a href="blog-details.php?slug=<?php echo $recent['slug']; ?>">
                            <?php if(!empty($recent['featured_image']) && file_exists($recent['featured_image'])): ?>
                                <img src="<?php echo htmlspecialchars($recent['featured_image']); ?>" alt="<?php echo htmlspecialchars($recent['title']); ?>">
                            <?php else: ?>
                                <img src="assets/images/placeholder.jpg" alt="No Image">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="rp-info">
                        <h4><a href="blog-details.php?slug=<?php echo $recent['slug']; ?>"><?php echo htmlspecialchars($recent['title']); ?></a></h4>
                        <span class="rp-date"><?php echo date('M d, Y', strtotime($recent['created_at'])); ?></span>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </aside>

</div>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>
