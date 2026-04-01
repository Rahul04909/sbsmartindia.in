<?php
require_once 'd:/wamp/www/sbsmartindia.in/database/db_config.php';
$res = $conn->query("DESCRIBE blogs");
$cols = [];
if($res){
    while($row = $res->fetch_assoc()){
        $cols[] = $row;
    }
}
file_put_contents('blogs_table_debug.json', json_encode($cols, JSON_PRETTY_PRINT));
print_r($cols);
?>
