<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once 'database/db_config.php';
    $host = 'localhost';
    $db_user = 'sbsmarti_sbsindia';
    $db_pass = 'Sbs@2026';
    $db_name = 'sbsmarti_sbsindia';

    echo "Attempting to connect to database...<br>";
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully!<br>";

    $res = $conn->query("DESCRIBE blogs");
    if (!$res) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $cols = [];
    while($row = $res->fetch_assoc()){
        $cols[] = $row;
    }
    
    echo "<pre>";
    print_r($cols);
    echo "</pre>";

} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "Caught error: " . $e->getMessage() . "<br>";
}
?>
