<?php
include('includes/ccavenue_crypto.php');
require_once 'database/db_config.php';

// CCAvenue Configuration (REPLACE WITH YOUR KEYS)
$working_key = "20F8426E1EE4F3AE18D8DE38F727AEAC"; // Shared by CCAVENUES

$encResponse = $_POST["encResp"];			//This is the response sent by the CCAvenue Server
$rcvdString = decrypt($encResponse, $working_key);		//Crypto Decryption used as per the specified working key.
$order_status = "";
$decryptValues = explode('&', $rcvdString);
$dataSize = sizeof($decryptValues);

$response_data = [];
for($i = 0; $i < $dataSize; $i++) 
{
	$information = explode('=', $decryptValues[$i]);
    $response_data[$information[0]] = $information[1];
}

$order_status = $response_data['order_status'];
$order_id = $response_data['order_id'];
$tracking_id = $response_data['tracking_id'];
$bank_ref_no = $response_data['bank_ref_no'];

// Update Database based on status
if($order_status === "Success")
{
    $status_msg = "Transaction Successful";
    $db_status = "Success";
}
else if($order_status === "Aborted")
{
    $status_msg = "Transaction Aborted";
    $db_status = "Keep Pending"; // Or Failed
}
else if($order_status === "Failure")
{
    $status_msg = "Transaction Declined";
    $db_status = "Failed";
}
else
{
    $status_msg = "Security Error. Illegal access detected";
    $db_status = "Failed"; 
}


// Update Order in DB
if(isset($order_id)) {
    $sql = "UPDATE orders SET payment_status='$db_status', tracking_id='$tracking_id', bank_ref_no='$bank_ref_no' WHERE order_id='$order_id'";
    $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
     <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
</head>
<body>
    <div class="container" style="padding: 100px; text-align: center;">
        <?php if($order_status === "Success"): ?>
            <h1 style="color: green;">Thank You!</h1>
            <p style="font-size: 18px; color:#555;"><?php echo $status_msg; ?></p>
            <p>Your Order ID is: <strong><?php echo $order_id; ?></strong></p>
            <a href="index.php" class="btn-primary" style="margin-top: 20px; display: inline-block;">Continue Shopping</a>
        <?php else: ?>
            <h1 style="color: red;">Transaction Failed</h1>
            <p style="font-size: 18px; color:#555;"><?php echo $status_msg; ?></p>
            <a href="index.php" class="btn-primary" style="margin-top: 20px; display: inline-block;">Try Again</a>
        <?php endif; ?>
    </div>
</body>
</html>
