<?php
$conn = @new mysqli('localhost', 'root', '', 'sbsmarti_value');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$res = $conn->query("SHOW TABLES");
if ($res) {
    while ($row = $res->fetch_row()) {
        echo $row[0] . "\n";
    }
} else {
    echo "No tables or error: " . $conn->error . "\n";
}
$conn->close();
?>
