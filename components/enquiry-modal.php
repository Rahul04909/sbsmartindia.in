
<!-- Enquiry Modal Styles (Scoped to avoid conflicts if both modals are present) -->
<style>
/* Reusing quote modal styles where possible, adding specific enquiry classes */
.enquiry-modal-overlay {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.5); 
    backdrop-filter: blur(2px);
}

.enquiry-modal-content {
    background-color: #fff;
    margin: 5% auto; 
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    position: relative;
    animation: slideDownEnquiry 0.3s ease-out;
}

@keyframes slideDownEnquiry {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.enquiry-modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.enquiry-modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
    font-weight: 600;
}

.close-enquiry-modal {
    color: #aaa;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s;
    line-height: 1;
}

.close-enquiry-modal:hover {
    color: #000;
}

.enquiry-modal-body {
    padding: 20px;
}

/* Steps */
.enquiry-step { display: none; }
.enquiry-step.active { display: block; }

/* Product Summary */
.enquiry-product-summary {
    background: #f0f7ff;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #004aad;
    border: 1px solid #cfe2ff;
}
</style>

<div id="enquiryModal" class="enquiry-modal-overlay">
    <div class="enquiry-modal-content">
        <div class="enquiry-modal-header">
            <h3>Product Enquiry</h3>
            <span class="close-enquiry-modal" onclick="closeEnquiryModal()">&times;</span>
        </div>
        <div class="enquiry-modal-body">
            
            <div class="enquiry-product-summary">
                <strong>Enquiring for:</strong> <span id="e-product-name">Product Name</span>
            </div>

            <!-- Step 1: Email Input -->
            <div id="e-step-1" class="enquiry-step active">
                <div class="q-form-group">
                    <label class="q-label">Enter Email Address</label>
                    <input type="email" id="e-email-input" class="q-input" placeholder="e.g. name@company.com" required>
                </div>
                <button type="button" class="q-btn" onclick="sendEnquiryOTP()" id="btn-e-get-otp">Get OTP</button>
                <div id="msg-e-step-1" class="q-msg"></div>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="e-step-2" class="enquiry-step">
                <div class="q-form-group">
                    <label class="q-label">Enter OTP sent to <span id="e-otp-email-display" style="font-weight:bold;"></span></label>
                    <input type="text" id="e-otp-input" class="q-input" placeholder="Enter 6-digit OTP" required>
                </div>
                <button type="button" class="q-btn" onclick="verifyEnquiryOTP()" id="btn-e-verify-otp">Verify OTP</button>
                <button type="button" class="q-btn q-btn-secondary" onclick="backToEnquiryStep1()">Change Email</button>
                <div id="msg-e-step-2" class="q-msg"></div>
            </div>

            <!-- Step 3: Full Details Form -->
            <div id="e-step-3" class="enquiry-step">
                <form id="enquiry-details-form">
                    <!-- Hidden Fields -->
                    <input type="hidden" id="he-product-id" name="product_id">
                    <input type="hidden" id="he-product-name" name="product_name">
                    <input type="hidden" id="he-email" name="email">
                    
                    <div class="q-form-group">
                        <label class="q-label">Full Name</label>
                        <input type="text" name="name" class="q-input" required>
                    </div>

                    <div class="q-form-group">
                        <label class="q-label">Mobile Number</label>
                        <input type="tel" name="mobile" class="q-input" placeholder="10-digit Mobile" required>
                    </div>

                    <div class="q-form-group">
                        <label class="q-label">Message / Requirement</label>
                        <textarea name="message" class="q-input" rows="4" placeholder="I am interested in this product..." required></textarea>
                    </div>

                    <button type="submit" class="q-btn" id="btn-submit-enquiry">Submit Enquiry</button>
                    <div id="msg-e-step-3" class="q-msg"></div>
                </form>
            </div>
            
             <!-- Step 4: Success Message -->
            <div id="e-step-4" class="enquiry-step" style="text-align: center; padding: 20px;">
                <i class="fa-solid fa-check-circle" style="font-size: 50px; color: green; margin-bottom: 20px;"></i>
                <h3>Enquiry Sent!</h3>
                <p>We have received your enquiry.</p>
                <p>Our team will get back to you soon.</p>
                <button type="button" class="q-btn" onclick="closeEnquiryModal()" style="margin-top: 20px;">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
// --- Modal Logic ---
function openEnquiryModal(productId, productName) {
    document.getElementById('enquiryModal').style.display = 'block';
    
    // Set Product Info
    document.getElementById('e-product-name').innerText = productName;
    document.getElementById('he-product-id').value = productId;
    document.getElementById('he-product-name').value = productName;
    
    // Reset Steps
    document.querySelectorAll('.enquiry-step').forEach(el => el.classList.remove('active'));
    document.getElementById('e-step-1').classList.add('active');
    
    // Clear Messages
    $('#msg-e-step-1, #msg-e-step-2, #msg-e-step-3').text('');
}

