<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand" style="justify-content: center; width: 100%;">
            <img src="<?php echo $url_prefix; ?>../asstes/logo/logo.png" alt="Admin Logo" style="max-height: 45px; width: auto;">
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo $url_prefix; ?>index.php" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-home"></i> <span>Dashboard</span>
                </div>
            </a>
        </li>
        
        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-thumbtack"></i> <span>Brands</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo $url_prefix; ?>brands/index.php">All Brands</a></li>
                <li><a href="<?php echo $url_prefix; ?>brands/add-brand.php">Add New Brand</a></li>
            </ul>
        </li>

        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-images"></i> <span>Product Categories</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?php echo $url_prefix; ?>product-categories/index.php">All Product Categories</a></li>
                <li><a href="<?php echo $url_prefix; ?>product-categories/add-category.php">Add New Product Category</a></li>
            </ul>
        </li>

        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-file-alt"></i> <span>Products</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">All Products</a></li>
                <li><a href="#">Add New Product</a></li>
            </ul>
        </li>

        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-users"></i> <span>Orders</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">All Orders</a></li>
                <li><a href="#">Add New Order</a></li>
            </ul>
        </li>

        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-tools"></i> <span>Users</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">All Users</a></li>
                <li><a href="#">Add User</a></li>
            </ul>
        </li>

        <li class="menu-item has-submenu">
            <a href="#" class="menu-link">
                <div class="menu-text">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </div>
                <i class="fas fa-chevron-right arrow"></i>
            </a>
            <ul class="submenu">
                <li><a href="#">General</a></li>
                <li><a href="#">Smtp Settings</a></li>
            </ul>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="<?php echo $url_prefix; ?>logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </div>
</aside>
