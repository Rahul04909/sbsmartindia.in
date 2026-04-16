<?php
$host = 'localhost';
$db_user = 'mineib_i1_raghav_pandey';
$db_pass = 'Rd14072003@./';
$db_name = 'mineib_i1_sbsmart';

mysqli_report(MYSQLI_REPORT_OFF); // Prevent mysqli from throwing exceptions

$conn = @new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    if (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) {
        die("Critical Error: Database connection failed. Please contact administrator.<br>Details: " . $conn->connect_error);
    } else {
        // Fallback for non-admin pages
        error_log("Database connection failed: " . $conn->connect_error);
        die("Unable to connect to the database. Please try again later.");
    }
}
?>