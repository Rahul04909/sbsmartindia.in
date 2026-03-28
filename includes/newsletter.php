<section class="newsletter-section">
    <div class="newsletter-container">
        <div class="newsletter-header">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for exclusive offers, new product announcements, and technical articles directly to your inbox.</p>
        </div>
        
        <form class="newsletter-form" id="newsletterForm">
            <div class="input-group">
                <input type="text" name="name" class="newsletter-input" placeholder="Your Name" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required>
            </div>
            <div class="input-group">
                <input type="tel" name="mobile" class="newsletter-input" placeholder="Mobile Number" required pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number">
            </div>
            <button type="submit" class="newsletter-btn" id="newsletterSubmit">Subscribe</button>
        </form>
        <div id="newsletterMessage" style="margin-top: 15px; font-weight: 500; display: none;"></div>
    </div>
</section>

<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('newsletterSubmit');
    const messageDiv = document.getElementById('newsletterMessage');
    const formData = new FormData(form);
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
    
    fetch('ajax/newsletter_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.style.display = 'block';
        messageDiv.textContent = data.message;
        
        if (data.status === 'success') {
            messageDiv.style.color = '#28a745'; // Green
            form.reset();
        } else {
            messageDiv.style.color = '#dc3545'; // Red
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.style.display = 'block';
        messageDiv.style.color = '#dc3545';
        messageDiv.textContent = 'An error occurred. Please try again.';
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Subscribe';
    });
});
</script>
