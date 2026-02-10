<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SB Smart India</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/hero.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="asstes/css/categories.css">
    <link rel="stylesheet" href="asstes/css/stats.css">
    <link rel="stylesheet" href="asstes/css/services.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/latest-products.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/hero.php'; ?>
    <?php include 'includes/brand-menu.php'; ?>
    <?php include 'includes/categories.php'; ?>
    
    <?php include 'includes/latest-products.php'; ?>
    
    <?php include 'includes/stats.php'; ?>
    <?php include 'includes/services.php'; ?>
    <?php include 'includes/footer.php'; ?>

</body>
</html>
