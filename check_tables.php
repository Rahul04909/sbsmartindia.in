<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database/db_config.php';

echo "Checking for tables...<br>";
$tables = ['blog_categories', 'blogs'];

foreach ($tables as $table) {
    echo "Table: $table - ";
    $res = $conn->query("SHOW TABLES LIKE '$table'");
    if ($res && $res->num_rows > 0) {
        echo "Found!<br>";
    } else {
        echo "NOT FOUND!<br>";
    }
}
?>
