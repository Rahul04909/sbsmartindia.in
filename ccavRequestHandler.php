<?php
include('includes/ccavenue_crypto.php');
require_once 'database/db_config.php';

// CCAvenue Configuration (REPLACE WITH YOUR KEYS)
$merchant_id = "254361";
$working_key = "20F8426E1EE4F3AE18D8DE38F727AEAC";
$access_code = "AVYV96HJ39CI86VYIC";

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
        $site_url = "http://sbsmart.in"; // Replace with your actual domain

        $merchant_data = "";
        $merchant_data .= "merchant_id=" . $merchant_id . "&";
        $merchant_data .= "order_id=" . $order_id . "&";
        $merchant_data .= "amount=" . $amount . "&";
        $merchant_data .= "currency=INR&";
        $merchant_data .= "redirect_url=" . $site_url . "/ccavResponseHandler.php&";
        $merchant_data .= "cancel_url=" . $site_url . "/ccavResponseHandler.php&";
        $merchant_data .= "language=EN&";
        
        // Billing Data (Urlencode to handle special chars)
        $merchant_data .= "billing_name=" . urlencode($name) . "&";
        $merchant_data .= "billing_address=" . urlencode($address) . "&";
        $merchant_data .= "billing_city=" . urlencode($city) . "&";
        $merchant_data .= "billing_state=" . urlencode($state) . "&";
        $merchant_data .= "billing_zip=" . urlencode($zip) . "&";
        $merchant_data .= "billing_country=India&";
        $merchant_data .= "billing_tel=" . urlencode($phone) . "&";
        $merchant_data .= "billing_email=" . urlencode($email);
        
        // Encrypt Data
        $encrypted_data = encrypt($merchant_data, $working_key);
        
        // Production URL
        $production_url = "https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
        ?>
        <form method="post" name="redirect" action="<?php echo $production_url; ?>"> 
            <input type="hidden" name="encRequest" value="<?php echo $encrypted_data; ?>">
            <input type="hidden" name="access_code" value="<?php echo $access_code; ?>">
        </form>
        <script language='javascript'>document.redirect.submit();</script>
        <?php
        exit();
        
    } else {
        echo "Error Creating Order: " . $conn->error;
    }
}
?>
