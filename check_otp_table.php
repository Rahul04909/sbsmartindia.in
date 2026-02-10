<?php
require_once 'database/db_config.php';

$sql = "DESCRIBE email_otps";
$result = $conn->query($sql);

if ($result) {
    echo "Table 'email_otps' exists.";
} else {
    echo "Table 'email_otps' does NOT exist. Error: " . $conn->error;
}
$conn->close();
?>
