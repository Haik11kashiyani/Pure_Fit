<?php
include 'connection.php';
include 'includes/smtp_mailer.php';

$message = '';
$success = false;

if (!isset($_GET['token']) || empty($_GET['token'])) {
    $message = 'Invalid verification link.';
} else {
    $token = $_GET['token'];
    // find token
    $stmt = mysqli_prepare($conn, "SELECT vt.user_id, vt.expires_at, u.first_name, u.email FROM verification_tokens vt INNER JOIN users u ON vt.user_id = u.user_id WHERE vt.token = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) === 0) {
        $message = 'Invalid or expired verification token.';
    } else {
        mysqli_stmt_bind_result($stmt, $user_id, $expires_at, $first_name, $email);
        mysqli_stmt_fetch($stmt);
        $now = date('Y-m-d H:i:s');
        if ($expires_at < $now) {
            $message = 'Verification token has expired. Please register again.';
        } else {
            // activate user and set is_active = 1
            $up = mysqli_prepare($conn, "UPDATE users SET is_email_verified = 1, is_active = 1, updated_at = NOW() WHERE user_id = ?");
            mysqli_stmt_bind_param($up, 'i', $user_id);
            if (mysqli_stmt_execute($up)) {
                // delete token
                $del = mysqli_prepare($conn, "DELETE FROM verification_tokens WHERE token = ?");
                mysqli_stmt_bind_param($del, 's', $token);
                mysqli_stmt_execute($del);
                
                // Send welcome email
                $welcome_sent = send_welcome_email($email, $first_name);
                
                $success = true;
                $message = 'Account verified successfully! Your account is now active and you can login.';
                
                // Redirect to login with success message after 3 seconds
                header('refresh:3;url=login.php?verified=1');
            } else {
                $message = 'Failed to verify account. Please contact support.';
            }
        }
    }
}

// verification page with proper styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Pure Fit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #BAC095, #D4DE95);
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
        }
        .verification-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .verification-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #636B2F, #3D4127);
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .card-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        .logo {
            color: #BAC095;
        }
        .card-body {
            padding: 2rem;
            text-align: center;
        }
        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .error-icon {
            color: #dc3545;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .message {
            color: #636B2F;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-login {
            background: linear-gradient(135deg, #636B2F, #3D4127);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 107, 47, 0.3);
            color: white;
        }
        .countdown {
            color: #999;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="verification-card">
            <div class="card-header">
                <h1><span class="logo">Pure</span> Fit</h1>
                <p class="mb-0 mt-2">Email Verification</p>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-success mb-3">Verification Successful!</h3>
                <?php else: ?>
                    <div class="error-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="text-danger mb-3">Verification Failed</h3>
                <?php endif; ?>
                
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                
                <?php if ($success): ?>
                    <a href="login.php?verified=1" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                    </a>
                    <div class="countdown">
                        <i class="fas fa-clock me-1"></i>Redirecting to login page in 3 seconds...
                    </div>
                <?php else: ?>
                    <a href="register.php" class="btn-login">
                        <i class="fas fa-user-plus me-2"></i>Register Again
                    </a>
                    <div class="mt-3">
                        <a href="login.php" class="text-muted" style="text-decoration: none;">
                            <i class="fas fa-arrow-left me-1"></i>Back to Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>