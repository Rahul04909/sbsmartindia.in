<?php
$page = 'dashboard';
$url_prefix = ''; 
include 'includes/header.php';
require_once '../database/db_config.php';

// --- Fetch Data ---

// 1. Total Sales & Orders (All Time) - Successful orders only
$total_sales = 0;
$total_orders = 0;
$sql_total = "SELECT SUM(total_amount) as sales, COUNT(*) as orders FROM orders WHERE payment_status = 'Success' OR order_status = 'Delivered'"; 
$res_total = $conn->query($sql_total);
if ($res_total && $row = $res_total->fetch_assoc()) {
    $total_sales = $row['sales'] ?? 0;
    $total_orders = $row['orders'] ?? 0; // Or count all orders regardless of status? usually depends on business logic, I'll count all for 'Total Orders' below separately if needed, but 'All time orders' usually implies valid ones. Let's stick to valid for revenue.
}

// Re-fetching Total Orders (All orders count)
$sql_all_orders = "SELECT COUNT(*) as total FROM orders";
$res_all_orders = $conn->query($sql_all_orders);
$total_orders_all = ($res_all_orders && $r = $res_all_orders->fetch_assoc()) ? $r['total'] : 0;

// 2. Monthly Sales & Orders
$current_month = date('Y-m');
$monthly_sales = 0;
$monthly_orders = 0;
$sql_month = "SELECT SUM(total_amount) as sales, COUNT(*) as orders FROM orders WHERE (payment_status = 'Success' OR order_status = 'Delivered') AND DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
$res_month = $conn->query($sql_month);
if ($res_month && $row = $res_month->fetch_assoc()) {
    $monthly_sales = $row['sales'] ?? 0;
    $monthly_orders = $row['orders'] ?? 0;
}

// 3. Today's Orders
$today = date('Y-m-d');
$todays_orders = 0;
$sql_today = "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = '$today'";
$res_today = $conn->query($sql_today);
if ($res_today && $row = $res_today->fetch_assoc()) {
    $todays_orders = $row['total'];
}



// 6. Counts: Users, Products, Brands, Quotes
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'] ?? 0;
$total_brands = $conn->query("SELECT COUNT(*) as total FROM brands")->fetch_assoc()['total'] ?? 0;
$total_quotes = $conn->query("SELECT COUNT(*) as total FROM quote_requests")->fetch_assoc()['total'] ?? 0;



?>

