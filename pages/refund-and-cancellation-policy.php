<?php
session_start();
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "Refund & Cancellation Policy - S.B. Syscon Pvt. Ltd.";
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
        .alert-custom a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        .alert-custom a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<div class="container">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <a href="../index.php">Home</a> &gt; <span>Refund & Cancellation Policy</span>
    </div>

    <div class="policy-container">
        <div class="policy-header">
            <h1>Refund & Cancellation Policy</h1>
            <p class="policy-date">Last updated: November 22, 2025</p>
        </div>

        <div class="policy-content">
            <p class="mb-4">
                At <strong>S.B. Syscon Pvt. Ltd.</strong>, we want you to be completely satisfied with your purchase. However, we understand that sometimes you may need to return a product or cancel an order. This policy outlines the process and conditions.
            </p>

            <h4>1. Order Cancellation</h4>
            <p>You may cancel an order <strong>before it has been dispatched</strong> from our warehouse. To cancel, please contact our support team immediately.</p>
            <ul>
                <li><strong>Pre-Dispatch Cancellation:</strong> Full refund will be initiated to your original payment method.</li>
                <li><strong>Post-Dispatch:</strong> Once an order is dispatched, it cannot be cancelled. You may refuse delivery or initiate a return as per our Return Policy.</li>
            </ul>

            <h4>2. Returns Policy</h4>
            <p>We accept returns for items that are <strong>damaged, defective, or incorrect</strong> (different from what was ordered). Returns must be initiated within <strong>7 days</strong> of delivery.</p>
            <p><strong>Conditions for Return:</strong></p>
            <ul>
                <li>Product must be unused and in its original packaging with all tags, manuals, and accessories intact.</li>
                <li>You must provide proof of purchase (Order ID / Invoice).</li>
                <li>For damaged/defective claims, unboxing video or photos are recommended.</li>
            </ul>

            <h4>3. Refund Process</h4>
            <p>Once we receive your return, our quality team will inspect the item. Upon approval, a refund will be processed.</p>
            <ul>
                <li><strong>Refund Method:</strong> Refunds are credited to the original payment source (Credit Card, UPI, etc.) or Bank Account.</li>
                <li><strong>Processing Time:</strong> Refunds typically take <strong>7–14 business days</strong> to reflect in your account, depending on your bank's processing time.</li>
            </ul>

            <h4>4. Non-Refundable Items</h4>
            <p>Certain items cannot be returned due to their nature, including:</p>
            <ul>
                <li>Custom-made or special order items.</li>
                <li>Products with broken seals (for hygiene or electronic safety reasons).</li>
                <li>Clearance sale items (unless defective).</li>
            </ul>

            <h4>5. How to Initiate a Return</h4>
            <div class="alert-custom">
                <strong>Steps to follow:</strong>
                <ol class="mb-0 mt-2" style="padding-left: 20px;">
                    <li class="mb-2">Send an email to <a href="mailto:marcom.sbsyscon@gmail.com">marcom.sbsyscon@gmail.com</a> with your Order ID and reason for return.</li>
                    <li class="mb-2">Attach clear photos/videos if the item is damaged.</li>
                    <li>Wait for our Return Authorization and shipping instructions.</li>
                </ol>
            </div>

            <p class="mt-4 text-muted small">
                For any questions regarding refunds or cancellations, please reach out to us at <a href="mailto:marcom.sbsyscon@gmail.com">marcom.sbsyscon@gmail.com</a>.
            </p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
