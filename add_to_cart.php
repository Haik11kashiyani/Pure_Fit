<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please login to add items to cart';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$action = isset($_POST['action']) ? $_POST['action'] : 'add';
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'products.php';

if ($product_id <= 0) {
    $_SESSION['error_message'] = 'Invalid product';
    header('Location: ' . $redirect);
    exit;
}

try {
    if ($action === 'add') {
        // Check if already in cart (without variant_id check)
        $check_query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update quantity
            $row = $check_result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;
            
            $update_query = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = 'Cart updated successfully';
            } else {
                throw new Exception($update_stmt->error);
            }
        } else {
            // Add to cart with NULL variant_id (after running fix_cart_variant_id.sql)
            $variant_id = NULL;
            $insert_query = "INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("iiii", $user_id, $product_id, $variant_id, $quantity);
            
            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = 'Product added to cart successfully';
            } else {
                throw new Exception($insert_stmt->error);
            }
        }
    } elseif ($action === 'remove') {
        // Remove from cart
        $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $user_id, $product_id);
        $delete_stmt->execute();
        
        $_SESSION['success_message'] = 'Product removed from cart';
    } elseif ($action === 'update_quantity') {
        // Update quantity
        if ($quantity > 0) {
            $update_query = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = 'Quantity updated';
            } else {
                throw new Exception($update_stmt->error);
            }
        } else {
            $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("ii", $user_id, $product_id);
            
            if ($delete_stmt->execute()) {
                $_SESSION['success_message'] = 'Product removed from cart';
            } else {
                throw new Exception($delete_stmt->error);
            }
        }
    }
} catch (Exception $e) {
    // Log the actual error for debugging
    error_log('Cart Error: ' . $e->getMessage());
    $_SESSION['error_message'] = 'Error: Unable to update cart. Please try again.';
}

$conn->close();
header('Location: ' . $redirect);
exit;
?>
