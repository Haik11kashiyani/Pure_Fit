<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please login to add items to favorites';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'add';
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'products.php';

if ($product_id <= 0) {
    $_SESSION['error_message'] = 'Invalid product';
    header('Location: ' . $redirect);
    exit;
}

try {
    if ($action === 'add') {
        // Check if already in favorites
        $check_query = "SELECT favorite_id FROM favorites WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            // Add to favorites
            $insert_query = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ii", $user_id, $product_id);
            
            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = 'Product added to favorites successfully';
            } else {
                throw new Exception($insert_stmt->error);
            }
        } else {
            $_SESSION['success_message'] = 'Product is already in your favorites';
        }
    } elseif ($action === 'remove') {
        // Remove from favorites
        $delete_query = "DELETE FROM favorites WHERE user_id = ? AND product_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $user_id, $product_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = 'Product removed from favorites';
        } else {
            throw new Exception($delete_stmt->error);
        }
    }
} catch (Exception $e) {
    error_log('Favorites Error: ' . $e->getMessage());
    $_SESSION['error_message'] = 'Error: Unable to update favorites. Please try again.';
}

$conn->close();
header('Location: ' . $redirect);
exit;
?>