<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
    </div>

    <!-- Stat Cards Row 1 -->
    <div class="dashboard-stats-grid">
        <!-- Total Sales -->
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">TOTAL SALES</span>
                <h2 class="stat-value">₹<?php echo number_format($total_sales, 2); ?></h2>
                <p class="stat-desc">Lifetime revenue</p>
            </div>
            <div class="stat-icon-bg green">
                <i class="fas fa-rupee-sign"></i>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">TOTAL ORDERS</span>
                <h2 class="stat-value"><?php echo number_format($total_orders_all); ?></h2>
                <p class="stat-desc">All time orders</p>
            </div>
            <div class="stat-icon-bg blue">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">MONTHLY SALES</span>
                <h2 class="stat-value">₹<?php echo number_format($monthly_sales, 2); ?></h2>
                <p class="stat-desc">This month</p>
            </div>
            <div class="stat-icon-bg light-blue">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>

        <!-- Monthly Orders -->
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">MONTHLY ORDERS</span>
                <h2 class="stat-value"><?php echo number_format($monthly_orders); ?></h2>
                <p class="stat-desc">This month</p>
            </div>
            <div class="stat-icon-bg yellow">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row 2 (Consolidated) -->
    <div class="dashboard-stats-grid" style="margin-top: 20px;">
        <!-- Today's Orders -->
        <div class="stat-card-row">
            <div class="stat-icon-circle blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-row-label">Today's Orders</span>
                <h3 class="stat-row-value"><?php echo number_format($todays_orders); ?></h3>
            </div>
        </div>

        <!-- Total Users -->
        <div class="stat-card-row">
            <div class="stat-icon-circle cyan">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-row-label">Total Users</span>
                <h3 class="stat-row-value"><?php echo number_format($total_users); ?></h3>
            </div>
        </div>

        <!-- Total Products -->
        <div class="stat-card-row">
            <div class="stat-icon-circle green">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-info">
                <span class="stat-row-label">Total Products</span>
                <h3 class="stat-row-value"><?php echo number_format($total_products); ?></h3>
            </div>
        </div>

        <!-- Total Brands -->
        <div class="stat-card-row">
            <div class="stat-icon-circle dark">
                <i class="fas fa-thumbtack"></i>
            </div>
            <div class="stat-info">
                <span class="stat-row-label">Total Brands</span>
                <h3 class="stat-row-value"><?php echo number_format($total_brands); ?></h3>
            </div>
        </div>
    </div>

    <!-- Main Content Grid (Quick Actions & Activity) -->
    <div class="dashboard-main-grid" style="margin-top: 30px; display: grid; grid-template-columns: 350px 1fr; gap: 30px;">
        
        <!-- Quick Actions -->
        <div class="quick-actions-card card">
            <h3 class="card-title">Quick Actions</h3>
            <div class="quick-actions-grid">
                <a href="products/add-product.php" class="quick-action-btn blue-outline">
                    <div class="icon-circle blue"><i class="fas fa-plus"></i></div>
                    <span>Add Product</span>
                </a>
                <a href="products/index.php" class="quick-action-btn gray-outline">
                    <div class="icon-circle gray"><i class="fas fa-boxes"></i></div>
                    <span>Manage Stock</span>
                </a>
                <a href="users/index.php" class="quick-action-btn cyan-outline">
                    <div class="icon-circle cyan"><i class="fas fa-users-cog"></i></div>
                    <span>Users</span>
                </a>
                <a href="#" class="quick-action-btn green-outline">
                    <div class="icon-circle green"><i class="fas fa-handshake"></i></div>
                    <span>Partners</span>
                </a>
                <a href="#" class="quick-action-btn yellow-outline">
                    <div class="icon-circle yellow"><i class="fas fa-user-tie"></i></div>
                    <span>Sr. Partners</span>
                </a>
                <a href="settings/smtp-settings.php" class="quick-action-btn black-outline">
                    <div class="icon-circle black"><i class="fas fa-cog"></i></div>
                    <span>Settings</span>
                </a>
            </div>
        </div>

        <!-- System Activity -->
        <div class="system-activity-card card">
            <div class="card-header-flex">
                <h3 class="card-title">System Activity</h3>
                <span class="text-muted">Last 5 events</span>
            </div>
            <div class="table-responsive">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>TIME</th>
                            <th>EVENT</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mock Data for now as no logs table exists -->
                        <tr>
                            <td class="text-muted">Just now</td>
                            <td>Admin Dashboard Accessed</td>
                            <td><span class="status-badge active">Active</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Yesterday</td>
                            <td>New Quote Request #<?php echo $total_quotes; ?> Received</td>
                            <td><span class="status-badge new-user">Quote</span></td>
                        </tr>
                         <tr>
                            <td class="text-muted">Yesterday</td>
                            <td>New User Registration</td>
                            <td><span class="status-badge new-user">New User</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">2 days ago</td>
                            <td>System Backup Completed</td>
                            <td><span class="status-badge done">Done</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">3 days ago</td>
                            <td>Weekly Report Generated</td>
                            <td><span class="status-badge system">System</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<style>
/* Dashboard Specific Styles */

