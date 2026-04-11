<?php
session_start();
$url_prefix = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon | SB Smart India</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    
    <style>
        .coming-soon-section {
            padding: 100px 20px;
            background: linear-gradient(135deg, #f6f9fc 0%, #ffffff 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .cs-container {
            max-width: 800px;
            width: 100%;
        }

        .cs-icon {
            font-size: 80px;
            color: #004aad;
            margin-bottom: 30px;
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .cs-content h1 {
            font-size: 48px;
            font-weight: 800;
            color: #004aad;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .cs-content h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .cs-content p {
            font-size: 18px;
            color: #666;
            line-height: 1.8;
            margin-bottom: 40px;
            padding: 0 40px;
        }

        .cs-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cs {
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-cs-primary {
            background-color: #004aad;
            color: white;
            box-shadow: 0 10px 20px rgba(0, 74, 173, 0.2);
        }

        .btn-cs-primary:hover {
            background-color: #003380;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0, 74, 173, 0.3);
        }

        .btn-cs-outline {
            background-color: white;
            color: #004aad;
            border: 2px solid #004aad;
        }

        .btn-cs-outline:hover {
            background-color: #f0f7ff;
            transform: translateY(-3px);
        }

        .brand-preview {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #e0e0e0;
        }

        .preview-text {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #999;
            font-weight: 700;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .cs-content h1 { font-size: 32px; }
            .cs-content h2 { font-size: 20px; }
            .cs-content p { font-size: 16px; padding: 0 10px; }
            .coming-soon-section { padding: 60px 20px; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="coming-soon-section">
        <div class="cs-container">
            <div class="cs-icon">
                <i class="fa-solid fa-rocket"></i>
            </div>
            <div class="cs-content">
                <h1>Exciting Things On The Way!</h1>
                <h2>Our "Others" Brand Portfolio is Growing</h2>
                <p>We are currently establishing partnerships with many more premium industrial automation & electrical brands. We'll be updating this section with new products very soon!</p>
                
                <div class="cs-actions">
                    <a href="products.php" class="btn-cs btn-cs-primary">
                        <i class="fa-solid fa-box-open"></i> Explore Active Shop
                    </a>
                    <a href="pages/contact-us.php" class="btn-cs btn-cs-outline">
                        <i class="fa-solid fa-envelope"></i> Contact For Enquiries
                    </a>
                </div>
            </div>

            <div class="brand-preview">
                <p class="preview-text">Stay Tuned for Updates</p>
                <div style="font-size: 24px; color: #ced4da; display: flex; gap: 40px; justify-content: center; opacity: 0.6;">
                    <i class="fa-solid fa-industry"></i>
                    <i class="fa-solid fa-bolt"></i>
                    <i class="fa-solid fa-microchip"></i>
                    <i class="fa-solid fa-gear"></i>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
