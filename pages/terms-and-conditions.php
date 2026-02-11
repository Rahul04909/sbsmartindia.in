<?php
session_start();
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "Terms & Conditions - S.B. Syscon Pvt. Ltd.";
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
        <a href="../index.php">Home</a> &gt; <span>Terms & Conditions</span>
    </div>

    <div class="policy-container">
        <div class="policy-header">
            <h1>Terms & Conditions</h1>
            <p class="policy-date">Last updated: November 22, 2025</p>
        </div>

        <div class="policy-content">
            <p class="mb-4">
                Welcome to <strong>sbsmart.in</strong>. These Terms and Conditions govern your use of our website and purchase of products from <strong>S.B. Syscon Pvt. Ltd.</strong>. By accessing this website, you agree to these terms in full.
            </p>

            <h4>1. Acceptance of Terms</h4>
            <p>By using this Site and placing an order, you confirm that you are at least 18 years old or visiting under the supervision of a parent or guardian. You agree to be bound by these Terms and our Privacy Policy.</p>

            <h4>2. Product Information & Pricing</h4>
            <p>We strive to provide accurate product descriptions and pricing. However, errors may occur:</p>
            <ul>
                <li><strong>Pricing:</strong> Prices are in Indian Rupees (INR) and exclude taxes unless stated otherwise. We reserve the right to change prices without notice.</li>
                <li><strong>Availability:</strong> All orders are subject to product availability. We reserve the right to discontinue any product at any time.</li>
                <li><strong>Accuracy:</strong> We ensure colors and specifications are displayed accurately, but cannot guarantee that your monitor's display will be accurate.</li>
            </ul>

            <h4>3. Orders & Payments</h4>
            <p>Placing an order constitutes an offer to purchase. Order confirmation via email does not signify our final acceptance until payment is verified and the product is dispatched. We use secure third-party payment gateways (like CCAvenue) and do not store complete credit card details.</p>

            <h4>4. Intellectual Property</h4>
            <p>All content included on this site, such as text, graphics, logos, images, and software, is the property of S.B. Syscon Pvt. Ltd. or its content suppliers and protected by copyright laws. Unauthorized use of any content is strictly prohibited.</p>

            <h4>5. Limitation of Liability</h4>
            <p>We shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use or inability to use the services or products procured using the services. Our maximum liability in any event shall be limited to the amount paid by you for the specific product.</p>

            <h4>6. Governing Law</h4>
            <p>These terms shall be governed by and constructed in accordance with the laws of India. Any disputes arising in relation to these terms shall be subject to the exclusive jurisdiction of the courts in Faridabad, Haryana.</p>

            <h4>7. Changes to Terms</h4>
            <p>We reserve the right to modify these Terms & Conditions at any time. Significant changes will be posted on this page with an updated revision date.</p>

            <div class="alert-custom">
                <strong>Questions?</strong> <br>
                If you have any questions regarding these Terms, please contact us at <a href="mailto:marcom.sbsyscon@gmail.com">marcom.sbsyscon@gmail.com</a>.
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
