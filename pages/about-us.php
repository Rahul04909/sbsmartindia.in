<?php
session_start();
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "About Us - S.B. Syscon Pvt. Ltd.";
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
    <link rel="stylesheet" href="../assets/css/about-us.css">
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <a href="../index.php">Home</a> &gt; <span>About Us</span>
</div>

<!-- Hero Section -->
<section class="about-hero">
    <div class="container">
        <h1>About S.B. Syscon Pvt. Ltd.</h1>
        <p>Empowering Industry Through Innovation, Integrity & Excellence</p>
    </div>
</section>

<!-- Legacy Section -->
<section class="legacy-section">
    <div class="container">
        <div class="legacy-content">
            <div class="legacy-text">
                <div class="section-title" style="text-align: left; margin-bottom: 20px;">
                    <h2>Our Legacy</h2>
                </div>
                <p>The story of S.B. Syscon Pvt. Ltd. is one of perseverance, trust, and growth. Founded over 34 years ago by Mr. R.P. Pandey, an engineering graduate, the company began in a modest rented space in Faridabad.</p>
                <br>
                <p>With a steadfast commitment to industrial reliability and technical excellence, we’ve grown into one of the most respected and trusted names in the industrial supply sector.</p>
                <br>
                <p>Today, with a proud legacy spanning more than three decades, we continue to empower industries across India and around the world.</p>
            </div>
            <div class="legacy-image">
                <img src="../asstes/frontend/industry .png" alt="S.B. Syscon Legacy">
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section">
    <div class="container">
        <div class="section-title">
            <h2>Who We Are</h2>
            <p>At S.B. Syscon Pvt. Ltd., we specialize in delivering a complete range of high-quality electrical and industrial equipment for industries including manufacturing, infrastructure, power, engineering, and energy.</p>
        </div>
        
        <div class="mission-grid">
            <div class="mission-card">
                <i class="fa-solid fa-handshake mission-icon"></i>
                <h3>Authorized Channel Partners</h3>
                <p>Trusted partners for leading global brands like SIEMENS, LAPP, INNOMOTICS, SCHNEIDER ASCO, SECURE METERS, BCH, and FLENDER GEARBOXES.</p>
            </div>
            <div class="mission-card">
                <i class="fa-solid fa-globe mission-icon"></i>
                <h3>Bridge to Global Manufacturers</h3>
                <p>We connect renowned manufacturers with thousands of end-users and OEMs across India and globally.</p>
            </div>
            <div class="mission-card">
                <i class="fa-solid fa-award mission-icon"></i>
                <h3>Respected & Reliable</h3>
                <p>Recognized for our ethical business practices, technical expertise, and commitment to customer satisfaction.</p>
            </div>
        </div>
    </div>
</section>

<!-- Product Range Section -->
<section class="product-range-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Product Range</h2>
            <p>We offer a comprehensive portfolio tailored to meet diverse industrial needs.</p>
        </div>
        <div class="range-grid">
            <div class="range-item">
                <i class="fa-solid fa-bolt" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Switchgear & Control Gear</h4>
                <p>Protection devices and control gear products.</p>
            </div>
            <div class="range-item">
                <i class="fa-solid fa-plug" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Wires & Cables</h4>
                <p>For diverse industrial applications.</p>
            </div>
            <div class="range-item">
                <i class="fa-solid fa-chart-line" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Energy Management</h4>
                <p>Monitoring and management solutions.</p>
            </div>
            <div class="range-item">
                <i class="fa-solid fa-cogs" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Heavy-Duty Gearboxes</h4>
                <p>For mechanical and industrial uses.</p>
            </div>
            <div class="range-item">
                <i class="fa-solid fa-box" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Modular Enclosures</h4>
                <p>For panel and equipment housing.</p>
            </div>
            <div class="range-item">
                <i class="fa-solid fa-tools" style="font-size: 40px; color: #004aad; margin-bottom: 15px;"></i>
                <h4>Customized Solutions</h4>
                <p>Sourcing solutions for specific projects.</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <p>Each product is carefully selected to meet the highest standards of quality, performance, and reliability.</p>
        </div>
    </div>
</section>

