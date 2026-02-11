<?php
session_start();
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "Privacy Policy - S.B. Syscon Pvt. Ltd.";
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
        /* Highlight specific text */
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
        <a href="../index.php">Home</a> &gt; <span>Privacy Policy</span>
    </div>

    <div class="policy-container">
        <div class="policy-header">
            <h1>Privacy Policy</h1>
            <p class="policy-date">Last updated: November 22, 2025</p>
        </div>

        <div class="policy-content">
            <p class="mb-4">
                At <strong>S.B. Syscon Pvt. Ltd.</strong> ("we", "us", "our"), operating <strong>sbsmart.in</strong>, we value your trust and are committed to protecting your personal information. This Privacy Policy describes how we collect, use, and safeguard your data.
            </p>

            <h4>1. Information We Collect</h4>
            <p>We collect information necessary to provide our services, including:</p>
            <ul>
                <li><strong>Personal Identification:</strong> Name, Email address, Phone number, Billing & Shipping addresses.</li>
                <li><strong>Transaction Data:</strong> Order history, Payment method details (card numbers are NOT stored by us).</li>
                <li><strong>Technical Data:</strong> IP address, Browser type, Device information, and Cookies to improve site functionality.</li>
            </ul>

            <h4>2. How We Use Your Data</h4>
            <p>We use your information for the following purposes:</p>
            <ul>
                <li>To process and deliver your orders.</li>
                <li>To communicate with you regarding order updates, support queries, or promotional offers (only if opted in).</li>
                <li>To improve our website functionality and user experience.</li>
                <li>To comply with legal obligations and prevent fraud.</li>
            </ul>

            <h4>3. Data Sharing & Disclosure</h4>
            <p>We do not sell your personal data. We may share information with trusted third parties solely for business operations:</p>
            <ul>
                <li><strong>Service Providers:</strong> Logistics partners (shipping) and Payment Gateways (processing payments).</li>
                <li><strong>Legal Requirements:</strong> If required by law or to protect our rights and safety.</li>
            </ul>

            <h4>4. Security Measures</h4>
            <p>We implement appropriate security measures to protect your personal data against unauthorized access or alteration. We use SSL encryption for data transmission. However, no internet transmission is completely secure, so we encourage you to protect your account credentials.</p>

            <h4>5. Cookies</h4>
            <p>Our website uses cookies to enhance your browsing experience, remember your cart items, and analyze traffic. You can choose to disable cookies through your browser settings, but some site features may not function properly.</p>

            <h4>6. Your Rights</h4>
            <p>You have the right to access, correct, or request deletion of your personal data. You can manage your account details via the "My Account" section or contact us directly.</p>

            <div class="alert-custom">
                <strong>Contact Privacy Team:</strong> <br>
                If you have any concerns regarding your privacy, please email us at <a href="mailto:marcom.sbsyscon@gmail.com">marcom.sbsyscon@gmail.com</a>.
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
