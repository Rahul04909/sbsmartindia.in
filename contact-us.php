<?php
session_start();
$url_prefix = './';
require_once 'database/db_config.php';
$page_title = "Contact Us - S.B. Syscon Pvt. Ltd.";
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
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .contact-container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        .contact-info-side {
            flex: 1;
            background-color: #004aad; /* Brand Color */
            color: #fff;
            padding: 50px;
            min-width: 300px;
        }
        .contact-info-side h2 { font-size: 28px; margin-bottom: 20px; font-weight: 700; }
        .contact-info-side p { margin-bottom: 30px; opacity: 0.9; line-height: 1.6; }
        .info-item { display: flex; align-items: flex-start; margin-bottom: 25px; }
        .info-item i { font-size: 20px; margin-right: 15px; margin-top: 5px; }
        .info-item span { font-size: 15px; }
        
        .contact-form-side {
            flex: 1.5;
            padding: 50px;
            min-width: 300px;
        }
        .contact-form-side h2 { color: #333; margin-bottom: 30px; font-weight: 700; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-control:focus { outline: none; border-color: #004aad; }
        textarea.form-control { resize: vertical; min-height: 120px; }
        
        .btn-submit {
            background-color: #004aad;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-block;
        }
        .btn-submit:hover { background-color: #003380; }
        .btn-submit:disabled { background-color: #ccc; cursor: not-allowed; }

        .otp-section { display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; }
        .otp-input-group { display: flex; gap: 10px; align-items: center; }
        .resend-otp { margin-left: auto; font-size: 13px; color: #004aad; cursor: pointer; }
        
        .breadcrumbs { margin: 25px auto; max-width: 1000px; font-size: 0.9rem; color: #6c757d; padding: 0 15px; }
        .breadcrumbs a { color: #6c757d; text-decoration: none; }
        .breadcrumbs a:hover { color: #000; }

        @media(max-width: 768px) {
            .contact-container { margin: 20px; flex-direction: column; }
        }
    </style>
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<div class="breadcrumbs">
    <a href="index.php">Home</a> &gt; <span>Contact Us</span>
</div>

<div class="contact-container">
    <!-- Info Side -->
    <div class="contact-info-side">
        <h2>Get in Touch</h2>
        <p>Have questions about our products or need a custom quote? Our team is here to help you.</p>
        
        <div class="info-item">
            <i class="fa-solid fa-location-dot"></i>
            <span>1D-45A, NIT Faridabad, Haryana, India – 121001</span>
        </div>
        <div class="info-item">
            <i class="fa-solid fa-phone"></i>
            <span>(+91) 129 4150 555</span>
        </div>
        <div class="info-item">
            <i class="fa-solid fa-envelope"></i>
            <span>marcom.sbsyscon@gmail.com</span>
        </div>
        <div class="info-item">
            <i class="fa-solid fa-clock"></i>
            <span>Mon - Sat: 9:30am – 6:30pm</span>
        </div>
    </div>

    <!-- Form Side -->
    <div class="contact-form-side">
        <h2>Send us a Message</h2>
        <form id="contactForm">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Your Name">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <div style="display: flex; gap: 10px;">
                    <input type="email" name="email" id="contactEmail" class="form-control" required placeholder="name@example.com">
                    <button type="button" id="sendOtpBtn" class="btn-submit" style="white-space: nowrap; padding: 12px 15px;">Verify Email</button>
                </div>
                <small class="text-muted" id="emailHelp">Click "Verify Email" to receive an OTP.</small>
            </div>
            
            <div id="otpSection" class="otp-section">
                <div class="form-group">
                    <label>Enter OTP</label>
                    <div class="otp-input-group">
                        <input type="text" name="otp" id="otpInput" class="form-control" placeholder="6-digit OTP" maxlength="6">
                        <span class="resend-otp" id="resendOtp">Resend OTP</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" required placeholder="Mobile Number">
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" required placeholder="How can we help you?"></textarea>
            </div>

            <button type="submit" class="btn-submit" id="finalSubmitBtn" disabled>Submit Request</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(document).ready(function() {
        var isEmailVerified = false;

        // Send OTP
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
                url: 'contact_handler.php',
                type: 'POST',
                data: { action: 'send_otp', email: email },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        alert(response.message);
                        $('#otpSection').slideDown();
                        $('#sendOtpBtn').parent().hide(); // Hide email input group parts or just disable
                        $('#contactEmail').prop('readonly', true);
                        $('#emailHelp').text('OTP sent to ' + email);
                        $('#finalSubmitBtn').prop('disabled', false); // Enable submit, but we will verify OTP on submit
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
            if(!email) return;
            
            $(this).text('Sending...');
             $.ajax({
                url: 'contact_handler.php',
                type: 'POST',
                data: { action: 'send_otp', email: email },
                dataType: 'json',
                success: function(response) {
                    alert(response.message);
                    $('#resendOtp').text('Resend OTP');
                }
            });
        });

        // Submit Form
        $('#contactForm').submit(function(e) {
            e.preventDefault();
            
            var otp = $('#otpInput').val();
            if(!otp) {
                alert('Please enter the OTP received on your email.');
                return;
            }

            var formData = $(this).serialize();
            formData += '&action=submit_contact';

            var btn = $('#finalSubmitBtn');
            btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: 'contact_handler.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                         // Replace form with success message
                         $('.contact-form-side').html('<div style="text-align:center; padding:50px;"><i class="fa-solid fa-circle-check" style="font-size:50px; color:green; margin-bottom:20px;"></i><h2>Message Sent!</h2><p>' + response.message + '</p><a href="index.php" class="btn-submit" style="text-decoration:none;">Back to Home</a></div>');
                    } else {
                        alert(response.message);
                        btn.prop('disabled', false).text('Submit Request');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    btn.prop('disabled', false).text('Submit Request');
                }
            });
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>

</body>
</html>
