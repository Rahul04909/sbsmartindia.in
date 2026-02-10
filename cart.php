<?php
session_start();
require_once 'database/db_config.php';

// Cart Logic: Fetch items
$cart_items = [];
$subtotal = 0;

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();

$sql = "SELECT c.id as cart_id, c.quantity, p.id as product_id, p.title, p.sales_price, p.featured_image 
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
        $cart_items[] = $row;
        $subtotal += $row['sales_price'] * $row['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - SB Smart India</title>
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
        body { background-color: #f4f6f8; }
        .cart-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 2.5fr 1fr; gap: 30px; }
        .cart-section { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .cart-title { font-size: 24px; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        
        .cart-item { display: flex; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px; align-items: center; }
        .cart-img { width: 100px; height: 100px; object-fit: contain; border: 1px solid #eee; border-radius: 6px; }
        .cart-details { flex-grow: 1; }
        .cart-details h4 { margin: 0 0 5px 0; font-size: 16px; color: #333; }
        .cart-price { font-weight: 700; font-size: 18px; color: #333; }
        
        .qty-controls { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .qty-btn { width: 30px; height: 30px; border: 1px solid #ddd; background: #fff; cursor: pointer; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .qty-btn:hover { background: #f0f0f0; }
        .qty-input { width: 40px; text-align: center; border: 1px solid #ddd; padding: 5px; border-radius: 4px; }
        
        .remove-btn { color: #dc3545; font-size: 13px; cursor: pointer; text-decoration: underline; margin-left: 15px; }
        
        /* Summary */
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; color: #555; }
        .summary-total { border-top: 1px solid #eee; padding-top: 20px; margin-top: 10px; font-weight: 700; font-size: 20px; color: #333; }
        .checkout-btn { width: 100%; padding: 15px; background-color: #ff9900; color: white; border: none; font-weight: 700; font-size: 16px; border-radius: 6px; cursor: pointer; transition: background 0.2s; margin-top: 20px; }
        .checkout-btn:hover { background-color: #e68a00; }
        
        .empty-cart { text-align: center; padding: 60px; }
        .empty-cart i { font-size: 48px; color: #ddd; margin-bottom: 20px; }
        
        @media (max-width: 768px) {
            .cart-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php require_once 'includes/header.php'; ?>

<div class="cart-container">
    <div class="cart-section">
        <h2 class="cart-title">Shopping Cart (<?php echo count($cart_items); ?> Items)</h2>
        
        <?php if (count($cart_items) > 0): ?>
            <?php foreach($cart_items as $item): ?>
                <div class="cart-item" id="item-<?php echo $item['cart_id']; ?>">
                    <img src="<?php echo !empty($item['featured_image']) ? htmlspecialchars($item['featured_image']) : 'assets/images/no-image.png'; ?>" class="cart-img">
                    <div class="cart-details">
                        <h4><a href="product-details.php?id=<?php echo $item['product_id']; ?>"><?php echo htmlspecialchars($item['title']); ?></a></h4>
                        <div class="cart-price">₹<?php echo number_format($item['sales_price']); ?></div>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty(<?php echo $item['cart_id']; ?>, -1)">-</button>
                            <input type="text" value="<?php echo $item['quantity']; ?>" class="qty-input" readonly id="qty-<?php echo $item['cart_id']; ?>">
                            <button class="qty-btn" onclick="updateQty(<?php echo $item['cart_id']; ?>, 1)">+</button>
                            <span class="remove-btn" onclick="removeItem(<?php echo $item['cart_id']; ?>)">Remove</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Your cart is empty</h3>
                <a href="index.php" class="btn-primary" style="display:inline-block; margin-top:20px; text-decoration:underline;">Shop Now</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="cart-section" style="height: fit-content;">
        <?php if (count($cart_items) > 0): ?>
            <h2 class="cart-title">Order Summary</h2>
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">₹<?php echo number_format($subtotal); ?></span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span style="color:green;">Free</span>
            </div>
            <div class="summary-row summary-total">
                <span>Total</span>
                <span id="grandtotal">₹<?php echo number_format($subtotal); ?></span>
            </div>
            <a href="checkout.php?from_cart=1" class="checkout-btn" style="display:block; text-align:center; text-decoration:none;">Proceed to Buy</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
function updateQty(cartId, change) {
    var input = document.getElementById('qty-' + cartId);
    var newQty = parseInt(input.value) + change;
    
    if (newQty < 1) return;

    $.ajax({
        url: 'cart_handler.php',
        type: 'POST',
        data: { action: 'update', cart_id: cartId, quantity: newQty },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                location.reload(); // Reload to update totals properly for now
            } else {
                alert(response.message);
            }
        }
    });
}

function removeItem(cartId) {
    if(!confirm('Are you sure?')) return;
    
    $.ajax({
        url: 'cart_handler.php',
        type: 'POST',
        data: { action: 'remove', cart_id: cartId },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                location.reload();
            }
        }
    });
}
</script>

</body>
</html>
