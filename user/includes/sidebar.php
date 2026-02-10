<div class="user-sidebar">
    <div class="user-info-box">
        <div class="user-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="user-text">
            <h4>Hello, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></h4>
            <span>Access your account</span>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="index.php" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-table-columns"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'orders') ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-open"></i> My Orders
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'profile') ? 'active' : ''; ?>">
                <i class="fa-solid fa-id-card"></i> Profile & Address
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'quotes') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> My Quotes
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'enquiries') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope-open-text"></i> Enquiries
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'get_quote') ? 'active' : ''; ?>">
                <i class="fa-solid fa-plus-circle"></i> Request Quote
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo ($page == 'support') ? 'active' : ''; ?>">
                <i class="fa-solid fa-headset"></i> Connect with Team
            </a>
        </li>
        <li>
            <a href="../logout.php" style="color: #dc3545;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>
    </ul>
</div>
