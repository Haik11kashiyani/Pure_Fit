<?php
session_start();
include 'connection.php';
include 'includes/smtp_mailer.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please login to resend verification email';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$message = '';

// Check if user is already verified
$check_query = "SELECT is_email_verified, email, first_name FROM users WHERE user_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    $message = 'User not found';
} else {
    $user = $check_result->fetch_assoc();
    
    if ($user['is_email_verified'] == 1) {
        $message = 'Your email is already verified! You can login.';
    } else {
        // Delete any existing verification tokens
        $delete_tokens = "DELETE FROM verification_tokens WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_tokens);
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();
        
        // Generate new verification token
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 60*60*24); // 24 hours
        
        // Insert new verification token
        $insert_token = "INSERT INTO verification_tokens (user_id, token, expires_at) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_token);
        $insert_stmt->bind_param("iss", $user_id, $token, $expires);
        
        if ($insert_stmt->execute()) {
            // Send verification email
            $mail_sent = send_verification_email_smtp($user['email'], $token);
            
            if ($mail_sent) {
                $success = true;
                $message = 'Verification email sent successfully! Please check your inbox (and spam folder).';
            } else {
                $message = 'Failed to send verification email. Please try again later.';
            }
        } else {
            $message = 'Failed to generate verification token. Please try again.';
        }
    }
}

// Store message in session
if ($success) {
    $_SESSION['success_message'] = $message;
} else {
    $_SESSION['error_message'] = $message;
}

// Redirect back to login or previous page
header('Location: login.php');
exit;
?>
