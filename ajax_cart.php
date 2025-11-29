<?php
session_start();
header('Content-Type: application/json');
include 'connection.php';

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $variant_id = (isset($_POST['variant_id']) && $_POST['variant_id'] !== '') ? (int)$_POST['variant_id'] : null;
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product']);
        exit;
    }

    // Determine available stock (product-level or variant-aware)
    $available_stock = null;

    $stock_q = "SELECT stock_quantity FROM products WHERE product_id = ? LIMIT 1";
    $stock_stmt = $conn->prepare($stock_q);
    if ($stock_stmt === false) throw new Exception('DB prepare failed (stock): ' . $conn->error);
    $stock_stmt->bind_param('i', $product_id);
    $stock_stmt->execute();
    $stock_res = $stock_stmt->get_result();
    if ($stock_res && $row = $stock_res->fetch_assoc()) {
        $available_stock = (int)$row['stock_quantity'];
    }

    // Compute variant(s) totals — prefer single variant if provided
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

    if ($variant_id) {
        $available_stock = $variant_total;
    } else if ($variant_total > 0) {
        $available_stock = $variant_total;
    }

    if ($available_stock !== null && $available_stock <= 0) {
        echo json_encode(['success' => false, 'message' => 'Product is out of stock']);
        exit;
    }

    // Check if already in cart (distinguish by variant)
    if ($variant_id) {
        $check_query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND variant_id = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) throw new Exception('DB prepare failed (check existing variant cart): ' . $conn->error);
        $check_stmt->bind_param('iii', $user_id, $product_id, $variant_id);
    } else {
        $check_query = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND variant_id IS NULL";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) throw new Exception('DB prepare failed (check existing product cart): ' . $conn->error);
        $check_stmt->bind_param('ii', $user_id, $product_id);
    }
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $row = $check_result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        if ($available_stock !== null && $new_quantity > $available_stock) {
            echo json_encode(['success' => false, 'message' => 'Cannot add more — Product stock insufficient']);
            exit;
        }

        $update_query = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?";
        $update_stmt = $conn->prepare($update_query);
        if ($update_stmt === false) throw new Exception('DB prepare failed (update): ' . $conn->error);
        $update_stmt->bind_param('ii', $new_quantity, $row['cart_id']);
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
            exit;
        } else {
            throw new Exception($update_stmt->error);
        }
    } else {
        // Insert new cart row
        if ($variant_id) {
            $insert_query = "INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            if ($insert_stmt === false) throw new Exception('DB prepare failed (insert): ' . $conn->error);
            $insert_stmt->bind_param('iiii', $user_id, $product_id, $variant_id, $quantity);
        } else {
            $insert_query = "INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at, updated_at) VALUES (?, ?, NULL, ?, NOW(), NOW())";
            $insert_stmt = $conn->prepare($insert_query);
            if ($insert_stmt === false) throw new Exception('DB prepare failed (insert): ' . $conn->error);
            $insert_stmt->bind_param('iii', $user_id, $product_id, $quantity);
        }

        if ($insert_stmt->execute()) {
            // Additional precaution: ensure not exceed stock
            if ($available_stock !== null && $quantity > $available_stock) {
                // rollback insertion
                $del = $conn->prepare('DELETE FROM cart WHERE cart_id = ?');
                if ($del) {
                    $cart_id = $conn->insert_id;
                    $del->bind_param('i', $cart_id);
                    $del->execute();
                }
                echo json_encode(['success' => false, 'message' => 'Product is out of stock']);
                exit;
            }
            echo json_encode(['success' => true, 'message' => 'Product added to cart successfully']);
            exit;
        } else {
            throw new Exception($insert_stmt->error);
        }
    }

} catch (Exception $e) {
    error_log('ajax_cart error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
    exit;
}
