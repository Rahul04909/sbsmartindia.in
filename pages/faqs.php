<?php
session_start();
// Adjust path to root based on location
$url_prefix = '../';
require_once '../database/db_config.php';
$page_title = "Frequently Asked Questions - S.B. Syscon Pvt. Ltd.";
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
    <link rel="stylesheet" href="../assets/css/faqs.css">
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs" style="background: #f8f9fa; padding: 15px 0; border-bottom: 1px solid #eee;">
    <div class="container">
        <a href="../index.php" style="color: #666;">Home</a> <span style="color: #999;">&gt;</span> <span style="color: #333; font-weight: 500;">FAQs</span>
    </div>
</div>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container faq-container">
        <div class="section-header">
            <h1 class="section-title">Frequently Asked Questions</h1>
            <p class="section-subtitle">Find answers to common questions about our products, orders, and services.</p>
        </div>

        <div class="faq-grid">
            <!-- FAQ 1 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How can I place an order?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>You can place an order directly through our website by adding products to your cart and proceeding to checkout. Alternatively, for bulk orders or specific industrial requirements, you can use our "Request a Quote" feature or contact our sales team directly.</p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Do you offer bulk discounts?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, we offer competitive pricing for bulk orders. Please submit a quote request with your specific requirements and quantities, and our team will provide you with the best possible rates.</p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>What payment methods do you accept?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>We accept a variety of payment methods including credit/debit cards, net banking, UPI, and bank transfers (NEFT/RTGS) for business transactions.</p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How can I track my order?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Once your order is dispatched, you will receive a tracking number via email and SMS. You can also track the status of your order by logging into your account dashboard under the "My Orders" section.</p>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>What is your return and refund policy?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>We have a comprehensive return policy. If you receive a defective or incorrect product, please report it within 48 hours of delivery. Refunds or replacements are processed after verification. Please refer to our Refund & Cancellation Policy page for detailed terms.</p>
                </div>
            </div>

            <!-- FAQ 6 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Do you ship internationally?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, S.B. Syscon Pvt. Ltd. exports to over 20 countries across Asia, the Middle East, and Africa. We have robust logistics partnerships to ensure timely and secure international deliveries.</p>
                </div>
            </div>

            <!-- FAQ 7 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Can I get a GST invoice for my business purchase?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Absolutely. All our potential business customers can enter their GST number during checkout or registration to receive a compliant tax invoice for input tax credit purposes.</p>
                </div>
            </div>

            <!-- FAQ 8 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>How can I contact customer support?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Our customer support team is available via phone at (+91) 129 4150 555 or email at marcom.sbsyscon@gmail.com. You can also use the contact form on our "Contact Us" page.</p>
                </div>
            </div>

            <!-- FAQ 9 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Are your products genuine and covered by warranty?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, we are authorized channel partners for leading brands like Siemens, LAPP, and Schneider. All products sold by us are 100% genuine and come with the standard manufacturer warranty.</p>
                </div>
            </div>

            <!-- FAQ 10 -->
            <div class="faq-item">
                <button class="faq-question">
                    <h3>Do you provide technical support for product selection?</h3>
                    <i class="fa-solid fa-chevron-down faq-toggle"></i>
                </button>
                <div class="faq-answer">
                    <p>Yes, our team of experienced engineers can assist you in selecting the right products for your specific industrial applications. Please feel free to reach out to us with your technical requirements.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Close other open FAQs
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    }
                });

                // Toggle current FAQ
                item.classList.toggle('active');
                
                const answer = item.querySelector('.faq-answer');
                if (item.classList.contains('active')) {
                    answer.style.maxHeight = answer.scrollHeight + "px";
                } else {
                    answer.style.maxHeight = null;
                }
            });
        });
    });
</script>

</body>
</html>
