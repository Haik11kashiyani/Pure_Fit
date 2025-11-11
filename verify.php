<?php
include 'connection.php';

$message = '';
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $message = 'Invalid verification link.';
} else {
    $token = $_GET['token'];
    // find token
    $stmt = mysqli_prepare($conn, "SELECT user_id, expires_at FROM verification_tokens WHERE token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        $message = 'Invalid or expired verification token.';
    } else {
        mysqli_stmt_bind_result($stmt, $user_id, $expires_at);
        mysqli_stmt_fetch($stmt);
        $now = date('Y-m-d H:i:s');
        if ($expires_at < $now) {
            $message = 'Verification token has expired.';
        } else {
            // activate user
            $up = mysqli_prepare($conn, "UPDATE users SET is_active = 1, updated_at = NOW() WHERE user_id = ?");
            mysqli_stmt_bind_param($up, 'i', $user_id);
            if (mysqli_stmt_execute($up)) {
                // delete token
                $del = mysqli_prepare($conn, "DELETE FROM verification_tokens WHERE token = ?");
                mysqli_stmt_bind_param($del, 's', $token);
                mysqli_stmt_execute($del);
                // redirect to login with success
                header('Location: login.php?verified=1');
                exit;
            } else {
                $message = 'Failed to activate account. Please contact support.';
            }
        }
    }
}

// simple page showing message and link to login
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Account</title>
    <link rel="stylesheet" href="assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <div class="card">
        <div class="card-body">
            <h3>Account Verification</h3>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="login.php" class="btn btn-primary">Go to Login</a>
        </div>
    </div>
</div>
</body>
</html>