<section class="newsletter-section">
    <div class="newsletter-container">
        <div class="newsletter-header">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for exclusive offers, new product announcements, and technical articles directly to your inbox.</p>
        </div>
        
        <form class="newsletter-form" action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
            <div class="input-group">
                <input type="text" name="name" class="newsletter-input" placeholder="Your Name" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required>
            </div>
            <div class="input-group">
                <input type="tel" name="mobile" class="newsletter-input" placeholder="Mobile Number" required pattern="[0-9]{10}" title="Please enter a valid 10-digit mobile number">
            </div>
            <button type="submit" class="newsletter-btn">Subscribe</button>
        </form>
    </div>
</section>
