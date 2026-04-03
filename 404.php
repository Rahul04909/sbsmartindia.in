<?php
session_start();
$url_prefix = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | SB Smart India</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    <style>
        .error-404-section {
            padding: 80px 20px;
            text-align: center;
            background: #fff;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-content h1 {
            font-size: 150px;
            font-weight: 900;
            color: #004aad;
            margin: 0;
            line-height: 1;
            text-shadow: 4px 4px 0px rgba(0, 74, 173, 0.1);
        }
        .error-content h2 {
            font-size: 36px;
            margin-bottom: 15px;
            color: #1a1a1a;
            font-weight: 700;
        }
        .error-content p {
            color: #555;
            font-size: 1.1rem;
            margin-bottom: 35px;
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        .btn-home {
            background-color: #004aad;
            color: white;
            padding: 14px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 74, 173, 0.3);
        }
        .btn-home:hover {
            background-color: #003380;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 74, 173, 0.4);
        }
        @media (max-width: 768px) {
            .error-content h1 { font-size: 100px; }
            .error-content h2 { font-size: 28px; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="error-404-section">
        <div class="error-content">
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>We're sorry, the page you've requested could not be found. Please return to our homepage or search for another item.</p>
            <a href="index.php" class="btn-home"><i class="fas fa-home" style="margin-right: 8px;"></i> Back to Homepage</a>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
