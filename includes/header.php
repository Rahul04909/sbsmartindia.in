<header>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-left-info">
                <span><i class="fa-regular fa-envelope"></i> marcom.sbsyscon@gmail.com</span>
                <span><i class="fa-solid fa-phone"></i> (+91) 129 4150 555</span>
            </div>
            <nav class="top-right-menu">
                <ul>
                    <li><a href="<?php echo $url_prefix; ?>pages/about-us.php">About</a></li>
                    <li><a href="<?php echo $url_prefix; ?>pages/contact-us.php">Contact</a></li>
                    <li><a href="<?php echo $url_prefix; ?>blogs.php">Blog</a></li>
                    <li><a href="<?php echo $url_prefix; ?>pages/faqs.php">FAQ</a></li>
                    <li><a href="<?php echo $url_prefix; ?>pages/assisted-orders.php">Assisted Orders</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <?php $url_prefix = isset($url_prefix) ? $url_prefix : ''; ?>
                <a href="<?php echo $url_prefix; ?>index.php">
                    <img src="<?php echo $url_prefix; ?>asstes/logo/logo.png" alt="SB Smart India">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="search-bar">
                <form action="<?php echo $url_prefix; ?>products.php" method="GET" id="headerSearchForm" style="flex: 1; display: flex; position: relative;">
                    <input type="text" name="q" id="headerSearchInput" placeholder="Search..." autocomplete="off" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <div id="searchResultsDropdown" class="search-results-dropdown"></div>
                </form>
                <button type="button" class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Icons -->
            <div class="header-icons">
                <div class="icon-item" style="position: relative;">
                    <a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>cart.php" title="Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                    <span id="cart-count-badge" class="cart-badge" style="display:none;">0</span>
                </div>
                <div class="icon-item">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php $url_prefix = isset($url_prefix) ? $url_prefix : ''; ?>
                        <a href="<?php echo $url_prefix; ?>user/index.php" title="My Account">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    <?php else: ?>
                        <a href="#" id="login-btn-trigger" title="Login / Register">
                            <i class="fa-regular fa-user"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-navigation">
        <div class="container">
            <ul class="nav-menu">
                <?php
                // Ensure DB connection
                if (!isset($conn)) {
                    // Adjust path based on location
                    $db_path = isset($url_prefix) && $url_prefix === '../' ? '../database/db_config.php' : 'database/db_config.php';
                    if (file_exists($db_path)) {
                        require_once $db_path;
                    } elseif (file_exists('database/db_config.php')) { // Fallback
                         require_once 'database/db_config.php';
                    } elseif (file_exists('../database/db_config.php')) { // Fallback
                         require_once '../database/db_config.php';
                    }
                }

                if (isset($conn)) {
                    // Fetch top 8 brands
                    $nav_brand_sql = "SELECT * FROM brands LIMIT 8";
                    $nav_brand_res = $conn->query($nav_brand_sql);

                    if ($nav_brand_res && $nav_brand_res->num_rows > 0) {
                        while ($nav_brand = $nav_brand_res->fetch_assoc()) {
                            $raw_logo = $nav_brand['logo'];
                            $check_path = (isset($url_prefix) ? $url_prefix : '') . $raw_logo;
                            
                            $nb_logo = $raw_logo && file_exists($check_path) ? $raw_logo : '';
                            $nb_name = $nav_brand['name'];
                ?>
                            <li class="nav-item">
                                <a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>products.php?brand=<?php echo urlencode($nav_brand['id']); ?>" class="nav-link brand-nav-btn">
                                    <span><?php echo htmlspecialchars($nb_name); ?></span>
                                </a>
                                
                                <!-- Dropdown Menu -->
                                <ul class="dropdown-menu">
                                    <?php
                                    // Fetch Categories for this Brand
                                    $brand_id = $nav_brand['id'];
                                    $cat_sql = "SELECT * FROM product_categories WHERE brand_id = $brand_id AND status = 1 ORDER BY name ASC";
                                    $cat_res = $conn->query($cat_sql);
                                    
                                    if ($cat_res && $cat_res->num_rows > 0) {
                                        while ($cat = $cat_res->fetch_assoc()) {
                                            $cat_id = $cat['id'];
                                            
                                            // Check if has subcategories
                                            $sub_count_sql = "SELECT COUNT(*) as count FROM product_sub_categories WHERE category_id = $cat_id AND status = 1";
                                            $sub_count_res = $conn->query($sub_count_sql);
                                            $has_sub = ($sub_count_res->fetch_assoc()['count'] > 0);
                                    ?>
                                            <li class="<?php echo $has_sub ? 'has-submenu' : ''; ?>">
                                                <a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>products.php?category=<?php echo urlencode($cat_id); ?>">
                                                    <?php echo htmlspecialchars($cat['name']); ?>
                                                </a>
                                                
                                                <?php if ($has_sub): ?>
                                                <ul class="dropdown-submenu">
                                                    <?php
                                                    $sub_sql = "SELECT * FROM product_sub_categories WHERE category_id = $cat_id AND status = 1 ORDER BY name ASC";
                                                    $sub_res = $conn->query($sub_sql);
                                                    while ($sub = $sub_res->fetch_assoc()) {
                                                    ?>
                                                        <li>
                                                            <a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>products.php?sub_category=<?php echo urlencode($sub['id']); ?>">
                                                                <?php echo htmlspecialchars($sub['name']); ?>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php endif; ?>
                                            </li>
                                    <?php
                                        }
                                    } else {
                                        echo '<li><a href="#">No categories</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                <?php
                        }
                        }
                    }

                ?>
                <!-- Others Item -->
                <li class="nav-item">
                    <a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>brands/index.php" class="nav-link brand-nav-btn">
                        <span>OTHERS</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/header-auth.css">
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/brand-menu-text.css">
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/mega-menu.css">
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/auth-modal.css">
<?php include_once dirname(__DIR__) . '/components/auth-modal.php'; ?>
<script>
    var urlPrefix = "<?php echo isset($url_prefix) ? $url_prefix : ''; ?>";
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>
<aside class="mobile-sidebar" id="mobileSidebar">
    <div class="mobile-sidebar-header">
        <div class="mobile-logo">
            <img src="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>asstes/logo/logo.png" alt="SB Smart India">
        </div>
        <button class="mobile-menu-close" id="mobileMenuClose" onclick="closeMobileSidebar()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <div class="mobile-sidebar-content">
        <!-- User Info (Mobile) -->
        <div class="mobile-user-info">
             <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mobile-user-profile">
                    <div class="mobile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="mobile-user-details">
                        <span>Hello,</span>
                        <h4><?php echo htmlspecialchars($_SESSION['user_name']); ?></h4>
                    </div>
                </div>
            <?php else: ?>
                <a href="#" class="mobile-login-btn" onclick="openLoginModal(); return false;">
                    <i class="fa-solid fa-right-to-bracket"></i> Login / Register
                </a>
            <?php endif; ?>
        </div>

        <nav class="mobile-nav-menu">
            <ul>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>index.php"><i class="fa-solid fa-home"></i> Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>user/index.php"><i class="fa-solid fa-gauge"></i> My Dashboard</a></li>
                <?php endif; ?>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>products.php"><i class="fa-solid fa-box-open"></i> All Products</a></li>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>pages/about-us.php"><i class="fa-solid fa-info-circle"></i> About Us</a></li>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>blogs.php"><i class="fa-solid fa-blog"></i> Blogs</a></li>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>pages/contact-us.php"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
                <li><a href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>pages/faqs.php"><i class="fa-solid fa-question-circle"></i> FAQs</a></li>
            </ul>
        </nav>

        <div class="mobile-sidebar-footer">
            <a href="tel:+911294150555"><i class="fa-solid fa-phone"></i> (+91) 129 4150 555</a>
            <a href="mailto:marcom.sbsyscon@gmail.com"><i class="fa-regular fa-envelope"></i> marcom.sbsyscon@gmail.com</a>
        </div>
    </div>
</aside>

<script>
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // Close on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('mobileSidebarOverlay');
        if(overlay) {
             overlay.addEventListener('click', closeMobileSidebar);
        }
    });
