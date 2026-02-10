<?php
session_start();
require_once 'database/db_config.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$from_cart = isset($_GET['from_cart']) ? 1 : 0;

$items_to_checkout = [];
$total_amount = 0;

if ($from_cart) {
    // Fetch Cart Items
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_id = session_id();

    $sql = "SELECT c.quantity, p.id, p.title, p.sales_price, p.featured_image 
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
            $items_to_checkout[] = $row;
            $total_amount += $row['sales_price'] * $row['quantity'];
        }
    } else {
        // Cart empty
        header("Location: cart.php");
        exit();
    }
} elseif ($product_id > 0) {
    // Buy Now Mode (Single Item)
    $sql = "SELECT * FROM products WHERE id = $product_id AND status = 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $items_to_checkout[] = [
            'id' => $product['id'],
            'title' => $product['title'],
            'sales_price' => $product['sales_price'],
            'featured_image' => $product['featured_image'],
            'quantity' => 1
        ];
        $total_amount = $product['sales_price'];
    } else {
         header("Location: products.php");
         exit();
    }
} else {
    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SB Smart India</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/brand-menu.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f6f8fb; }
        .checkout-container { max-width: 1100px; margin: 40px auto; display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; padding: 0 20px; }
        .checkout-card { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        h2 { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: #555; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; box-sizing: border-box; }
        .order-summary-item { display: flex; gap: 15px; padding-bottom: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; }
        .summary-img { width: 60px; height: 60px; object-fit: contain; border: 1px solid #eee; border-radius: 6px; }
        .summary-details h4 { font-size: 14px; margin: 0 0 5px 0; color: #333; }
        .summary-details p { margin: 0; color: #666; font-size: 13px; }
        .price-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; color: #555; }
        .total-row { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-weight: 700; font-size: 18px; color: #333; }
        .btn-checkout { width: 100%; padding: 15px; background-color: #004aad; color: #fff; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-top: 20px; }
        .btn-checkout:hover { background-color: #003380; }
        .secure-badge { text-align: center; margin-top: 15px; color: #28a745; font-size: 13px; font-weight: 500; }
        
        @media (max-width: 768px) {
            .checkout-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<div class="checkout-container">
    <!-- Left: Billing Details -->
    <div class="checkout-card">
        <h2>Billing Details</h2>
        <form action="ccavRequestHandler.php" method="POST">
            <?php if ($from_cart): ?>
                <input type="hidden" name="from_cart" value="1">
            <?php else: ?>
                <input type="hidden" name="product_id" value="<?php echo $items_to_checkout[0]['id']; ?>">
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="billing_name" class="form-control" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="billing_tel" class="form-control" required placeholder="9876543210">
                </div>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="billing_email" class="form-control" required placeholder="john@example.com">
            </div>
            
            <div class="form-group">
                <label>Address</label>
                <textarea name="billing_address" class="form-control" rows="3" required placeholder="Street address, Flat, Suite..."></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="billing_city" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="billing_state" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" name="billing_zip" class="form-control" required>
                </div>
            </div>
            
            <input type="hidden" name="checkout_submit" value="1">
            <button type="submit" class="btn-checkout">Proceed to Payment <i class="fa-solid fa-lock"></i></button>
            <div class="secure-badge"><i class="fa-solid fa-shield-halved"></i> 100% Secure Transaction</div>
        </form>
    </div>
    
    <!-- Right: Order Summary -->
    <div class="checkout-card" style="height: fit-content;">
        <h2>Order Summary</h2>
        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
            <?php foreach($items_to_checkout as $item): ?>
                <div class="order-summary-item">
                    <img src="<?php echo !empty($item['featured_image']) ? htmlspecialchars($item['featured_image']) : 'assets/images/no-image.png'; ?>" class="summary-img">
                    <div class="summary-details">
                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                        <p>Qty: <?php echo $item['quantity']; ?> x ₹<?php echo number_format($item['sales_price']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="price-row">
            <span>Subtotal</span>
            <span>₹<?php echo number_format($total_amount); ?></span>
        </div>
        <div class="price-row">
            <span>Shipping</span>
            <span style="color: #28a745;">Free</span>
        </div>
        
        <div class="total-row">
            <span>Total Amount</span>
            <span>₹<?php echo number_format($total_amount); ?></span>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

</body>
</html>