<!-- Infrastructure Section -->
<section class="infra-section">
    <div class="container">
        <div class="section-title" style="margin-bottom: 30px;">
            <h2>Infrastructure & Inventory Readiness</h2>
            <p style="color: rgba(255,255,255,0.8);">Our strength lies in always being prepared to serve our customers efficiently and effectively.</p>
        </div>
        <ul class="infra-list">
            <li><i class="fa-solid fa-check"></i> <span>20,000+ sq. ft. modern warehouse with ready-to-dispatch inventory</span></li>
            <li><i class="fa-solid fa-check"></i> <span>ERP-integrated inventory management with real-time stock monitoring</span></li>
            <li><i class="fa-solid fa-check"></i> <span>Thousands of SKUs for immediate dispatch, minimizing downtime</span></li>
            <li><i class="fa-solid fa-check"></i> <span>E-Commerce Portal: www.sbsmart.in for 24/7 access</span></li>
            <li><i class="fa-solid fa-check"></i> <span>Robust logistics for timely deliveries across India and globally</span></li>
        </ul>
        <div style="text-align: center; margin-top: 30px;">
            <p>Our deep inventory capabilities ensure that you stay on schedule — no project delays, no compromises.</p>
        </div>
    </div>
</section>

<!-- ARC & Global Section -->
<section class="arc-global-section">
    <div class="container">
        <div class="content-row">
            <div class="content-col">
                <h3>Annual Rate Contracts Division</h3>
                <p>Understanding the unique needs of large corporates and multinational companies, we have a dedicated Annual Rate Contracts Division.</p>
                <ul style="list-style: none; padding: 0; margin-top: 15px;">
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-arrow-right" style="color: #004aad; margin-right: 10px;"></i> Centralized rate contracts for companies with multiple plants.</li>
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-arrow-right" style="color: #004aad; margin-right: 10px;"></i> Cost-efficient supply chains ensuring savings.</li>
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-arrow-right" style="color: #004aad; margin-right: 10px;"></i> Seamless coordination across different sites.</li>
                    <li style="margin-bottom: 10px;"><i class="fa-solid fa-arrow-right" style="color: #004aad; margin-right: 10px;"></i> Customized support services for dynamic needs.</li>
                </ul>
            </div>
            
            <div class="content-col">
                <h3>Our Global Reach</h3>
                <p>We are proud to serve industries not just within India, but across the globe.</p>
                <div style="display: flex; gap: 15px; margin-top: 15px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px; background: #f8f9fa; padding: 15px; border-radius: 6px;">
                        <h4 style="color: #004aad; font-size: 16px;">Exporting to 20+ Countries</h4>
                        <p style="font-size: 14px;">Asia, Middle East, and Africa.</p>
                    </div>
                    <div style="flex: 1; min-width: 200px; background: #f8f9fa; padding: 15px; border-radius: 6px;">
                        <h4 style="color: #004aad; font-size: 16px;">IEC Registered</h4>
                        <p style="font-size: 14px;">Seamless international operations & compliance.</p>
                    </div>
                </div>
                <p style="margin-top: 15px;">Making S.B. Syscon Pvt. Ltd. a reliable partner, worldwide.</p>
            </div>
        </div>
    </div>
</section>

<!-- Key Strengths -->
<section class="strengths-section">
    <div class="container">
        <div class="section-title">
            <h2>Why Customers Choose Us</h2>
            <p>What sets us apart from the rest of the industry.</p>
        </div>
        <div class="strength-grid">
            <div class="strength-card">
                <h4>Inventory Readiness</h4>
                <p>Extensive stock ensures rapid delivery and minimal downtime.</p>
            </div>
            <div class="strength-card">
                <h4>Wide Product Range</h4>
                <p>Covering everything from switchgear to heavy-duty gearboxes.</p>
            </div>
            <div class="strength-card">
                <h4>Trusted Brands</h4>
                <p>We work with top-tier global manufacturers to ensure quality.</p>
            </div>
            <div class="strength-card">
                <h4>Expert Support</h4>
                <p>Our experienced team provides reliable, solution-oriented guidance.</p>
            </div>
            <div class="strength-card">
                <h4>Competitive Pricing</h4>
                <p>Value-driven pricing without compromising on performance.</p>
            </div>
            <div class="strength-card">
                <h4>Customer Focus</h4>
                <p>Seamless service, trust, and long-term partnerships.</p>
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section class="values-section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2>Looking Ahead</h2>
            <p>Driven by a vision for innovation and leadership in industrial solutions.</p>
            <br>
            <p style="font-style: italic; font-size: 18px; color: #333;">"At S.B. Syscon Pvt. Ltd., we are not just moving with the times — we are shaping the future of industrial solutions."</p>
            <br>
            <a href="../pages/contact-us.php" class="btn-primary" style="padding: 12px 30px; text-decoration: none; display: inline-block; background: #004aad; color: white; border-radius: 6px; font-weight: 600;">Contact Us Today</a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
