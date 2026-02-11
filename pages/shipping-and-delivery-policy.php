<?php
session_start();
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "Shipping & Delivery Policy - S.B. Syscon Pvt. Ltd.";
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../asstes/css/style.css">
    <link rel="stylesheet" href="../asstes/css/footer.css">
    <link rel="stylesheet" href="../assets/css/brand-menu.css">
    <link rel="stylesheet" href="../assets/css/header-menu.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .policy-container {
            background: #fff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin: 30px 0 60px 0;
        }
        .policy-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 30px;
            margin-bottom: 40px;
            text-align: center;
        }
        .policy-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .policy-date {
            color: #888;
            font-size: 0.95rem;
        }
        .policy-content {
            max-width: 900px;
            margin: 0 auto;
        }
        .policy-content h4 {
            color: #222;
            font-weight: 700;
            margin-top: 35px;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .policy-content p {
            color: #4a4a4a;
            line-height: 1.8;
            font-size: 1rem;
            margin-bottom: 15px;
        }
        .policy-content ul {
            padding-left: 20px;
            margin-bottom: 25px;
        }
        .policy-content li {
            color: #4a4a4a;
            line-height: 1.7;
            margin-bottom: 8px;
            position: relative;
        }
        .policy-content strong {
            color: #333;
        }
        .breadcrumbs {
            margin: 25px 0 10px 0;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .breadcrumbs a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumbs a:hover {
            color: #000;
        }
        .alert-custom {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 20px;
            border-radius: 4px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<div class="container">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <a href="../index.php">Home</a> &gt; <span>Shipping & Delivery Policy</span>
    </div>

    <div class="policy-container">
        <div class="policy-header">
            <h1>Shipping & Delivery Policy</h1>
            <p class="policy-date">Last updated: November 22, 2025</p>
        </div>

        <div class="policy-content">
            <p class="mb-4">
                <strong>S.B. Syscon Pvt. Ltd.</strong> is committed to delivering your order accurately, in good condition, and always on time. We partner with reputed courier agencies to ensure that your verified industrial products reach you safely.
            </p>

            <h4>1. Shipping Locations</h4>
            <p>We ship to locations across India. While we strive to cover every pin code, certain remote areas may have limited shipping options or may require additional delivery time.</p>

            <h4>2. Shipping Methods & Charges</h4>
            <p>Shipping costs are calculated based on the weight, dimensions of the package, and the destination pin code. You can view the exact shipping charges at the checkout page before making a payment. We may offer <strong>Free Shipping</strong> promotions for orders above a certain value, which will be highlighted on the website.</p>

            <h4>3. Delivery Timelines</h4>
            <p>Estimated delivery times are displayed at checkout and depending on your location and product availability. Typical timelines are:</p>
            <ul>
                <li><strong>Metro Cities:</strong> 3–7 business days</li>
                <li><strong>Rest of India:</strong> 5–10 business days</li>
            </ul>
            <p class="small text-muted" style="font-style: italic;">Note: Business days exclude Sundays and public holidays.</p>

            <h4>4. Order Processing</h4>
            <p>Orders placed on business days before our cut-off time are typically processed the same day. Orders placed on weekends or holidays will be processed on the next business day. Processing times may vary for items on backorder or special custom orders.</p>

            <h4>5. Tracking Your Order</h4>
            <p>Once your order is dispatched, you will receive a tracking number via Email and SMS. You can use this tracking number on our logistics partner's website to check the status of your delivery. You can also track your order from the <strong>"My Orders"</strong> section of your account.</p>

            <h4>6. Delivery Issues</h4>
            <p>If you experience any delays or if a package appears to be lost or damaged in transit, please contact us immediately.</p>
            <div class="alert-custom">
                <strong>Contact Support:</strong> <br>
                Email: <a href="mailto:marcom.sbsyscon@gmail.com" style="color:#0d6efd; text-decoration:none;">marcom.sbsyscon@gmail.com</a> <br>
                Phone: <a href="tel:+911294150555" style="color:#0d6efd; text-decoration:none;">(+91) 129 4150 555</a>
            </div>

            <h4>7. Failed Deliveries</h4>
            <p>If a delivery fails due to an incorrect address provided by you or non-availability of the recipient, the product may be returned to us. In such cases, additional re-delivery charges may apply. We request you to provide accurate address details including landmarks and mobile numbers.</p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
