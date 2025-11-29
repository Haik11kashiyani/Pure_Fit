<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate form submission
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

// Check if using saved address or new address
$address_id = isset($_POST['address_id']) ? (int)$_POST['address_id'] : 0;
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

if ($address_id > 0) {
    // Using saved address
    $addr_query = "SELECT * FROM addresses WHERE address_id = ? AND user_id = ?";
    $addr_stmt = $conn->prepare($addr_query);
    $addr_stmt->bind_param("ii", $address_id, $user_id);
    $addr_stmt->execute();
    $addr_result = $addr_stmt->get_result();
    
    if ($addr_result->num_rows === 0) {
        $_SESSION['error'] = 'Invalid address selected';
        header('Location: checkout.php');
        exit;
    }
    
    $addr = $addr_result->fetch_assoc();
    $full_name = $addr['full_name'];
    $phone = $addr['phone'];
    
    // Build shipping address from saved data
    $shipping_address = $addr['address_line1'];
    if (!empty($addr['address_line2'])) {
        $shipping_address .= ', ' . $addr['address_line2'];
    }
    $shipping_address .= ', ' . $addr['city'] . ', ' . $addr['state'] . ' - ' . $addr['pincode'];
    $shipping_address .= '\nPhone: ' . $phone;
    
} else {
    // Using new address
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address_line1 = mysqli_real_escape_string($conn, $_POST['address_line1']);
    $address_line2 = mysqli_real_escape_string($conn, $_POST['address_line2'] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $save_address = isset($_POST['save_address']) ? 1 : 0;
    $set_default = isset($_POST['set_default']) ? 1 : 0;
    
    // Build shipping address
    $shipping_address = $address_line1;
    if (!empty($address_line2)) {
        $shipping_address .= ', ' . $address_line2;
    }
    $shipping_address .= ', ' . $city . ', ' . $state . ' - ' . $pincode;
    $shipping_address .= '\nPhone: ' . $phone . '\nEmail: ' . $email;
    
    // Save address if requested
    if ($save_address) {
        // If set as default, unset other defaults
        if ($set_default) {
            $unset_query = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
            $unset_stmt = $conn->prepare($unset_query);
            $unset_stmt->bind_param("i", $user_id);
            $unset_stmt->execute();
        }
        
        // Check if this is first address
        $check_query = "SELECT COUNT(*) as count FROM addresses WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_row = $check_result->fetch_assoc();
        
        // If first address, make it default
        if ($check_row['count'] == 0) {
            $set_default = 1;
        }
        
        // Insert address
        $addr_insert = "INSERT INTO addresses (user_id, full_name, phone, address_line1, address_line2, city, state, pincode, is_default) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $addr_insert_stmt = $conn->prepare($addr_insert);
        $addr_insert_stmt->bind_param("isssssssi", $user_id, $full_name, $phone, $address_line1, $address_line2, $city, $state, $pincode, $set_default);
        $addr_insert_stmt->execute();
    }
}

// Fetch cart items and calculate total
$cart_query = "SELECT c.*, p.price FROM cart c 
               INNER JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = ? AND p.is_active = 1";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$cart_items = [];
$subtotal = 0;

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $subtotal += ($item['price'] * $item['quantity']);
}

// If cart is empty, redirect
if (count($cart_items) === 0) {
    header('Location: cart.php');
    exit;
}

$tax = $subtotal * 0.18;
$total_amount = $subtotal + $tax;

// Determine payment method ID
$payment_method_id = ($payment_method === 'cod') ? 1 : 2;

