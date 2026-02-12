<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/database/db_config.php';

echo "<h2>Debug Enquiries</h2>";

// Check Table Existence
$check_table = $conn->query("SHOW TABLES LIKE 'product_enquiries'");
if($check_table->num_rows > 0) {
    echo "Table 'product_enquiries' exists.<br>";
} else {
    echo "Table 'product_enquiries' DOES NOT EXIST.<br>";
    exit();
}

// Insert Dummy Data if empty
$count_val = $conn->query("SELECT COUNT(*) as total FROM product_enquiries")->fetch_assoc()['total'];
echo "Initial Count: $count_val<br>";

if ($count_val == 0) {
    echo "Inserting dummy data...<br>";
    // Get a valid product ID first
    $prod_res = $conn->query("SELECT id FROM products LIMIT 1");
    if($prod_res->num_rows > 0) {
        $p_id = $prod_res->fetch_assoc()['id'];
        $stmt = $conn->prepare("INSERT INTO product_enquiries (product_id, name, email, mobile, message) VALUES (?, ?, ?, ?, ?)");
        $name = "Test User";
        $email = "test@example.com";
        $mobile = "1234567890";
        $message = "This is a test enquiry.";
        $stmt->bind_param("issss", $p_id, $name, $email, $mobile, $message);
        if ($stmt->execute()) {
            echo "Dummy enquiry inserted successfully.<br>";
        } else {
            echo "Error inserting dummy enquiry: " . $stmt->error . "<br>";
        }
    } else {
        echo "No products found to link enquiry to.<br>";
    }
}

// Check Data Again
$sql = "SELECT * FROM product_enquiries LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<pre>";
    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
    echo "</pre>";
} else {
    echo "No rows returned from simple SELECT query.<br>";
}
?>
