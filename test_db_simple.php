<?php
$conn = mysqli_init();
if (!$conn) {
    die("mysqli_init failed");
}
echo "mysqli initialized successfully!<br>";
$host = 'localhost';
$db_user = 'sbsmarti_sbsindia';
$db_pass = 'Sbs@2026';
$db_name = 'sbsmarti_sbsindia';

if (@mysqli_real_connect($conn, $host, $db_user, $db_pass, $db_name)) {
    echo "Connected successfully to mysqli!<br>";
} else {
    echo "Connection failed: (" . mysqli_connect_errno() . ") " . mysqli_connect_error() . "<br>";
}
?>
