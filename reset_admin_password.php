<?php
include 'connection.php';

$message = '';
$messageType = '';

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match!";
        $messageType = "danger";
    } elseif (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters!";
        $messageType = "danger";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update admin password
        $update_query = "UPDATE users SET password = ? WHERE email = 'admin@purefit.com'";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 's', $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "Admin password updated successfully! You can now login with the new password.";
            $messageType = "success";
        } else {
            $message = "Error updating password: " . mysqli_error($conn);
            $messageType = "danger";
        }
        mysqli_stmt_close($stmt);
    }
}

// Check if admin user exists
$check_query = "SELECT u.user_id, u.username, u.email, r.role_name 
                FROM users u 
                INNER JOIN roles r ON u.role_id = r.role_id 
                WHERE u.email = 'admin@purefit.com'";
$result = mysqli_query($conn, $check_query);
$admin_exists = mysqli_num_rows($result) > 0;
$admin_data = $admin_exists ? mysqli_fetch_assoc($result) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password - Pure Fit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #636B2F 0%, #3D4127 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-card {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card reset-card shadow-lg">
            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                <i class="fas fa-key fa-3x mb-3" style="color: #3D4127;"></i>
                <h3 class="mb-0 fw-bold" style="color: #3D4127;">Reset Admin Password</h3>
            </div>
            <div class="card-body p-4">
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                    <i class="fas fa-<?php echo $messageType == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if ($admin_exists): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Admin User Found:</strong><br>
                    Username: <?php echo htmlspecialchars($admin_data['username']); ?><br>
                    Email: <?php echo htmlspecialchars($admin_data['email']); ?><br>
                    Role: <?php echo htmlspecialchars($admin_data['role_name']); ?>
                </div>

                <form method="POST" id="resetForm" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock me-2"></i>New Password
                        </label>
                        <div class="position-relative">
                            <input type="password" name="new_password" class="form-control form-control-lg pe-5" 
                                   placeholder="Enter new password">
                            <span class="password-toggle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                <i class="fas fa-eye text-muted"></i>
                            </span>
                        </div>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-lock me-2"></i>Confirm Password
                        </label>
                        <div class="position-relative">
                            <input type="password" name="confirm_password" class="form-control form-control-lg pe-5" 
                                   placeholder="Confirm new password">
                            <span class="password-toggle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                <i class="fas fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="reset_password" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-save me-2"></i>Reset Password
                    </button>
                </form>

                <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Admin user not found!</strong><br>
                    Please create an admin user first by running this SQL:
                </div>
                <pre class="bg-light p-3 rounded">INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `phone`, `role_id`, `is_active`) 
VALUES ('admin', 'admin@purefit.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '1234567890', 1, 1);</pre>
                <?php endif; ?>

                <hr>
                <div class="text-center">
                    <a href="login.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Security Notice:</strong> Delete this file (reset_admin_password.php) after resetting the password!
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).on('click', '.password-toggle', function() {
            const $toggle = $(this);
            const $input = $toggle.prev('input');
            const $icon = $toggle.find('i');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#resetForm').on('submit', function(e) {
            const newPass = $('[name="new_password"]').val();
            const confirmPass = $('[name="confirm_password"]').val();
            
            if (newPass.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters');
                return false;
            }
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Passwords do not match');
                return false;
            }
        });
    });
    </script>
</body>
</html>
