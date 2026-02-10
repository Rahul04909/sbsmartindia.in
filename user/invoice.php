<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../database/db_config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    die("Unauthorized Access");
}

$user_id = $_SESSION['user_id'];
$order_id = $conn->real_escape_string($_GET['order_id']);

// Fetch Order
$sql = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Order not found");
}

$order = $result->fetch_assoc();

// Fetch Order Items
$item_sql = "SELECT * FROM order_items WHERE order_id = " . $order['id'];
$item_res = $conn->query($item_sql);
$items = [];
while($row = $item_res->fetch_assoc()) {
    $items[] = $row;
}

// Company Details (Ideally fetch from settings, hardcoded for now based on header)
$company_name = "SB Smart India";
$company_address = "Sector 29, Faridabad, Haryana, India";
$company_phone = "(+91) 129 4150 555";
$company_email = "marcom.sbsyscon@gmail.com";
$company_logo = '../asstes/logo/logo.png'; // Path relative to this script for mPDF? No, mPDF needs absolute or relative to script execution.
// mPDF often works best with absolute paths or base64. Let's try relative first.

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: sans-serif; font-size: 14px; color: #333; }
    .header-table { width: 100%; border-bottom: 2px solid #004aad; padding-bottom: 20px; margin-bottom: 20px; }
    .company-info { text-align: right; }
    .company-name { font-size: 24px; font-weight: bold; color: #004aad; margin-bottom: 5px; }
    .invoice-title { font-size: 28px; font-weight: bold; color: #333; text-transform: uppercase; letter-spacing: 2px; }
    
    .details-table { width: 100%; margin-bottom: 30px; }
    .bill-to { width: 50%; vertical-align: top; }
    .invoice-meta { width: 50%; text-align: right; vertical-align: top; }
    
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    .items-table th { background-color: #004aad; color: #fff; padding: 10px; text-align: left; font-weight: bold; }
    .items-table td { border-bottom: 1px solid #ddd; padding: 10px; }
    .text-right { text-align: right; }
    .total-row td { font-weight: bold; font-size: 16px; background-color: #f8f9fa; border-top: 2px solid #004aad; }
    
    .footer { text-align: center; font-size: 12px; color: #777; margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; }
</style>
</head>
<body>

<table class="header-table">
    <tr>
        <td width="30%">
            <img src="'.$company_logo.'" width="150" />
        </td>
        <td width="70%" class="company-info">
            <div class="invoice-title">INVOICE</div>
            <div class="company-name">'.$company_name.'</div>
            <div>'.$company_address.'</div>
            <div>Phone: '.$company_phone.'</div>
            <div>Email: '.$company_email.'</div>
        </td>
    </tr>
</table>

<table class="details-table">
    <tr>
        <td class="bill-to">
            <strong>BILL TO:</strong><br><br>
            <strong>Name:</strong> '.htmlspecialchars($order['customer_name']).'<br>
            <strong>Address:</strong> '.htmlspecialchars($order['customer_address']).'<br>
            '.htmlspecialchars($order['customer_city']).', '.htmlspecialchars($order['customer_state']).' - '.htmlspecialchars($order['customer_zip']).'<br>
            <strong>Mobile:</strong> '.htmlspecialchars($order['customer_phone']).'<br>
            <strong>Email:</strong> '.htmlspecialchars($order['customer_email']).'
        </td>
        <td class="invoice-meta">
            <strong>Order ID:</strong> #'.$order['order_id'].'<br>
            <strong>Date:</strong> '.date('d M Y', strtotime($order['created_at'])).'<br>
            <strong>Payment Status:</strong> <span style="color:'.($order['payment_status']=='Success'?'green':'red').'">'.$order['payment_status'].'</span>
        </td>
    </tr>
</table>

<table class="items-table">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="50%">Product</th>
            <th width="15%" class="text-right">Price</th>
            <th width="10%" class="text-right">Qty</th>
            <th width="20%" class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>';
    
    $i = 1;
    $total_qty = 0;
    foreach($items as $item) {
        $row_total = $item['price'] * $item['quantity'];
        $html .= '
        <tr>
            <td>'.$i++.'</td>
            <td>'.htmlspecialchars($item['product_name']).'</td>
            <td class="text-right">'.number_format($item['price'], 2).'</td>
            <td class="text-right">'.$item['quantity'].'</td>
            <td class="text-right">'.number_format($row_total, 2).'</td>
        </tr>';
        $total_qty += $item['quantity'];
    }

$html .= '
    <tr class="total-row">
        <td colspan="3" class="text-right">Grand Total</td>
        <td class="text-right">'.$total_qty.'</td>
        <td class="text-right">Rs. '.number_format($order['total_amount'], 2).'</td>
    </tr>
    </tbody>
</table>

<div class="footer">
    <p>Thank you for your business!</p>
    <p>This is a computer-generated invoice and does not require a signature.</p>
</div>

</body>
</html>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Invoice-'.$order_id.'.pdf', 'I'); // I = Inline browser view, D = Download
?>
