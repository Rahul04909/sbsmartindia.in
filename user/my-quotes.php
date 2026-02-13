<?php
session_start();
require_once '../database/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$page_title = "My Bulk Quotes - SB Smart";
$current_page = 'my-quotes.php';
$url_prefix = '../';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM bulk_quotes WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>asstes/css/style.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/user-dashboard.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>asstes/css/footer.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/brand-menu.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/header-menu.css">
    
    <style>
        .table-responsive { overflow-x: auto; }
        .user-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; }
        .user-table th, .user-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: middle;}
        .user-table th { background-color: #f8f9fa; font-weight: 600; color: #555; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .content-card { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .file-link { color: #004aad; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; }
        .file-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<div class="dashboard-container">
    
    <!-- Sidebar -->
    <?php require_once 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1>My Bulk Quotes</h1>
            <p>Track the status of your bulk quote requests.</p>
        </div>

        <div class="content-card">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                                <th>Uploaded File</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td>
                                        <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($row['message']); ?>">
                                            <?php echo htmlspecialchars($row['message']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['file_path']) && file_exists('../' . $row['file_path'])): ?>
                                            <a href="../<?php echo $row['file_path']; ?>" class="file-link" target="_blank" download>
                                                <i class="fa-solid fa-file-arrow-down"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span style="color: #999;">No File</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <?php 
                                        $status = ucfirst($row['status']);
                                        $badge_bg = '#e9ecef'; $badge_color = '#495057';
                                        
                                        if($status == 'Pending') { $badge_bg = '#fff3cd'; $badge_color = '#856404'; }
                                        if($status == 'Approved' || $status == 'Completed') { $badge_bg = '#d4edda'; $badge_color = '#155724'; }
                                        if($status == 'Rejected') { $badge_bg = '#f8d7da'; $badge_color = '#721c24'; }
                                        ?>
                                        <span class="status-badge" style="background-color: <?php echo $badge_bg; ?>; color: <?php echo $badge_color; ?>;">
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
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size: 40px; margin-bottom: 15px; color:#ddd;"></i>
                    <p>No bulk quotes found.</p>
                    <a href="request-quote.php" class="btn-primary" style="margin-top:10px; display:inline-block; padding:8px 15px; font-size:14px; text-decoration:none; background: #004aad; color: white; border-radius: 4px;">Request a Quote</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
