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
$variant_id = (isset($_POST['variant_id']) && $_POST['variant_id'] !== '') ? (int)$_POST['variant_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$action = isset($_POST['action']) ? $_POST['action'] : 'add';
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'products.php';

if ($product_id <= 0) {
    $_SESSION['error_message'] = 'Invalid product';
    header('Location: ' . $redirect);
    exit;
}

try {
    // Basic stock check: prefer product-level stock, but if product has variants
    // use the total of active variant stock as authoritative (so variant-stocked items reflect availability)
    $stock_q = "SELECT stock_quantity FROM products WHERE product_id = ? LIMIT 1";
    $stock_stmt = $conn->prepare($stock_q);
    if ($stock_stmt === false) throw new Exception('DB prepare failed (stock): ' . $conn->error);
    $stock_stmt->bind_param('i', $product_id);
    $stock_stmt->execute();
    $stock_res = $stock_stmt->get_result();
    $available_stock = null;
    if ($stock_res && $row = $stock_res->fetch_assoc()) {
        $available_stock = (int)$row['stock_quantity'];
    }
    // If product has no stock information treat as available; but if stock == 0, block
    // Also check variant-level stock (sum of active variants). If a specific variant was selected, use its stock.
    $variant_total = 0;
    if ($variant_id) {
        $single_var_q = "SELECT stock_quantity FROM product_variants WHERE variant_id = ? AND is_active = 1 LIMIT 1";
        $single_var_stmt = $conn->prepare($single_var_q);
        if ($single_var_stmt === false) throw new Exception('DB prepare failed (single variant): ' . $conn->error);
        $single_var_stmt->bind_param('i', $variant_id);
        $single_var_stmt->execute();
        $single_var_res = $single_var_stmt->get_result();
        if ($single_var_res && $vrow = $single_var_res->fetch_assoc()) {
            $variant_total = (int)$vrow['stock_quantity'];
        }
    } else {
        // Sum available active variant stock for the product
        $var_q = "SELECT COALESCE(SUM(stock_quantity), 0) AS total FROM product_variants WHERE product_id = ? AND is_active = 1";
        $var_stmt = $conn->prepare($var_q);
        if ($var_stmt === false) throw new Exception('DB prepare failed (variant sum): ' . $conn->error);
        $var_stmt->bind_param('i', $product_id);
        $var_stmt->execute();
        $var_res = $var_stmt->get_result();
        if ($var_res && $vrow = $var_res->fetch_assoc()) {
            $variant_total = (int)$vrow['total'];
        }
    }

    // If a specific variant was requested prefer using that variant's stock; otherwise prefer variant_total when available
    if ($variant_id) {
        // If a particular variant was requested, prefer the variant's stock
        // (variant_total will have been set from the single-variant query above)
        $available_stock = $variant_total;
    } else if ($variant_total > 0) {
        // If there are active variants and no variant was selected, use the sum of variant stock
        $available_stock = $variant_total;
    }

    if ($available_stock !== null && $available_stock <= 0 && $action === 'add') {
        $_SESSION['error_message'] = 'Product is out of stock';
        header('Location: ' . $redirect);
        exit;
    }
    if ($action === 'add') {
        // Check if already in cart — use variant_id to separate entries per variant
        if ($variant_id) {
            $check_query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND variant_id = ?";
            $check_stmt = $conn->prepare($check_query);
            if ($check_stmt === false) throw new Exception('DB prepare failed (check existing variant cart): ' . $conn->error);
            $check_stmt->bind_param("iii", $user_id, $product_id, $variant_id);
        } else {
            $check_query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
            $check_stmt = $conn->prepare($check_query);
            if ($check_stmt === false) throw new Exception('DB prepare failed (check existing product cart): ' . $conn->error);
            $check_stmt->bind_param("ii", $user_id, $product_id);
        }
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
            if ($check_result->num_rows > 0) {
                // Item already exists — do not add duplicate row or silently increase quantity.
                // Show a user-friendly message asking them to manage quantity in the cart page.
                $_SESSION['error_message'] = 'Product already added to cart';
                header('Location: ' . $redirect);
                exit;
            } else {
            // Add to cart with or without variant_id
            if ($variant_id) {
                $insert_query = "INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
                $insert_stmt = $conn->prepare($insert_query);
                if ($insert_stmt === false) throw new Exception('DB prepare failed (insert cart): ' . $conn->error);
                $insert_stmt->bind_param("iiii", $user_id, $product_id, $variant_id, $quantity);
            } else {
                $insert_query = "INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at, updated_at) VALUES (?, ?, NULL, ?, NOW(), NOW())";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
            }
            
            if ($insert_stmt->execute()) {
                // make sure quantity does not exceed stock (additional precaution)
                if ($available_stock !== null && $quantity > $available_stock) {
                    $_SESSION['error_message'] = 'Product is out of stock';
                    // Optionally could delete the cart row, but we'll just show error and leave as-is
                    header('Location: ' . $redirect);
                    exit;
                }
                $_SESSION['success_message'] = 'Product added to cart successfully';
            } else {
                throw new Exception($insert_stmt->error);
            }
        }
    } elseif ($action === 'remove') {
        // Remove from cart (consider variant_id when removing)
        if ($variant_id) {
            $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND variant_id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            if ($delete_stmt === false) throw new Exception('DB prepare failed (delete cart): ' . $conn->error);
            $delete_stmt->bind_param("iii", $user_id, $product_id, $variant_id);
        } else {
            $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("ii", $user_id, $product_id);
        }
        $delete_stmt->execute();
        
        $_SESSION['success_message'] = 'Product removed from cart';
    } elseif ($action === 'update_quantity') {
        // Update quantity
            if ($quantity > 0) {
            // Update with variant filter
            if ($variant_id) {
                $update_query = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ? AND variant_id = ?";
                $update_stmt = $conn->prepare($update_query);
                if ($update_stmt === false) throw new Exception('DB prepare failed (update cart quantity specific): ' . $conn->error);
                $update_stmt->bind_param("iiii", $quantity, $user_id, $product_id, $variant_id);
            } else {
                $update_query = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
            }
            
            // Ensure not exceeding available stock
            if ($available_stock !== null && $quantity > $available_stock) {
                $_SESSION['error_message'] = 'Cannot update — not enough stock available';
                header('Location: ' . $redirect);
                exit;
            }
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = 'Quantity updated';
            } else {
                throw new Exception($update_stmt->error);
            }
        } else {
            // deletion when quantity <= 0 should respect variant id
            if ($variant_id) {
                $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND variant_id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                if ($delete_stmt === false) throw new Exception('DB prepare failed (delete cart specific): ' . $conn->error);
                $delete_stmt->bind_param("iii", $user_id, $product_id, $variant_id);
            } else {
                $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("ii", $user_id, $product_id);
            }
            
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
