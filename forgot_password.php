<?php
session_start();
ob_start();
include 'connection.php';
include 'includes/smtp_mailer.php';

$success_msg = '';
$error_msg = '';

if (!function_exists('clear_reset_session')) {
    function clear_reset_session() {
        unset($_SESSION['reset_stage']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_first_name']);
    }
}

// If user lands on this page via GET (fresh visit), reset any previous flow data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    clear_reset_session();
}

$stage = $_SESSION['reset_stage'] ?? 'request';
$prefill_email = $_SESSION['reset_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'send_otp') {
        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            $error_msg = 'Please enter your registered email address.';
            $stage = 'request';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_msg = 'Please enter a valid email address.';
            $stage = 'request';
        } else {
            $stmt = mysqli_prepare($conn, 'SELECT user_id, first_name FROM users WHERE email = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 0) {
                $error_msg = 'No account found with that email.';
                $stage = 'request';
            } else {
                mysqli_stmt_bind_result($stmt, $user_id, $first_name);
                mysqli_stmt_fetch($stmt);

                $otp = (string) random_int(100000, 999999);

                $delete_stmt = mysqli_prepare($conn, 'DELETE FROM password_reset_tokens WHERE user_id = ?');
                mysqli_stmt_bind_param($delete_stmt, 'i', $user_id);
                mysqli_stmt_execute($delete_stmt);
                mysqli_stmt_close($delete_stmt);

                $insert_stmt = mysqli_prepare($conn, 'INSERT INTO password_reset_tokens (user_id, otp, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))');
                mysqli_stmt_bind_param($insert_stmt, 'is', $user_id, $otp);

                if (mysqli_stmt_execute($insert_stmt)) {
                    $mail_sent = send_password_reset_otp($email, $first_name ?: 'there', $otp);

                    if ($mail_sent) {
                        $_SESSION['reset_stage'] = 'verify';
                        $_SESSION['reset_email'] = $email;
                        $_SESSION['reset_user_id'] = $user_id;
                        $_SESSION['reset_first_name'] = $first_name;
                        $stage = 'verify';
                        $prefill_email = $email;
                        $success_msg = 'OTP sent! Please check your email (including spam folder).';
                    } else {
                        $error_msg = 'Failed to send OTP email. Please try again later.';
                        $stage = 'request';
                    }
                } else {
                    $error_msg = 'Failed to generate OTP. Please try again later.';
                    $stage = 'request';
                }

                mysqli_stmt_close($insert_stmt);
            }

            mysqli_stmt_close($stmt);
        }
    } elseif ($action === 'reset_password') {
        $otp = trim($_POST['otp'] ?? '');
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!isset($_SESSION['reset_user_id'], $_SESSION['reset_email'])) {
            $error_msg = 'Your session has expired. Please request a new OTP.';
            $stage = 'request';
            clear_reset_session();
        } elseif (empty($otp) || empty($new_password) || empty($confirm_password)) {
            $error_msg = 'Please fill in all required fields.';
            $stage = 'verify';
        } elseif (!ctype_digit($otp) || strlen($otp) !== 6) {
            $error_msg = 'Please enter a valid 6-digit OTP.';
            $stage = 'verify';
        } elseif (strlen($new_password) < 8) {
            $error_msg = 'Password must be at least 8 characters long.';
            $stage = 'verify';
        } elseif ($new_password !== $confirm_password) {
            $error_msg = 'Passwords do not match.';
            $stage = 'verify';
        } else {
            $user_id = $_SESSION['reset_user_id'];

            $token_stmt = mysqli_prepare($conn, 'SELECT id FROM password_reset_tokens WHERE user_id = ? AND otp = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1');
            mysqli_stmt_bind_param($token_stmt, 'is', $user_id, $otp);
            mysqli_stmt_execute($token_stmt);
            mysqli_stmt_store_result($token_stmt);

            if (mysqli_stmt_num_rows($token_stmt) === 0) {
                $error_msg = 'Invalid or expired OTP. Please request a new one.';
                $stage = 'verify';
            } else {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $update_stmt = mysqli_prepare($conn, 'UPDATE users SET password = ?, updated_at = NOW() WHERE user_id = ?');
                mysqli_stmt_bind_param($update_stmt, 'si', $password_hash, $user_id);

                if (mysqli_stmt_execute($update_stmt)) {
                    $delete_stmt = mysqli_prepare($conn, 'DELETE FROM password_reset_tokens WHERE user_id = ?');
                    mysqli_stmt_bind_param($delete_stmt, 'i', $user_id);
                    mysqli_stmt_execute($delete_stmt);
                    mysqli_stmt_close($delete_stmt);

                    clear_reset_session();
                    $_SESSION['success_message'] = 'Password reset successful! Please login with your new password.';
                    header('Location: login.php');
                    exit;
                } else {
                    $error_msg = 'Failed to reset password. Please try again.';
                    $stage = 'verify';
                }

                mysqli_stmt_close($update_stmt);
            }

            mysqli_stmt_close($token_stmt);
        }
    } elseif ($action === 'change_email') {
        clear_reset_session();
        $stage = 'request';
        $prefill_email = '';
    }
}
?>
<div class="container-fluid forgot-wrapper" style="padding-top: 12vh; min-height: 100vh;">
    <div class="row justify-content-center" style="min-height: 80vh;">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); margin: 2rem 0;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #713600, #C05800);">
                    <h2 class="mb-0 fw-bold" style="color: #FDFBD4; letter-spacing: 1px;">
                        Forgot Password
                    </h2>
                    <p class="mb-0 mt-2" style="color: #FDFBD4; font-size: 0.95rem;">
                        Enter your email to receive a one-time password (OTP)
                    </p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <div id="clientAlert" class="alert alert-danger d-none" role="alert"></div>

                    <form method="POST" action="" id="forgotPasswordForm" data-stage="<?php echo htmlspecialchars($stage); ?>" novalidate>
                        <?php if ($stage === 'request'): ?>
                            <input type="hidden" name="action" value="send_otp">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold" style="color: #38240D;">
                                    Registered Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #C05800; color: #FDFBD4;">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control border-0 py-3" id="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($prefill_email); ?>" required
                                           style="background: #fffdf4; border-left: 3px solid #C05800 !important; color: #38240D;">
                                </div>
                            </div>
                            <button type="submit" id="sendOtpBtn" class="btn w-100 py-3 rounded-pill fw-bold"
                                    style="background: linear-gradient(135deg, #C05800, #713600); border: 2px solid #FDFBD4; color: #FDFBD4 !important; letter-spacing: 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.6); font-size: 1.05rem;">
                                Send OTP
                            </button>
                        <?php elseif ($stage === 'verify'): ?>
                            <input type="hidden" name="action" value="reset_password">
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="color: #38240D;">
                                    Email Address
                                </label>
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <p class="mb-0" style="color: #636B2F; font-weight: 600;">
                                        <?php echo htmlspecialchars($prefill_email); ?>
                                    </p>
                                    <button type="submit" name="action" value="change_email" class="btn btn-sm btn-outline-secondary rounded-pill mt-2 mt-md-0">
                                        Change Email
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="otp" class="form-label fw-semibold" style="color: #38240D;">
                                    Enter 6-digit OTP <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #C05800; color: #FDFBD4;">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 py-3" id="otp" name="otp" placeholder="123456" maxlength="6" required
                                           style="background: #fffdf4; border-left: 3px solid #C05800 !important; color: #38240D;">
                                </div>
                                <small class="text-muted">OTP is valid for 10 minutes.</small>
                            </div>

                            <div class="mb-4">
                                <label for="new_password" class="form-label fw-semibold" style="color: #38240D;">
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group password-toggle-group">
                                    <span class="input-group-text border-0" style="background: #C05800; color: #FDFBD4;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 py-3" id="new_password" name="new_password" placeholder="Enter new password" required minlength="8"
                                           style="background: #fffdf4; border-left: 3px solid #C05800 !important; color: #38240D;">
                                    <button type="button" class="password-toggle-btn" data-target="new_password" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-semibold" style="color: #38240D;">
                                    Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group password-toggle-group">
                                    <span class="input-group-text border-0" style="background: #C05800; color: #FDFBD4;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 py-3" id="confirm_password" name="confirm_password" placeholder="Re-enter new password" required minlength="8"
                                           style="background: #fffdf4; border-left: 3px solid #C05800 !important; color: #38240D;">
                                    <button type="button" class="password-toggle-btn" data-target="confirm_password" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold"
                                    style="background: linear-gradient(135deg, #C05800, #713600); border: 2px solid #FDFBD4; color: #FDFBD4 !important; letter-spacing: 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.6); font-size: 1.05rem;">
                                Reset Password
                            </button>
                        <?php endif; ?>
                    </form>

                    <div class="text-center mt-4">
                        <a href="login.php" class="text-decoration-none" style="color: #713600; font-weight: 600;">
                            <i class="fas fa-arrow-left me-2"></i>Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .forgot-wrapper {
        background: linear-gradient(135deg, #FDFBD4, #C05800 70%);
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 2px solid #C05800;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 45px rgba(99, 107, 47, 0.15) !important;
    }
    .form-control:focus {
        background: #fff !important;
        box-shadow: 0 0 0 0.2rem rgba(192, 88, 0, 0.25);
        border-color: #C05800 !important;
    }
    .password-toggle-group {
        position: relative;
    }
    .password-toggle-btn {
        border: 0;
        background: transparent;
        color: #713600;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        cursor: pointer;
        padding: 0;
    }
    .password-toggle-btn:focus {
        outline: none;
        color: #C05800;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.password-toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });
    });

    const form = document.getElementById('forgotPasswordForm');
    const stage = form?.getAttribute('data-stage') || 'request';
    const clientAlert = document.getElementById('clientAlert');

    const showClientAlert = (message, type = 'danger') => {
        if (!clientAlert) return;
        clientAlert.textContent = message;
        clientAlert.classList.remove('d-none', 'alert-danger', 'alert-success', 'alert-info');
        clientAlert.classList.add(`alert-${type}`);
    };

    const hideClientAlert = () => {
        if (!clientAlert) return;
        clientAlert.classList.add('d-none');
        clientAlert.textContent = '';
    };

    if (form) {
        form.addEventListener('submit', (event) => {
            hideClientAlert();
            if (stage === 'request') {
                const emailInput = document.getElementById('email');
                if (!emailInput) return;
                const email = emailInput.value.trim();
                if (!email) {
                    event.preventDefault();
                    showClientAlert('Please enter your registered email address.', 'danger');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    event.preventDefault();
                    showClientAlert('Please enter a valid email address.', 'danger');
                }
            } else if (stage === 'verify') {
                const otpInput = document.getElementById('otp');
                const newPasswordInput = document.getElementById('new_password');
                const confirmPasswordInput = document.getElementById('confirm_password');

                if (!otpInput || !newPasswordInput || !confirmPasswordInput) return;

                const otp = otpInput.value.trim();
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (!/^\d{6}$/.test(otp)) {
                    event.preventDefault();
                    showClientAlert('Please enter a valid 6-digit OTP.', 'danger');
                    return;
                }

                if (newPassword.length < 8) {
                    event.preventDefault();
                    showClientAlert('Password must be at least 8 characters long.', 'danger');
                    return;
                }

                if (newPassword !== confirmPassword) {
                    event.preventDefault();
                    showClientAlert('Passwords do not match. Please re-enter.', 'danger');
                }
            }
        });
    }
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