</script>
<script>
    function updateCartCount() {
        $.ajax({
            url: '<?php echo isset($url_prefix) ? $url_prefix : ''; ?>cart_handler.php',
            type: 'POST',
            data: { action: 'count' },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success' && response.count > 0) {
                    $('#cart-count-badge').text(response.count).show();
                } else {
                    $('#cart-count-badge').hide();
                }
            }
        });
    }
    $(document).ready(function() {
        updateCartCount();

        // Live Search Logic
        let searchTimer;
        const searchInput = $('#headerSearchInput');
        const searchResults = $('#searchResultsDropdown');

        searchInput.on('input', function() {
            clearTimeout(searchTimer);
            const query = $(this).val().trim();

            if (query.length < 2) {
                searchResults.removeClass('active').empty();
                return;
            }

            searchTimer = setTimeout(function() {
                $.ajax({
                    url: '<?php echo isset($url_prefix) ? $url_prefix : ""; ?>search_suggestions.php',
                    type: 'GET',
                    data: { q: query },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            let html = '';
                            response.data.forEach(function(item) {
                                const priceText = item.is_price_request ? 'Price on Request' : '₹' + item.price.toLocaleString('en-IN');
                                html += `
                                    <a href="<?php echo isset($url_prefix) ? $url_prefix : ""; ?>product-details.php?id=${item.id}" class="search-result-item">
                                        <img src="<?php echo isset($url_prefix) ? $url_prefix : ""; ?>${item.image}" alt="${item.title}" class="search-result-image">
                                        <div class="search-result-info">
                                            <span class="search-result-title">${item.title}</span>
                                            ${item.category ? `<span class="search-result-category">in ${item.category}</span>` : ''}
                                        </div>
                                    </a>`;
                            });
                            html += `<a href="<?php echo isset($url_prefix) ? $url_prefix : ""; ?>products.php?q=${encodeURIComponent(query)}" class="search-view-all">See All Results</a>`;
                            searchResults.html(html).addClass('active');
                        } else {
                            searchResults.html('<div class="search-no-results">No products found for "'+query+'"</div>').addClass('active');
                        }
                    }
                });
            }, 300);
        });

        // Hide results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-bar').length) {
                searchResults.removeClass('active');
            }
        });

        // Show results when focusing back if not empty
        searchInput.on('focus', function() {
            if ($(this).val().trim().length >= 2 && searchResults.children().length > 0) {
                searchResults.addClass('active');
            }
        });
    });
</script>
