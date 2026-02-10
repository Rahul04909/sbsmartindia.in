
<!-- Quote Modal Styles -->
<style>
/* Modal Overlay */
.quote-modal-overlay {
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

/* Modal Content */
.quote-modal-content {
    background-color: #fff;
    margin: 5% auto; 
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    position: relative;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Header */
.quote-modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.quote-modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
    font-weight: 600;
}

.close-quote-modal {
    color: #aaa;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s;
    line-height: 1;
}

.close-quote-modal:hover {
    color: #000;
}

/* Body */
.quote-modal-body {
    padding: 20px;
}

/* Form Groups */
.q-form-group {
    margin-bottom: 15px;
}

.q-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    font-size: 14px;
    color: #555;
}

.q-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.q-input:focus {
    border-color: #004aad;
    outline: none;
}

/* Steps */
.quote-step { display: none; }
.quote-step.active { display: block; }

/* Buttons */
.q-btn {
    width: 100%;
    padding: 12px;
    background-color: #004aad;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.q-btn:hover { background-color: #003380; }
.q-btn:disabled { background-color: #ccc; cursor: not-allowed; }

.q-btn-secondary {
    background-color: #6c757d;
    margin-top: 10px;
}
.q-btn-secondary:hover { background-color: #5a6268; }

/* Product Summary in Modal */
.quote-product-summary {
    background: #f0f7ff;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #004aad;
    border: 1px solid #cfe2ff;
}

/* Messages */
.q-msg {
    margin-top: 10px;
    font-size: 13px;
    text-align: center;
}
.q-msg-success { color: green; }
.q-msg-error { color: red; }
</style>

<div id="quoteModal" class="quote-modal-overlay">
    <div class="quote-modal-content">
        <div class="quote-modal-header">
            <h3>Request a Quote</h3>
            <span class="close-quote-modal" onclick="closeQuoteModal()">&times;</span>
        </div>
        <div class="quote-modal-body">
            
            <div class="quote-product-summary">
                <strong>Product:</strong> <span id="q-product-name">Product Name</span>
            </div>

            <!-- Step 1: Email Input -->
            <div id="step-1" class="quote-step active">
                <div class="q-form-group">
                    <label class="q-label">Enter Email Address</label>
                    <input type="email" id="q-email-input" class="q-input" placeholder="e.g. name@company.com" required>
                </div>
                <button type="button" class="q-btn" onclick="sendQuoteOTP()" id="btn-get-otp">Get OTP</button>
                <div id="msg-step-1" class="q-msg"></div>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="step-2" class="quote-step">
                <div class="q-form-group">
                    <label class="q-label">Enter OTP sent to <span id="opt-email-display" style="font-weight:bold;"></span></label>
                    <input type="text" id="q-otp-input" class="q-input" placeholder="Enter 6-digit OTP" required>
                </div>
                <button type="button" class="q-btn" onclick="verifyQuoteOTP()" id="btn-verify-otp">Verify OTP</button>
                <button type="button" class="q-btn q-btn-secondary" onclick="backToStep1()">Change Email</button>
                <div id="msg-step-2" class="q-msg"></div>
            </div>

            <!-- Step 3: Full Details Form -->
            <div id="step-3" class="quote-step">
                <form id="quote-details-form">
                    <!-- Hidden Fields -->
                    <input type="hidden" id="hq-product-id" name="product_id">
                    <input type="hidden" id="hq-product-name" name="product_name">
                    <input type="hidden" id="hq-email" name="email">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="q-form-group">
                            <label class="q-label">Quantity</label>
                            <input type="number" name="quantity" class="q-input" value="1" min="1" required>
                        </div>
                         <div class="q-form-group">
                            <label class="q-label">Mobile Number</label>
                            <input type="tel" name="mobile" class="q-input" placeholder="10-digit Mobile" required>
                        </div>
                    </div>

                    <div class="q-form-group">
                        <label class="q-label">Full Name</label>
                        <input type="text" name="name" class="q-input" required>
                    </div>

                    <div class="q-form-group">
                        <label class="q-label">Company Name (Optional)</label>
                        <input type="text" name="company_name" class="q-input">
                    </div>

                    <div class="q-form-group">
                        <label class="q-label">Pincode</label>
                        <input type="text" name="pincode" class="q-input" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="q-form-group">
                            <label class="q-label">City</label>
                            <input type="text" name="city" class="q-input" required>
                        </div>
                        <div class="q-form-group">
                            <label class="q-label">State</label>
                            <input type="text" name="state" class="q-input" required>
                        </div>
                    </div>
                    
                    <div class="q-form-group">
                        <label class="q-label">Address</label>
                        <textarea name="address" class="q-input" rows="2" required></textarea>
                    </div>

                    <button type="submit" class="q-btn" id="btn-submit-quote">Submit Enquiry</button>
                    <div id="msg-step-3" class="q-msg"></div>
                </form>
            </div>
            
             <!-- Step 4: Success Message -->
            <div id="step-4" class="quote-step" style="text-align: center; padding: 20px;">
                <i class="fa-solid fa-check-circle" style="font-size: 50px; color: green; margin-bottom: 20px;"></i>
                <h3>Thank You!</h3>
                <p>Your quote request has been submitted successfully.</p>
                <p>Our team will contact you shortly.</p>
                <button type="button" class="q-btn" onclick="closeQuoteModal()" style="margin-top: 20px;">Close</button>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// --- Modal Logic ---
function openQuoteModal(productId, productName) {
    document.getElementById('quoteModal').style.display = 'block';
    
    // Set Product Info
    document.getElementById('q-product-name').innerText = productName;
    document.getElementById('hq-product-id').value = productId;
    document.getElementById('hq-product-name').value = productName;
    
    // Reset Steps
    document.querySelectorAll('.quote-step').forEach(el => el.classList.remove('active'));
    document.getElementById('step-1').classList.add('active');
    
    // Clear Inputs (Optional: Keep email if user wants)
    $('#msg-step-1, #msg-step-2, #msg-step-3').text('');
}

function closeQuoteModal() {
    document.getElementById('quoteModal').style.display = 'none';
}

// Close on outside click
window.onclick = function(event) {
    var modal = document.getElementById('quoteModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// --- AJAX Logic ---

// 1. Send OTP
function sendQuoteOTP() {
    var email = $('#q-email-input').val();
    if(!email) {
        $('#msg-step-1').text('Please enter a valid email.').addClass('q-msg-error');
        return;
    }
    
    var btn = $('#btn-get-otp');
    btn.prop('disabled', true).text('Sending...');
    
    $.ajax({
        url: 'quote_handler.php',
        type: 'POST',
        data: { action: 'send_otp', email: email },
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).text('Get OTP');
            if(res.status === 'success') {
                $('#opt-email-display').text(email);
                $('#hq-email').value = email; // Might not work directly if hq-email is input, use .val()
                $('input[name="email"]').val(email); // Set hidden field
                
                // Switch to Step 2
                $('#step-1').removeClass('active');
                $('#step-2').addClass('active');
            } else {
                $('#msg-step-1').text(res.message).addClass('q-msg-error');
            }
        },
        error: function() {
              btn.prop('disabled', false).text('Get OTP');
              $('#msg-step-1').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
}

function backToStep1() {
    $('#step-2').removeClass('active');
    $('#step-1').addClass('active');
}

// 2. Verify OTP
function verifyQuoteOTP() {
     var email = $('#q-email-input').val();
     var otp = $('#q-otp-input').val();
     
     if(!otp) {
        $('#msg-step-2').text('Please enter OTP.').addClass('q-msg-error');
        return;
    }
    
    var btn = $('#btn-verify-otp');
    btn.prop('disabled', true).text('Verifying...');
    
    $.ajax({
        url: 'quote_handler.php',
        type: 'POST',
        data: { action: 'verify_otp', email: email, otp: otp },
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).text('Verify OTP');
            if(res.status === 'success') {
                 // Switch to Step 3
                $('#step-2').removeClass('active');
                $('#step-3').addClass('active');
            } else {
                $('#msg-step-2').text(res.message).addClass('q-msg-error');
            }
        },
         error: function() {
              btn.prop('disabled', false).text('Verify OTP');
              $('#msg-step-2').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
}

// 3. Submit Quote Form
$('#quote-details-form').on('submit', function(e) {
    e.preventDefault();
    
    var btn = $('#btn-submit-quote');
    btn.prop('disabled', true).text('Submitting...');
    
    var formData = $(this).serialize() + '&action=submit_quote';
    // Ensure email is included if it wasn't in the form explicitly as editable (it is hidden)
    
    $.ajax({
        url: 'quote_handler.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
         success: function(res) {
            btn.prop('disabled', false).text('Submit Enquiry');
            if(res.status === 'success') {
                 // Switch to Step 4 (Success)
                $('#step-3').removeClass('active');
                $('#step-4').addClass('active');
            } else {
                $('#msg-step-3').text(res.message).addClass('q-msg-error');
            }
        },
         error: function() {
              btn.prop('disabled', false).text('Submit Enquiry');
              $('#msg-step-3').text('Server error. Try again.').addClass('q-msg-error');
        }
    });
});

</script>
