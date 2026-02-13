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
            <a href="../../user/my-orders.php" class="<?php echo ($page == 'orders') ? 'active' : ''; ?>">
                <i class="fa-solid fa-box-open"></i> My Orders
            </a>
        </li>
        <li>
            <a href="../../user/profile.php" class="<?php echo ($page == 'profile') ? 'active' : ''; ?>">
                <i class="fa-solid fa-id-card"></i> Profile & Address
            </a>
        </li>
        <li class="<?php echo ($current_page == 'my-quotes.php') ? 'active' : ''; ?>">
            <a href="my-quotes.php"><i class="fa-solid fa-file-invoice-dollar"></i> My Quotes</a>
        </li>
        <li class="<?php echo ($current_page == 'enquiries.php') ? 'active' : ''; ?>">
            <a href="enquiries.php"><i class="fa-solid fa-clipboard-question"></i> Enquiries</a>
        </li>
        <li class="<?php echo ($current_page == 'request-quote.php') ? 'active' : ''; ?>">
            <a href="request-quote.php"><i class="fa-solid fa-file-invoice-dollar"></i> Request a Quote</a>
        </li>
        <li>
            <a href="../logout.php" style="color: #dc3545;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>
    </ul>
</div>
