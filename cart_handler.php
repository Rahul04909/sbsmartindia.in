<?php
session_start();
require_once 'database/db_config.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Identity logic: User ID if logged in, otherwise Session ID
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_id = session_id();

    if ($action === 'add') {
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($product_id > 0) {
            // Check if item exists
            $check_sql = "SELECT id, quantity FROM cart WHERE product_id = $product_id AND ";
            if ($user_id) {
                $check_sql .= "user_id = $user_id";
            } else {
                $check_sql .= "session_id = '$session_id' AND user_id IS NULL";
            }

            $check_res = $conn->query($check_sql);

            if ($check_res && $check_res->num_rows > 0) {
                // Update quantity
                $row = $check_res->fetch_assoc();
                $new_qty = $row['quantity'] + $quantity;
                $update_sql = "UPDATE cart SET quantity = $new_qty WHERE id = " . $row['id'];
                if ($conn->query($update_sql)) {
                    $response = ['status' => 'success', 'message' => 'Cart updated'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Database error'];
                }
            } else {
                // Insert new
                $insert_sql = "INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (";
                $insert_sql .= $user_id ? "$user_id, NULL, $product_id, $quantity)" : "NULL, '$session_id', $product_id, $quantity)";
                
                if ($conn->query($insert_sql)) {
                    $response = ['status' => 'success', 'message' => 'Added to cart'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Database error: ' . $conn->error];
                }
            }
        }
    } 
    elseif ($action === 'update') {
        $cart_id = intval($_POST['cart_id']);
        $quantity = intval($_POST['quantity']);
        
        if ($quantity > 0) {
            $sql = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id";
             // Ensure ownership
            if ($user_id) {
                $sql .= " AND user_id = $user_id";
            } else {
                $sql .= " AND session_id = '$session_id'";
            }
            
            if ($conn->query($sql)) {
                $response = ['status' => 'success', 'message' => 'Quantity updated'];
            }
        }
    }
    elseif ($action === 'remove') {
        $cart_id = intval($_POST['cart_id']);
        $sql = "DELETE FROM cart WHERE id = $cart_id";
        // Ensure ownership
        if ($user_id) {
            $sql .= " AND user_id = $user_id";
        } else {
            $sql .= " AND session_id = '$session_id'";
        }

        if ($conn->query($sql)) {
            $response = ['status' => 'success', 'message' => 'Item removed'];
        }
    }
    elseif ($action === 'count') {
         $sql = "SELECT SUM(quantity) as total FROM cart WHERE ";
         if ($user_id) {
            $sql .= "user_id = $user_id";
        } else {
            $sql .= "session_id = '$session_id' AND user_id IS NULL";
        }
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        $response = ['status' => 'success', 'count' => $row['total'] ? $row['total'] : 0];
    }
}

echo json_encode($response);
?>
