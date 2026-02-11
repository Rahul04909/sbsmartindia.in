<?php
require_once 'database/db_config.php';
echo "Connected successfully to " . $db_name;
$conn->close();
?>
