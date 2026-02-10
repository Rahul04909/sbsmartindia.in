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
                <a href="index.php">
                    <img src="asstes/logo/logo.png" alt="SB Smart India">
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
                <div class="icon-item">
                    <a href="#" title="Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                </div>
                <div class="icon-item">
                    <a href="#" title="Account" id="account-link"><i class="fa-regular fa-user"></i></a>
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
                    if (file_exists('database/db_config.php')) {
                        require_once 'database/db_config.php';
                    } elseif (file_exists('../database/db_config.php')) {
                        require_once '../database/db_config.php';
                    }
                }

                if (isset($conn)) {
                    // Fetch top 8 brands
                    $nav_brand_sql = "SELECT * FROM brands LIMIT 8";
                    $nav_brand_res = $conn->query($nav_brand_sql);

                    if ($nav_brand_res && $nav_brand_res->num_rows > 0) {
                        while ($nav_brand = $nav_brand_res->fetch_assoc()) {
                            $nb_logo = $nav_brand['logo'] && file_exists($nav_brand['logo']) ? $nav_brand['logo'] : '';
                            $nb_name = $nav_brand['name'];
                ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link brand-nav-btn">
                                    <?php if ($nb_logo): ?>
                                        <img src="<?php echo htmlspecialchars($nb_logo); ?>" alt="<?php echo htmlspecialchars($nb_name); ?>">
                                    <?php else: ?>
                                        <span><?php echo htmlspecialchars($nb_name); ?></span>
                                    <?php endif; ?>
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
<?php include_once dirname(__DIR__) . '/components/auth-modal.php'; ?>
<script>
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    var userName = "<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>";
</script>
