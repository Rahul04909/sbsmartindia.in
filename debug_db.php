<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db_user = 'invest13_sbsindia';
$db_pass = 'Rahul@2026';
$db_name = 'invest13_sbsindia';

echo "Connecting to DB...\n";
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully.\n";

$sql = "SELECT COUNT(*) as total FROM product_enquiries";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total Enquiries: " . $row['total'] . "\n";
    
    if ($row['total'] == 0) {
        echo "Inserting dummy data...\n";
        $prod_res = $conn->query("SELECT id FROM products LIMIT 1");
        if ($prod_res->num_rows > 0) {
            $p_id = $prod_res->fetch_assoc()['id'];
            $name = "Test User";
            $email = "test@example.com";
            $mobile = "1234567890";
            $message = "Test enquiry message";
            
            $stmt = $conn->prepare("INSERT INTO product_enquiries (product_id, name, email, mobile, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $p_id, $name, $email, $mobile, $message);
            if($stmt->execute()) {
                echo "Dummy data inserted.\n";
            } else {
                echo "Insert failed: " . $stmt->error . "\n";
            }
        } else {
            echo "No products found.\n";
        }
    }
} else {
    echo "Query failed: " . $conn->error . "\n";
}
?>
