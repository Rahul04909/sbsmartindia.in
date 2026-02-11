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
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Assisted Orders</a></li>
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
                <input type="text" placeholder="Search products, brands or model numbers...">
                <button type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <!-- Icons -->
            <div class="header-icons">
                <div class="icon-item" style="position: relative;">
                    <a href="cart.php" title="Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                    <span id="cart-count-badge" class="cart-badge" style="display:none;">0</span>
                </div>
                <div class="icon-item">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php $url_prefix = isset($url_prefix) ? $url_prefix : ''; ?>
                        <a href="<?php echo $url_prefix; ?>user/index.php" class="auth-btn">
                            <i class="fa-regular fa-user"></i> My Account
                        </a>
                    <?php else: ?>
                        <a href="#" id="login-btn-trigger" class="auth-btn">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
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
                                <a href="#" class="nav-link brand-nav-btn">
                                    <span><?php echo htmlspecialchars($nb_name); ?></span>
                                </a>
                            </li>
                <?php
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/header-auth.css">
<link rel="stylesheet" href="<?php echo isset($url_prefix) ? $url_prefix : ''; ?>assets/css/brand-menu-text.css">
<?php include_once dirname(__DIR__) . '/components/auth-modal.php'; ?>
<script>
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    var userName = "<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>";
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
    });
</script>