function closeEnquiryModal() {
    document.getElementById('enquiryModal').style.display = 'none';
}

// Close on outside click
window.onclick = function(event) {
    var modal = document.getElementById('enquiryModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
    // Also handle quote modal closing here if needed, or rely on its own handler
    var qModal = document.getElementById('quoteModal');
    if (event.target == qModal) {
        qModal.style.display = "none";
    }
}

// --- AJAX Logic ---

// 1. Send OTP
function sendEnquiryOTP() {
    var email = $('#e-email-input').val();
    if(!email) {
        $('#msg-e-step-1').text('Please enter a valid email.').addClass('q-msg-error');
        return;
    }
    
    var btn = $('#btn-e-get-otp');
    btn.prop('disabled', true).text('Sending...');
    
    $.ajax({
        url: 'contact_handler.php', // Using contact_handler, or reuse quote_handler if logic serves? contact_handler is better for generic contact stuff.
        // Wait, quote_handler has OTP logic. contact_handler has it too (from Assisted Orders).
        // I'll use contact_handler.php as per plan.
        type: 'POST',
        data: { action: 'send_otp', email: email },
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).text('Get OTP');
            if(res.status === 'success') {
                $('#e-otp-email-display').text(email);
                $('#he-email').val(email);
                
                // Switch to Step 2
                $('#e-step-1').removeClass('active');
                $('#e-step-2').addClass('active');
            } else {
                $('#msg-e-step-1').text(res.message).addClass('q-msg-error');
            }
        },
        error: function() {
              btn.prop('disabled', false).text('Get OTP');
              $('#msg-e-step-1').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
}

function backToEnquiryStep1() {
    $('#e-step-2').removeClass('active');
    $('#e-step-1').addClass('active');
}

// 2. Verify OTP
function verifyEnquiryOTP() { // Note: contact_handler doesn't have 'verify_otp' action returning JSON only? 
    // Assisted orders does verification INSIDE submit.
    // Quote handler does verification as separate step?
    // Let's check contact_handler.php content again to see if it has 'verify_otp' action or if I need to add it.
    // If not, I can add it, or just rely on 'submit' to verify. 
    // But UI has 2 steps.
    // Quote handler has 'verify_otp'.
    // I should probably add 'verify_otp' to contact_handler.php to support this UI flow.
    
     var email = $('#e-email-input').val();
     var otp = $('#e-otp-input').val();
     
     if(!otp) {
        $('#msg-e-step-2').text('Please enter OTP.').addClass('q-msg-error');
        return;
    }
    
    var btn = $('#btn-e-verify-otp');
    btn.prop('disabled', true).text('Verifying...');
    
    $.ajax({
        url: 'contact_handler.php',
        type: 'POST',
        data: { action: 'verify_otp_only', email: email, otp: otp }, // New action needed in contact_handler
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).text('Verify OTP');
            if(res.status === 'success') {
                 // Switch to Step 3
                $('#e-step-2').removeClass('active');
                $('#e-step-3').addClass('active');
            } else {
                $('#msg-e-step-2').text(res.message).addClass('q-msg-error');
            }
        },
         error: function() {
              btn.prop('disabled', false).text('Verify OTP');
              $('#msg-e-step-2').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
}

// 3. Submit Enquiry Form
$('#enquiry-details-form').on('submit', function(e) {
    e.preventDefault();
    
    var btn = $('#btn-submit-enquiry');
    btn.prop('disabled', true).text('Submitting...');
    
    var formData = $(this).serialize() + '&action=submit_product_enquiry';
    // We already verified OTP in step 2, but secure backend should verify again or use session. 
    // For simplicity, we can pass OTP again if we kept it? 
    // Or just trust if step 2 passed? Step 2 just showed UI.
    // Secure way: verify OTP again on submit.
    // So I should keep OTP value and send it.
    var otp = $('#e-otp-input').val();
    formData += '&otp=' + otp;

    $.ajax({
        url: 'contact_handler.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).text('Submit Enquiry');
            if(res.status === 'success') {
                 // Switch to Step 4 (Success)
                $('#e-step-3').removeClass('active');
                $('#e-step-4').addClass('active');
            } else {
                $('#msg-e-step-3').text(res.message).addClass('q-msg-error');
            }
        },
         error: function() {
              btn.prop('disabled', false).text('Submit Enquiry');
              $('#msg-e-step-3').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
});
</script>
