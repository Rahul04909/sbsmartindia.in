<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$page = 'enquiries';
$url_prefix = '../';
require_once '../database/db_config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id'];
// Fetch Enquiries with Product Details
$sql = "SELECT e.*, p.title as product_name, p.featured_image as product_image 
        FROM product_enquiries e 
        LEFT JOIN products p ON e.product_id = p.id 
        WHERE e.user_id = $user_id 
        ORDER BY e.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enquiries | SB Smart India</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="../asstes/css/style.css">
    <link rel="stylesheet" href="../assets/css/header-menu.css">
    <link rel="stylesheet" href="../asstes/css/footer.css">
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/user-dashboard.css">
    
    <style>
        /* Table Styles override or specific to this page */
        .table-responsive { overflow-x: auto; }
        .user-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        .user-table th, .user-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle;}
        .user-table th { background-color: #f8f9fa; font-weight: 600; color: #555; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .product-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; }
        .content-card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="dashboard-content">
            <div class="dashboard-header">
                <h1>My Enquiries</h1>
                <p>Track the status of your product enquiries.</p>
            </div>

            <!-- Enquiries Table -->
            <div class="content-card">
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php 
                                        $prod_img = (!empty($row['product_image']) && file_exists('../' . $row['product_image'])) ? '../' . $row['product_image'] : '../assets/images/placeholder.jpg';
                                    ?>
                                    <tr>
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <img src="<?php echo $prod_img; ?>" alt="Product" class="product-thumb">
                                                <div>
                                                    <a href="../product-details.php?id=<?php echo $row['product_id']; ?>" style="text-decoration: none; color: #333; font-weight: 600;" target="_blank">
                                                        <?php echo htmlspecialchars($row['product_name']); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($row['message']); ?>">
                                                <?php echo htmlspecialchars($row['message']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php 
                                            $status = ucfirst($row['status']);
                                            $badge_color = '#6c757d'; // gray for pending
                                            if($status == 'Resolved' || $status == 'Replied') $badge_color = '#28a745';
                                            if($status == 'Processing') $badge_color = '#007bff';
                                            ?>
                                            <span class="status-badge" style="background-color: <?php echo $badge_color; ?>20; color: <?php echo $badge_color; ?>;">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align:center; padding: 40px; color:#666;">
                        <i class="fa-solid fa-envelope-open-text" style="font-size: 40px; margin-bottom: 15px; color:#ddd;"></i>
                        <p>No enquiries found.</p>
                        <a href="../products.php" class="btn-primary" style="margin-top:10px; display:inline-block; padding:8px 15px; font-size:14px; text-decoration:none;">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
