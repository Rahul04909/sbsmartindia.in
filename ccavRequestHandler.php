<?php
session_start();
include('includes/ccavenue_crypto.php');
require_once 'database/db_config.php';

// CCAvenue Configuration (REPLACE WITH YOUR KEYS)
$merchant_id = "254361";
$working_key = "20F8426E1EE4F3AE18D8DE38F727AEAC";
$access_code = "AVYV96HJ39CI86VYIC";

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $order_id = "ORD" . time() . rand(100,999);
    $total_items = [];
    $total_amount = 0;

    if (isset($_POST['from_cart']) && $_POST['from_cart'] == 1) {
        // Handle Cart Checkout
        $session_id = session_id();
        $sql = "SELECT c.quantity, p.id, p.title, p.sales_price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE ";

        if ($user_id) {
            $sql .= "c.user_id = $user_id";
        } else {
            $sql .= "c.session_id = '$session_id' AND c.user_id IS NULL";
        }

        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $total_items[] = [
                    'product_id' => $row['id'],
                    'product_name' => $row['title'],
                    'quantity' => $row['quantity'],
                    'price' => $row['sales_price']
                ];
                $total_amount += $row['sales_price'] * $row['quantity'];
            }
        } else {
             die("Cart is empty");
        }
    } 
    elseif (isset($_POST['product_id'])) {
        // Handle Single Product Checkout
        $product_id = (int)$_POST['product_id'];
        $quantity = 1;
        
        $prod_sql = "SELECT title, sales_price FROM products WHERE id = $product_id";
        $prod_res = $conn->query($prod_sql);
        if($prod_res->num_rows > 0) {
            $product = $prod_res->fetch_assoc();
            $total_amount = $product['sales_price'];
            $total_items[] = [
                'product_id' => $product_id,
                'product_name' => $product['title'],
                'quantity' => 1,
                'price' => $product['sales_price']
            ];
        } else {
            die("Invalid Product");
        }
    } else {
        die("Invalid Request");
    }
    
    // 2. Create Order in Database
    $sql = "INSERT INTO orders (order_id, user_id, customer_name, customer_email, customer_phone, customer_address, customer_city, customer_state, customer_zip, total_amount, payment_status) 
            VALUES ('$order_id', " . ($user_id ? $user_id : "NULL") . ", '$name', '$email', '$phone', '$address', '$city', '$state', '$zip', '$total_amount', 'Pending')";
            
    if($conn->query($sql)) {
        $db_id = $conn->insert_id;
        
        // Insert Order Items
        foreach($total_items as $item) {
            $p_id = $item['product_id'];
            $p_name = $conn->real_escape_string($item['product_name']);
            $qty = $item['quantity'];
            $price = $item['price'];
            
            $item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                         VALUES ('$db_id', '$p_id', '$p_name', '$qty', '$price')";
            $conn->query($item_sql);
        }
        
    // 3. Prepare CCAvenue Data
        $site_url = "http://sbsmart.in"; // Replace with your actual domain

        $merchant_data = "";
        $merchant_data .= "merchant_id=" . $merchant_id . "&";
        $merchant_data .= "order_id=" . $order_id . "&";
        $merchant_data .= "amount=" . $total_amount . "&";
        $merchant_data .= "currency=INR&";
        $merchant_data .= "redirect_url=" . $site_url . "/ccavResponseHandler.php&";
        $merchant_data .= "cancel_url=" . $site_url . "/ccavResponseHandler.php&";
        $merchant_data .= "language=EN&";
        
        // Billing Data
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
?>