/* Grid Layouts */
.dashboard-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1200px) {
    .dashboard-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .dashboard-main-grid {
        grid-template-columns: 1fr !important;
    }
}
@media (max-width: 768px) {
    .dashboard-stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Stat Card Type 1 (Top Row) */
.stat-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.stat-content {
    z-index: 1;
}
.stat-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #888;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 5px 0;
}
.stat-desc {
    font-size: 13px;
    color: #888;
    margin: 0;
}
.stat-icon-bg {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
/* Colors */
.stat-icon-bg.green, .icon-circle.green { background: #e0f7e9; color: #2dce89; }
.stat-icon-bg.blue, .icon-circle.blue { background: #e6f6fa; color: #11cdef; }
.stat-icon-bg.light-blue, .icon-circle.cyan { background: #e3f2fd; color: #2196f3; }
.stat-icon-bg.yellow, .icon-circle.yellow { background: #fff9db; color: #fb6340; } /* Adjusted to orange/yellow */
.stat-icon-bg.red, .icon-circle.red { background: #fee2e2; color: #f5365c; }
.stat-icon-bg.dark, .icon-circle.black { background: #f6f9fc; color: #32325d; }


/* Stat Card Type 2 (Detailed Rows) */
.stat-card-row {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    display: flex;
    align-items: center;
    gap: 20px;
}
.stat-icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.stat-icon-circle.blue { background: #e8f0fe; color: #1a73e8; }
.stat-icon-circle.red { background: #fce8e6; color: #d93025; }
.stat-icon-circle.green { background: #e6f4ea; color: #137333; }
.stat-icon-circle.dark { background: #f1f3f4; color: #202124; }
.stat-icon-circle.cyan { background: #e0f7fa; color: #0097a7; }

.stat-info {
    flex: 1;
}
.stat-row-label {
    display: block;
    font-size: 14px;
    color: #5f6368;
    margin-bottom: 2px;
}
.stat-row-value {
    font-size: 24px;
    font-weight: 600;
    color: #202124;
    margin: 0;
}
.stat-row-value.red-text { color: #d93025; }
.stat-row-value.green-text { color: #137333; }
.stat-link {
    display: block;
    font-size: 12px;
    color: #1a73e8;
    margin-top: 5px;
    text-decoration: none;
}
.stat-link:hover { text-decoration: underline; }

/* Main Cards */
.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    padding: 25px;
}
.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}
.card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}
.card-header-flex .card-title { margin: 0; border: none; padding: 0; }

/* Quick Actions */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border-radius: 8px;
    text-decoration: none;
    color: #444;
    transition: all 0.2s;
    border: 1px solid transparent;
}
.quick-action-btn span {
    margin-top: 10px;
    font-size: 14px;
    font-weight: 500;
}
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-bottom: 5px;
}
/* Quick Action Outlines */
.quick-action-btn.blue-outline { border: 1px solid #bfdbfe; }
.quick-action-btn:hover.blue-outline { background: #eff6ff; }
.quick-action-btn.gray-outline { border: 1px solid #e5e7eb; }
.quick-action-btn:hover.gray-outline { background: #f9fafb; }
.quick-action-btn.cyan-outline { border: 1px solid #b2ebf2; }
.quick-action-btn:hover.cyan-outline { background: #e0f7fa; }
.quick-action-btn.green-outline { border: 1px solid #c8e6c9; }
.quick-action-btn:hover.green-outline { background: #e8f5e9; }
.quick-action-btn.yellow-outline { border: 1px solid #ffe0b2; }
.quick-action-btn:hover.yellow-outline { background: #fff3e0; }
.quick-action-btn.black-outline { border: 1px solid #e0e0e0; }
.quick-action-btn:hover.black-outline { background: #f5f5f5; }


/* System Activity Table */
.activity-table {
    width: 100%;
    border-collapse: collapse;
}
.activity-table th {
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    padding-bottom: 15px;
}
.activity-table td {
    padding: 12px 0;
    font-size: 14px;
    color: #444;
    border-bottom: 1px solid #f9f9f9;
}
.activity-table tr:last-child td { border-bottom: none; }
.activity-table .text-muted { color: #888; font-size: 13px; }

/* Badges */
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.status-badge.active { background: #e6f4ea; color: #137333; }
.status-badge.done { background: #e8f0fe; color: #1a73e8; }
.status-badge.order { background: #e0f7fa; color: #006064; }
.status-badge.new-user { background: #fff8e1; color: #f57f17; }
.status-badge.system { background: #f1f3f4; color: #5f6368; }

</style>

<?php include 'includes/footer.php'; ?>
