
<?php
ob_start();
include 'connection.php';
session_start();
$login_error = '';
// If coming from verification
$verified_msg = '';
if (isset($_GET['verified']) && $_GET['verified'] == 1) {
    $verified_msg = 'Your account has been verified. You may now sign in.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (!$email || !$password) {
        $login_error = 'Please enter email and password.';
    } else {
        // Check if is_email_verified column exists
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'is_email_verified'");
        $column_exists = mysqli_num_rows($check_column) > 0;
        
        // Get user details including role and verification status (if column exists)
        if ($column_exists) {
            $stmt = mysqli_prepare($conn, "SELECT u.user_id, u.password, u.is_active, u.is_email_verified, u.first_name, u.last_name, r.role_name 
                                            FROM users u 
                                            INNER JOIN roles r ON u.role_id = r.role_id 
                                            WHERE u.email = ? LIMIT 1");
        } else {
            // Fallback for when column doesn't exist (backward compatibility)
            $stmt = mysqli_prepare($conn, "SELECT u.user_id, u.password, u.is_active, 1 as is_email_verified, u.first_name, u.last_name, r.role_name 
                                            FROM users u 
                                            INNER JOIN roles r ON u.role_id = r.role_id 
                                            WHERE u.email = ? LIMIT 1");
        }
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $user_id, $hash, $is_active, $is_email_verified, $first_name, $last_name, $role_name);
            mysqli_stmt_fetch($stmt);
            if (!password_verify($password, $hash)) {
                $login_error = 'Invalid credentials.';
            } elseif (!$is_active) {
                $login_error = 'Account not active. Please contact support.';
            } elseif (!$is_email_verified) {
                $login_error = 'Account not verified. Please check your email for a verification link. <a href="resend_verification.php" class="alert-link">Click here to resend</a>.';
            } else {
                // Login success - Check role and redirect accordingly
                if ($role_name == 'admin') {
                    // Admin user - redirect to admin panel
                    $_SESSION['admin_id'] = $user_id;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_name'] = $first_name . ' ' . $last_name;
                    header('Location: admin/index.php');
                    exit;
                } else {
                    // Regular customer - redirect to main site
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['email'] = $email;
                    header('Location: index.php');
                    exit;
                }
            }
        } else {
            $login_error = 'Invalid credentials.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="container-fluid" style="padding-top: 15vh; min-height: 100vh; ">
    <div class="row justify-content-center" style="min-height: 80vh; padding-top: 4vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); margin: 2rem 0;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                    <h2 class="mb-0 fw-bold" style="color: #3D4127;  letter-spacing: 1px;">
                        Welcome Back
                    </h2>
                    <p class="mb-0 mt-2" style="color: #636B2F; font-size: 0.9rem;">
                        Sign in to your Pure Fit account
                    </p>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($login_error)): ?>
                        <script>document.addEventListener('DOMContentLoaded', function(){ try{ showNotification(<?php echo json_encode($login_error); ?>, 'danger'); } catch(e){ console.error('showNotification not available', e); } });</script>
                    <?php endif; ?>
                    <?php if (!empty($verified_msg)): ?>
                        <script>document.addEventListener('DOMContentLoaded', function(){ try{ showNotification(<?php echo json_encode($verified_msg); ?>, 'success'); } catch(e){ console.error('showNotification not available', e); } });</script>
                    <?php endif; ?>
                    <div class="alert-container"></div>
                    <form method="POST" action="" id="loginForm" novalidate>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #3D4127;">
                                Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-3" id="email" name="email" placeholder="Enter your email" 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important; transition: all 0.3s ease;">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #3D4127;">
                                Password
                            </label>
                            <div class="input-group position-relative">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                    <input type="password" class="form-control border-0 py-3 pe-5" id="password" name="password" placeholder="Enter your password" 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important; transition: all 0.3s ease;">
                                <span class="password-toggle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                    <i class="fas fa-eye text-muted"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a href="#" class="text-decoration-none" style="color: #636B2F; font-size: 0.9rem; transition: color 0.3s ease;">
                                Forgot password?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold text-white mb-3" 
                                style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease;  letter-spacing: 1px;">
                            Sign In
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">
                                Don't have an account? 
                                <a href="register.php" class="text-decoration-none fw-bold" style="color: #3D4127; transition: color 0.3s ease;">
                                    Sign up here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
  
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.2) !important;
}

.input-group .form-control:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.02);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-check-input:checked {
    background-color: #636B2F;
    border-color: #636B2F;
}

a:hover {
    color: #BAC095 !important;
}

/* Animation for form elements */
.card-body form > * {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

.card-body form > *:nth-child(1) { animation-delay: 0.1s; }
.card-body form > *:nth-child(2) { animation-delay: 0.2s; }
.card-body form > *:nth-child(3) { animation-delay: 0.3s; }
.card-body form > *:nth-child(4) { animation-delay: 0.4s; }
.card-body form > *:nth-child(5) { animation-delay: 0.5s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Validation styling */
.form-control.is-invalid {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.input-group .form-control.is-invalid {
    border-left-color: #dc3545 !important;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
    display: block;
    width: 100%;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card {
        margin: 1rem;
    }
    
    .container-fluid {
        padding: 1rem;
    }
    
    .row {
        min-height: 90vh !important;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="js/validation.js"></script>
<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        const isValid = Validation.validateForm('#loginForm', {
            email: { required: true, email: true },
            password: { required: true, password: true }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

    <?php
        $contant = ob_get_clean();
        include_once 'master_layout.php';
    ?>