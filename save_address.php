<?php
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

// Get form data
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address_line1 = mysqli_real_escape_string($conn, $_POST['address_line1']);
$address_line2 = mysqli_real_escape_string($conn, $_POST['address_line2'] ?? '');
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state = mysqli_real_escape_string($conn, $_POST['state']);
$pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
$is_default = isset($_POST['is_default']) ? 1 : 0;
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'checkout.php';

// If this is set as default, unset all other default addresses for this user
if ($is_default == 1) {
    $unset_query = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
    $unset_stmt = $conn->prepare($unset_query);
    $unset_stmt->bind_param("i", $user_id);
    $unset_stmt->execute();
}

// Check if this is the first address for the user
$check_query = "SELECT COUNT(*) as count FROM addresses WHERE user_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();

// If first address, make it default automatically
if ($check_row['count'] == 0) {
    $is_default = 1;
}

// Insert new address
$insert_query = "INSERT INTO addresses (user_id, full_name, phone, address_line1, address_line2, city, state, pincode, is_default) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("isssssssi", $user_id, $full_name, $phone, $address_line1, $address_line2, $city, $state, $pincode, $is_default);

if ($insert_stmt->execute()) {
    $_SESSION['success'] = 'Address saved successfully!';
} else {
    $_SESSION['error'] = 'Failed to save address. Please try again.';
}

$conn->close();
header('Location: ' . $redirect);
exit;
?>
