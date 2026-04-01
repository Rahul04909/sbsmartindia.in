<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL) {
        echo "<pre>";
        echo "A fatal error occurred:<br>";
        print_r($error);
        echo "</pre>";
    }
});

require_once 'database/db_config.php';

echo "Database connection successful!<br>";
$res = $conn->query("DESCRIBE blogs");
if (!$res) {
    echo "Query failed: " . $conn->error . "<br>";
} else {
    echo "Table 'blogs' exists and has columns.<br>";
}
?>
