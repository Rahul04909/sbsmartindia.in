<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if (!isset($url_prefix)) $url_prefix = '';
    header("Location: " . $url_prefix . "login.php");
    exit();
}
?>
