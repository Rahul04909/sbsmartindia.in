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
                    <a href="#" title="Account"><i class="fa-regular fa-user"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-navigation">
        <div class="container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Brands <i class="fa-solid fa-chevron-down"></i></a>
                    <div class="mega-menu">
                        <div class="mega-menu-content">
                            <div class="header-brand-grid-container">
                                <div class="header-brand-grid-header">
                                    <h3>Our Brands</h3>
                                    <a href="#">View All Brands</a>
                                </div>
                                <div class="header-brand-grid">
                                    <?php
                                    // Ensure DB connection
                                    if (!isset($conn)) {
                                        // Assume we are in root, so path is database/db_config.php
                                        // Use file_exists to be safe if header is included from elsewhere
                                        if (file_exists('database/db_config.php')) {
                                            require_once 'database/db_config.php';
                                        } elseif (file_exists('../database/db_config.php')) {
                                            require_once '../database/db_config.php';
                                        }
                                    }

                                    if (isset($conn)) {
                                        $h_brand_sql = "SELECT * FROM brands ORDER BY RAND() LIMIT 8";
                                        $h_brand_res = $conn->query($h_brand_sql);

                                        if ($h_brand_res && $h_brand_res->num_rows > 0) {
                                            while ($h_brand = $h_brand_res->fetch_assoc()) {
                                                $h_b_logo = $h_brand['logo'] && file_exists($h_brand['logo']) ? $h_brand['logo'] : 'assets/images/no-brand.png';
                                    ?>
                                                <a href="#" class="brand-mega-item">
                                                    <img src="<?php echo htmlspecialchars($h_b_logo); ?>" alt="<?php echo htmlspecialchars($h_brand['name']); ?>">
                                                    <span><?php echo htmlspecialchars($h_brand['name']); ?></span>
                                                </a>
                                    <?php
                                            }
                                        } else {
                                            echo "<p style='color:#333; padding:10px;'>No brands found.</p>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- Featured Section in Mega Menu -->
                            <div class="mega-menu-feature">
                                <img src="asstes/logo/logo.png" alt="Feature" style="opacity: 0.8; height: 50px; object-fit: contain;">
                                <h4>Premium Partners</h4>
                                <p>Discover our range of premium electrical brands and authorized distributorships.</p>
                                <a href="#" class="mega-menu-btn">View Catalog</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Products</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
