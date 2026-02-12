<section class="faq-section">
    <div class="faq-container">
        <div class="section-header">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Find answers to common questions about our products, delivery, and services.</p>
        </div>

        <div class="faq-grid">
            <!-- FAQ 1 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How do I place an order?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>You can easily place an order by browsing our products, adding items to your cart, and proceeding to checkout. If you need assistance, you can use our "Assisted Order" feature or contact us via WhatsApp.</p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>What payment methods do you accept?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>We accept all major credit/debit cards, net banking, UPI, and popular wallets. All transactions are secured through our payment gateway partners.</p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Do you offer free delivery?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>Yes, we offer free delivery on selected products and orders above a certain value. Check the product page for specific delivery information.</p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How can I track my order?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>Once your order is shipped, you will receive a tracking link via SMS/Email. You can also track your order status from your account dashboard.</p>
                </div>
            </div>
            
            <!-- FAQ 5 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>What is your return policy?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>We have a customer-friendly return policy. If you receive a damaged or incorrect product, please contact us within 24-48 hours of delivery for a resolution.</p>
                </div>
            </div>
            
            <!-- FAQ 6 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How can I become a partner?</h3>
                    <span class="faq-toggle"><i class="fa-solid fa-chevron-down"></i></span>
                </button>
                <div class="faq-answer">
                    <p>You can join our partner program by registering through the "Become a Partner" page. Fill in your details, and our team will get in touch with you.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const faqItem = button.parentElement;
        const isActive = faqItem.classList.contains('active');
        
        // Close all other FAQs (optional, removed for better UX if user wants to compare)
        // document.querySelectorAll('.faq-item').forEach(item => {
        //     item.classList.remove('active');
        //     item.querySelector('.faq-answer').style.maxHeight = null;
        // });

        if (!isActive) {
            faqItem.classList.add('active');
            const answer = faqItem.querySelector('.faq-answer');
            answer.style.maxHeight = answer.scrollHeight + "px";
        } else {
            faqItem.classList.remove('active');
            const answer = faqItem.querySelector('.faq-answer');
            answer.style.maxHeight = null;
        }
    });
});
</script>