// Set order and payment status based on payment method
if ($payment_method === 'cod') {
    $order_status = 'confirmed';  // COD orders are confirmed immediately
    $payment_status = 'pending';  // Payment pending until delivery
    $transaction_id = 'COD-' . time() . '-' . $user_id;
    $payment_notes = 'Cash on Delivery - Payment will be collected at the time of delivery';
} else {
    $order_status = 'pending';    // Online payment orders wait for payment
    $payment_status = 'pending';  // Payment pending
    $transaction_id = null;
    $payment_notes = 'Online Payment - Awaiting payment confirmation';
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Insert order with transaction details
    $order_query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method_id, order_status, payment_status, transaction_id, payment_notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_query);
    $order_stmt->bind_param("idsissss", $user_id, $total_amount, $shipping_address, $payment_method_id, $order_status, $payment_status, $transaction_id, $payment_notes);
    $order_stmt->execute();
    $order_id = $conn->insert_id;

    // Insert order items
    $order_item_query = "INSERT INTO order_items (order_id, product_id, variant_id, quantity, price_at_time) 
                         VALUES (?, ?, ?, ?, ?)";
    $order_item_stmt = $conn->prepare($order_item_query);

    foreach ($cart_items as $item) {
        $variant_id = $item['variant_id'] ?? null;

        // Check and decrement stock for each cart item (prevent oversell)
        if (!empty($variant_id)) {
            // Lock the variant row and check stock
            $check_var_q = "SELECT stock_quantity, product_id FROM product_variants WHERE variant_id = ? FOR UPDATE";
            $check_var_stmt = $conn->prepare($check_var_q);
            $check_var_stmt->bind_param('i', $variant_id);
            $check_var_stmt->execute();
            $check_var_res = $check_var_stmt->get_result();
            if ($check_var_res && $var_row = $check_var_res->fetch_assoc()) {
                $available = (int)$var_row['stock_quantity'];
                if ($available < (int)$item['quantity']) {
                    throw new Exception('Insufficient stock for product id ' . $item['product_id']);
                }
                // Decrement variant stock
                $dec_var_q = "UPDATE product_variants SET stock_quantity = stock_quantity - ? WHERE variant_id = ?";
                $dec_var_stmt = $conn->prepare($dec_var_q);
                $dec_var_stmt->bind_param('ii', $item['quantity'], $variant_id);
                if (!$dec_var_stmt->execute()) throw new Exception($dec_var_stmt->error);

                // Also decrement product-level stock to keep in sync (if column exists)
                $dec_prod_q = "UPDATE products SET stock_quantity = GREATEST(stock_quantity - ?, 0) WHERE product_id = ?";
                $dec_prod_stmt = $conn->prepare($dec_prod_q);
                $dec_prod_stmt->bind_param('ii', $item['quantity'], $item['product_id']);
                if (!$dec_prod_stmt->execute()) throw new Exception($dec_prod_stmt->error);
            } else {
                throw new Exception('Variant not found for id ' . $variant_id);
            }
        } else {
            // No variant: use product-level stock
            $check_prod_q = "SELECT stock_quantity FROM products WHERE product_id = ? FOR UPDATE";
            $check_prod_stmt = $conn->prepare($check_prod_q);
            $check_prod_stmt->bind_param('i', $item['product_id']);
            $check_prod_stmt->execute();
            $check_prod_res = $check_prod_stmt->get_result();
            if ($check_prod_res && $prow = $check_prod_res->fetch_assoc()) {
                $available = (int)$prow['stock_quantity'];
                if ($available < (int)$item['quantity']) {
                    throw new Exception('Insufficient stock for product id ' . $item['product_id']);
                }
                $dec_prod_q = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
                $dec_prod_stmt = $conn->prepare($dec_prod_q);
                $dec_prod_stmt->bind_param('ii', $item['quantity'], $item['product_id']);
                if (!$dec_prod_stmt->execute()) throw new Exception($dec_prod_stmt->error);
            } else {
                throw new Exception('Product not found for id ' . $item['product_id']);
            }
        }

        // Insert the order item after stock decremented
        $order_item_stmt->bind_param("iiiid", $order_id, $item['product_id'], $variant_id, $item['quantity'], $item['price']);
        $order_item_stmt->execute();
    }

    // Clear cart
    $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $clear_cart_stmt = $conn->prepare($clear_cart_query);
    $clear_cart_stmt->bind_param("i", $user_id);
    $clear_cart_stmt->execute();

    // Commit transaction
    mysqli_commit($conn);

    // Set success message based on payment method
    if ($payment_method === 'cod') {
        $_SESSION['order_success'] = 'Order placed successfully! You will pay cash on delivery.';
    } else {
        $_SESSION['order_success'] = 'Order placed successfully! Please complete the payment.';
    }

    // Redirect to profile orders page
    header('Location: profile.php?tab=orders&order_id=' . $order_id);
    exit;

} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($conn);
    $_SESSION['error'] = 'Order placement failed: ' . $e->getMessage();
    header('Location: checkout.php');
    exit;
}

$conn->close();
?>
