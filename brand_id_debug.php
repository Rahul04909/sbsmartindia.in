<?php
$conn = new mysqli('localhost', 'sbsmarti_sbsindia', 'Sbs@2026', 'sbsmarti_sbsindia');
if ($conn->connect_error) {
    file_put_contents('brand_debug.txt', "Connection failed: " . $conn->connect_error);
    exit;
}
$result = $conn->query("SELECT id, name FROM brands");
$out = "";
while($row = $result->fetch_assoc()) {
    $out .= "ID: " . $row['id'] . " - Name: " . $row['name'] . "\n";
}
file_put_contents('brand_debug.txt', $out);
$conn->close();
?>
