<?php
include('includes/ccavenue_crypto.php');
require_once 'database/db_config.php';

// CCAvenue Configuration (REPLACE WITH YOUR KEYS)
$merchant_id = "YOUR_MERCHANT_ID";
$working_key = "YOUR_WORKING_KEY";
$access_code = "YOUR_ACCESS_CODE";

if(isset($_POST['checkout_submit'])) {
    
    // 1. Collect User Data
    $name = $conn->real_escape_string($_POST['billing_name']);
    $email = $conn->real_escape_string($_POST['billing_email']);
    $phone = $conn->real_escape_string($_POST['billing_tel']);
    $address = $conn->real_escape_string($_POST['billing_address']);
    $city = $conn->real_escape_string($_POST['billing_city']);
    $state = $conn->real_escape_string($_POST['billing_state']);
    $zip = $conn->real_escape_string($_POST['billing_zip']);
    
    $product_id = (int)$_POST['product_id'];
    $quantity = 1; // Default to 1 for now
    
    // Fetch Product Price
    $prod_sql = "SELECT title, sales_price FROM products WHERE id = $product_id";
    $prod_res = $conn->query($prod_sql);
    if($prod_res->num_rows > 0) {
        $product = $prod_res->fetch_assoc();
        $amount = $product['sales_price'];
        $product_name = $product['title'];
    } else {
        die("Invalid Product");
    }
    
    // 2. Create Order in Database (Status: Pending)
    $order_id = "ORD" . time() . rand(100,999);
    
    $sql = "INSERT INTO orders (order_id, customer_name, customer_email, customer_phone, customer_address, customer_city, customer_state, customer_zip, total_amount, payment_status) 
            VALUES ('$order_id', '$name', '$email', '$phone', '$address', '$city', '$state', '$zip', '$amount', 'Pending')";
            
    if($conn->query($sql)) {
        $db_id = $conn->insert_id;
        
        // Insert Order Item
        $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                     VALUES ('$db_id', '$product_id', '$product_name', '$quantity', '$amount')";
        $conn->query($item_sql);
        
        // 3. Prepare CCAvenue Data
        $merchant_data = "";
        
        // Mandatory Parameters
        $merchant_data .= "merchant_id=" . $merchant_id . "&";
        $merchant_data .= "order_id=" . $order_id . "&";
        $merchant_data .= "amount=" . $amount . "&";
        $merchant_data .= "currency=INR&";
        $merchant_data .= "redirect_url=http://localhost/sbsmartindia.in/ccavResponseHandler.php&";
        $merchant_data .= "cancel_url=http://localhost/sbsmartindia.in/ccavResponseHandler.php&";
        $merchant_data .= "language=EN&";
        
        // Billing Data
        $merchant_data .= "billing_name=" . $name . "&";
        $merchant_data .= "billing_address=" . $address . "&";
        $merchant_data .= "billing_city=" . $city . "&";
        $merchant_data .= "billing_state=" . $state . "&";
        $merchant_data .= "billing_zip=" . $zip . "&";
        $merchant_data .= "billing_country=India&";
        $merchant_data .= "billing_tel=" . $phone . "&";
        $merchant_data .= "billing_email=" . $email . "&";
        
        // Encrypt Data
        $encrypted_data = encrypt($merchant_data, $working_key);
        
        ?>
        <form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
            <?php
            echo "<input type=hidden name=encRequest value=$encrypted_data>";
            echo "<input type=hidden name=access_code value=$access_code>";
            ?>
        </form>
        <script language='javascript'>document.redirect.submit();</script>
        <?php
        exit();
        
    } else {
        echo "Error Creating Order: " . $conn->error;
    }
}
?>
