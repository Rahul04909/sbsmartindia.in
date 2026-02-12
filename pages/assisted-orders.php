<?php
session_start();
$url_prefix = '../'; // For header/footer
require_once '../database/db_config.php';
$page_title = "Assisted Orders - SB Smart India";
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
    <link rel="stylesheet" href="../assets/css/assisted-orders.css">
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<main class="assisted-orders-page">

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="hero-text">
                <span class="hero-tag"><i class="fa-solid fa-star"></i> Premium Service</span>
                <h1 class="hero-title">Assisted Orders</h1>
                <p class="hero-desc">Experience personalized procurement. From bulk requirements to complex technical specifications, our experts are here to guide you every step of the way.</p>
                <div class="hero-buttons">
                    <a href="#contactSection" class="btn-primary-hero">Get a Quote</a>
                    <a href="contact-us.php" class="btn-outline-hero">Contact Us</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="../assets/images/assisted-orders-hero.png" alt="Assisted Orders Illustration" onerror="this.onerror=null;this.src='https://placehold.co/600x400/004aad/FFF?text=Procurement+Consultation';">
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="section-padding process-section">
        <div class="container">
            <div class="process-layout">
                <div class="process-intro">
                    <h2>Why choose Assisted Orders?</h2>
                    <p>For standard procurements, our cart is perfect. But when your needs go beyond the click of a button, our Assisted Order service ensures you get the technical accuracy and commercial flexibility you require.</p>
                    
                    <ul class="feature-list">
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>Bulk Pricing Strategy</strong>
                                Get customized quotes and tiered pricing for large volume orders.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>Technical Consultation</strong>
                                Ensure product compatibility with your existing infrastructure.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>Project Management</strong>
                                Phased deliveries and scheduled dispatch for long-term projects.
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle-check"></i>
                            <div>
                                <strong>GST & Credit Support</strong>
                                Seamless B2B billing and credit terms for verified partners.
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="process-grid-container">
                    <!-- Step 1 -->
                    <div class="process-card">
                        <div class="process-icon"><i class="fa-regular fa-comments"></i></div>
                        <h3>1. Enable</h3>
                        <p>Share your requirements via form, email, or a direct call with our engineers.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="process-card">
                        <div class="process-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <h3>2. Evaluate</h3>
                        <p>We analyze technical specs and check availability across our global network.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="process-card">
                        <div class="process-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <h3>3. Estimate</h3>
                        <p>Receive a detailed commercial proposal with best-in-class pricing.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="process-card">
                        <div class="process-icon"><i class="fa-solid fa-box-open"></i></div>
                        <h3>4. Execute</h3>
                        <p>On approval, we process your order with priority handling and tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Connect Section -->
    <section class="section-padding connect-section" id="contactSection">
        <div class="container">
            <div class="section-title-center">
                <h2>Connect With Us</h2>
                <p>Choose how you would like to proceed.</p>
            </div>

            <div class="connect-wrapper">
                <!-- Direct Contact -->
                <div class="contact-card-left">
                    <h3>Direct Contact</h3>
                    <p style="margin-bottom:20px; color:#666;">Our sales desk is operational Mon-Fri, 9:30 AM to 6:30 PM IST.</p>
                    
                    <div class="contact-info-block">
                        <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-text">
                            <h5>EMAIL SUPPORT</h5>
                            <p>marcom.sbsyscon@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-info-block">
                        <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-text">
                            <h5>PHONE SUPPORT</h5>
                            <p>+91-9899598955</p>
                            <small>+91-9999999999 (Alt)</small>
                        </div>
                    </div>

                    <div class="info-note">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>We typically respond to emails within 24 business hours.</span>
                    </div>
                </div>

                <!-- Form -->
                <div class="form-card-right">
                    <h3>Submit a Requirement</h3>
                    <form id="requirementForm">
                        <input type="hidden" name="action" value="submit_assisted_order">
                        <div class="form-grid">
                            <div class="form-group">
                                <label style="display:block; margin-bottom:5px; font-weight:600;">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="form-group">
                                <label style="display:block; margin-bottom:5px; font-weight:600;">Company Name</label>
                                <input type="text" name="company" class="form-control" placeholder="ABC Corp">
                            </div>
                        </div>

                        <div class="form-group-full">
                            <label style="display:block; margin-bottom:5px; font-weight:600;">Email Address</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="email" name="email" id="contactEmail" class="form-control" placeholder="john@example.com" required>
                                <button type="button" id="sendOtpBtn" class="btn-primary-hero" style="white-space: nowrap; padding: 12px 20px; font-size: 14px; background-color: #004aad; border:none; color:white; border-radius: 6px;">Verify Email</button>
                            </div>
                            <small class="text-muted" id="emailHelp" style="display:block; margin-top:5px; font-size:12px; color:#666;">Click "Verify Email" to receive an OTP.</small>
                        </div>
                        
                        <div id="otpSection" style="display: none; margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eee;">
                            <div class="form-group">
                                <label style="display:block; margin-bottom:5px; font-weight:600;">Enter OTP</label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="text" name="otp" id="otpInput" class="form-control" placeholder="6-digit OTP" maxlength="6">
                                    <span id="resendOtp" style="font-size: 13px; color: #004aad; cursor: pointer; white-space: nowrap;">Resend OTP</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group-full">
                            <label style="display:block; margin-bottom:5px; font-weight:600;">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                        </div>

                        <div class="form-group-full">
                            <label style="display:block; margin-bottom:5px; font-weight:600;">Requirement Details</label>
                            <textarea name="message" class="form-control" placeholder="Please describe your project, required products, or specific SKUs..." required></textarea>
                        </div>

                        <button type="submit" class="btn-submit-req" id="submitBtn" disabled>Submit Request &rarr;</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <div class="cta-banner">
        <div class="container cta-content">
            <h2>Ready to Streamline Your Procurement?</h2>
            <p>Join hundreds of businesses that trust SB Smart for their industrial supplies.</p>
            <a href="../products.php" class="btn-white">Browse Catalog</a>
        </div>
    </div>

