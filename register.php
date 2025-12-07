<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
include 'connection.php';
include 'includes/smtp_mailer.php';

// Registration handling
$register_success = '';
$register_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // collect and sanitize
    $first = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $last = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $terms = isset($_POST['terms']) ? true : false;

    if (!$first || !$last || !$email || !$password || !$confirm) {
        $register_error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $register_error = 'Passwords do not match.';
    } elseif (!$terms) {
        $register_error = 'You must accept the terms.';
    } else {
        // check email uniqueness
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $register_error = 'Email already registered. Please login or use another email.';
        } else {
            // insert user with is_active = 0 (inactive) and is_email_verified = 0
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $username = strstr($email, '@', true) ?: $email;
            $ins = mysqli_prepare($conn, "INSERT INTO users (username, email, password, first_name, last_name, phone, role_id, is_active, is_email_verified, created_at) VALUES (?,?,?,?,?,?,2,0,0,NOW())");
            mysqli_stmt_bind_param($ins, 'ssssss', $username, $email, $hash, $first, $last, $phone);
            if (mysqli_stmt_execute($ins)) {
                $user_id = mysqli_insert_id($conn);
                
                $token = bin2hex(random_bytes(16));
                $expires = date('Y-m-d H:i:s', time() + 60*60*24);
                $vt = mysqli_prepare($conn, "INSERT INTO verification_tokens (user_id, token, expires_at) VALUES (?,?,?)");
                mysqli_stmt_bind_param($vt, 'iss', $user_id, $token, $expires);
                mysqli_stmt_execute($vt);

                // send verification email using SMTP
                $mail_sent = send_verification_email_smtp($email, $token);

                $register_success = 'Account created successfully! Please check your email inbox (and spam folder) for the verification link.';
                if (!$mail_sent) {
                    $host = $_SERVER['HTTP_HOST'];
                    $path = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
                    $verify_url = "http://" . $host . $path . "/verify.php?token=" . $token;
                    // show fallback link for local testing
                    $register_success .= '<br><strong>SMTP mail not configured - Use this link to verify:</strong><br><a href="' . $verify_url . '" style="color: #713600; word-break: break-all;">' . $verify_url . '</a>';
                }
            } else {
                $register_error = 'Registration failed: ' . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<div class="container-fluid" style="padding-top: 15vh; min-height: 100vh;">
    <div class="row justify-content-center" style="min-height: 80vh; padding-top: 4vh;">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); margin: 2rem 0;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #713600, #C05800);">
                    <h2 class="mb-0 fw-bold" style="color: #FDFBD4;  letter-spacing: 1px;">
                        Create Account
                    </h2>
                    <p class="mb-0 mt-2" style="color: #FDFBD4; font-size: 0.9rem;">
                        Join Pure Fit and start your fitness journey
                    </p>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($register_error)): ?>
                        <script>document.addEventListener('DOMContentLoaded', function(){ try{ showNotification(<?php echo json_encode($register_error); ?>, 'danger'); } catch(e){ console.error('showNotification not available', e); } });</script>
                    <?php endif; ?>
                    <?php if (!empty($register_success)): ?>
                        <script>document.addEventListener('DOMContentLoaded', function(){ try{ showNotification(<?php echo json_encode($register_success); ?>, 'success'); } catch(e){ console.error('showNotification not available', e); } });</script>
                    <?php endif; ?>
                    <div class="alert-container"></div>
                    <form method="POST" action="" id="registerForm" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="firstName" class="form-label fw-semibold" style="color: #713600; ">
                                    First Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 py-3" id="firstName" name="firstName" placeholder="First name" 
                                           style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="lastName" class="form-label fw-semibold" style="color: #713600; ">
                                    Last Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 py-3" id="lastName" name="lastName" placeholder="Last name" 
                                           style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #713600; ">
                                Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-3" id="email" name="email" placeholder="Enter your email" 
                                       style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold" style="color: #713600; ">
                                Phone Number
                            </label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-3" id="phone" name="phone" placeholder="Enter your phone number" 
                                       style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label fw-semibold" style="color: #713600; ">
                                    Password
                                </label>
                                <div class="input-group position-relative">
                                    <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 py-3 pe-5" id="password" name="password" placeholder="Create password" 
                                           style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                                    <span class="password-toggle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="confirmPassword" class="form-label fw-semibold" style="color: #713600; ">
                                    Confirm Password
                                </label>
                                <div class="input-group position-relative">
                                    <span class="input-group-text border-0" style="background: #FDFBD4; color: #C05800;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 py-3 pe-5" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" 
                                           style="background: #f8f9fa; border-left: 3px solid #C05800 !important; transition: all 0.3s ease;">
                                    <span class="password-toggle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                                        <i class="fas fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" style="accent-color: #C05800;">
                                <label class="form-check-label" for="terms" style="color: #713600; font-size: 0.9rem;">
                                    I agree to the <a href="#" class="text-decoration-none fw-bold" style="color: #713600;">Terms of Service</a> and 
                                    <a href="#" class="text-decoration-none fw-bold" style="color: #713600;">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold mb-3" 
                                style="background: #FDFBD4; color: #713600; border: 2px solid #C05800; transition: all 0.3s ease; letter-spacing: 1px; font-weight: 600;">
                            Create Account
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0" style="color: #713600; font-size: 0.9rem;">
                                Already have an account? 
                                <a href="login.php" class="text-decoration-none fw-bold" style="color: #C05800; transition: color 0.3s ease;">
                                    Sign in here
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
    background-color: #713600;
    border-color: #713600;
}

a:hover {
    color: #713600 !important;
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
.card-body form > *:nth-child(6) { animation-delay: 0.6s; }

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
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="js/validation.js"></script>
<script>
$(document).ready(function() {
    $('#registerForm').on('submit', function(e) {
        const isValid = Validation.validateForm('#registerForm', {
            firstName: { required: true },
            lastName: { required: true },
            email: { required: true, email: true },
            phone: { phone: true },
            password: { required: true, password: true },
            confirmPassword: { required: true, match: 'password' }
        });
        
        const termsChecked = $('#terms').is(':checked');
        if (!termsChecked) {
            Validation.showMessage('error', 'You must accept the terms and conditions');
            e.preventDefault();
            return false;
        }
        
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