</main>

<?php require_once '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function() {
    
    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Send OTP Logic
    $('#sendOtpBtn').click(function() {
        var email = $('#contactEmail').val();
        var btn = $(this);

        if(!email) {
            alert('Please enter your email first.');
            return;
        }
        if(!validateEmail(email)) {
            alert('Please enter a valid email address.');
                return;
        }

        btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: '../contact_handler.php',
            type: 'POST',
            data: { action: 'send_otp', email: email },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);
                    $('#otpSection').slideDown();
                    // btn.hide(); // Keep button visible but disabled? Or hide. Let's disable.
                    $('#contactEmail').prop('readonly', true);
                    $('#emailHelp').text('OTP sent to ' + email);
                    $('#submitBtn').prop('disabled', false); // Enable submit
                    btn.text('Sent');
                } else {
                    alert(response.message);
                    btn.prop('disabled', false).text('Verify Email');
                }
            },
            error: function() {
                alert('Error sending OTP. Please try again.');
                btn.prop('disabled', false).text('Verify Email');
            }
        });
    });

    // Resend OTP
    $('#resendOtp').click(function() {
        var email = $('#contactEmail').val();
        var btn = $(this);
        
        btn.text('Sending...');
            $.ajax({
            url: '../contact_handler.php',
            type: 'POST',
            data: { action: 'send_otp', email: email },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                btn.text('Resend OTP');
            }
        });
    });

    // Submit Form
    $('#requirementForm').submit(function(e) {
        e.preventDefault();
        
        var otp = $('#otpInput').val();
        if(!otp) {
            alert('Please enter the OTP received on your email.');
            return;
        }

        var form = $(this);
        var btn = $('#submitBtn');
        var originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: '../contact_handler.php',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    form.html('<div style="text-align:center; padding:40px;"><i class="fa-solid fa-circle-check" style="font-size:48px; color:#00b894; margin-bottom:20px;"></i><h3>Request Received!</h3><p>Our team will review your requirements and get back to you shortly.</p></div>');
                } else {
                    alert(response.message);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('An error occurred. Please try again later.');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

</body>
</html>
