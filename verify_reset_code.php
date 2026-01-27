<?php
ob_start();
include 'connection.php';
session_start();

// Check if email is set in session
if (!isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit;
}

$email = $_SESSION['reset_email'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_code = isset($_POST['verification_code']) ? trim($_POST['verification_code']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Check if it's a resend request (handled below, but check triggers here if not separated)
    if (!isset($_POST['resend_code'])) {
        if (!$verification_code) {
            $error = 'Please enter the verification code.';
        } elseif (!preg_match('/^\d{6}$/', $verification_code)) {
            $error = 'Verification code must be 6 digits.';
        } elseif (!$new_password) {
            $error = 'Please enter a new password.';
        } elseif (strlen($new_password) < 8) {
            $error = 'Password must be at least 8 characters long.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            // Verify the code and check expiry
            $stmt = mysqli_prepare($conn, "SELECT user_id, reset_token_expiry FROM users WHERE email = ? AND reset_token = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'ss', $email, $verification_code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $user_id, $expiry_time);
                mysqli_stmt_fetch($stmt);
                
                // Check if code is still valid
                if (strtotime($expiry_time) > time()) {
                    // Hash new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Update password and clear reset token
                    $update_stmt = mysqli_prepare($conn, "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?");
                    mysqli_stmt_bind_param($update_stmt, 'si', $hashed_password, $user_id);
                    
                    if (mysqli_stmt_execute($update_stmt)) {
                        // Clear session
                        unset($_SESSION['reset_email']);
                        
                        $success = 'Password reset successful! Redirecting...';
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 2000);
                        </script>";
                    } else {
                        $error = 'Failed to reset password.';
                    }
                    mysqli_stmt_close($update_stmt);
                } else {
                    $error = 'Verification code has expired.';
                }
            } else {
                $error = 'Invalid verification code.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Handle resend code
if (isset($_POST['resend_code'])) {
    // Generate new 6-digit verification code
    $new_verification_code = sprintf('%06d', mt_rand(0, 999999));
    $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // Update verification code in database
    $stmt = mysqli_prepare($conn, "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 'sss', $new_verification_code, $expiry_time, $email);
    
    if (mysqli_stmt_execute($stmt)) {
        // Get user details for email
        $user_stmt = mysqli_prepare($conn, "SELECT first_name FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($user_stmt, 's', $email);
        mysqli_stmt_execute($user_stmt);
        mysqli_stmt_bind_result($user_stmt, $first_name);
        mysqli_stmt_fetch($user_stmt);
        mysqli_stmt_close($user_stmt);
        
        // Send new verification code email
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
        require 'PHPMailer/Exception.php';
        $smtp_config = require 'config/smtp_config.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        try {
            $mail->isSMTP();
            $mail->Host = $smtp_config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtp_config['username'];
            $mail->Password = $smtp_config['password'];
            $mail->SMTPSecure = $smtp_config['encryption'];
            $mail->Port = $smtp_config['port'];
            
            $mail->setFrom($smtp_config['from_email'], $smtp_config['from_name']);
            $mail->addReplyTo($smtp_config['reply_to'], $smtp_config['from_name']);
            $mail->addAddress($email, $first_name);
            
            $mail->isHTML(true);
            $mail->Subject = 'New Password Reset Code';
            $mail->Body = "
                <div style='font-family: 'Inter', sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <div style='background: linear-gradient(135deg, #18181b, #27272a); padding: 30px; text-align: center; border-radius: 16px 16px 0 0;'>
                        <h1 style='color: #ffffff; margin: 0; font-family: serif; font-size: 28px; text-transform: uppercase; letter-spacing: 2px;'>Pure Fit</h1>
                    </div>
                    <div style='background: #ffffff; padding: 40px; border-radius: 0 0 16px 16px; border: 1px solid #e5e5e5; border-top: none;'>
                        <h2 style='color: #18181b; margin-top: 0;'>New Verification Code</h2>
                        <p style='color: #4b5563; font-size: 16px; line-height: 1.5;'>Hi $first_name,</p>
                        <p style='color: #4b5563; font-size: 16px; line-height: 1.5;'>Here is your new verification code:</p>
                        <div style='background: #f4f4f5; color: #eab308; font-size: 32px; font-weight: bold; padding: 24px; text-align: center; border-radius: 12px; margin: 30px 0; letter-spacing: 8px; border: 1px solid #e5e5e5;'>
                            $new_verification_code
                        </div>
                        <p style='color: #6b7280; font-size: 14px;'>This code will expire in 15 minutes.</p>
                    </div>
                </div>
            ";
            
            $mail->send();
            $success = 'New code sent to your email.';
        } catch (Exception $e) {
            $error = 'Failed to send new code.';
        }
    }
    mysqli_stmt_close($stmt);
}
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden text-center">
    <!-- Fluid Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="liquid-blob w-[600px] h-[600px] top-[10%] left-[30%] opacity-20"></div>
    </div>

    <div class="max-w-md w-full relative z-10 text-left">
        <div class="glass-panel p-10 backdrop-blur-xl border border-white/5 relative overflow-hidden shadow-2xl" x-data="{ showPassword: false, showConfirm: false, submitting: false }">
            
            <div class="text-center mb-10">
                <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mx-auto mb-6 clay-card shadow-inner">
                    <i class="fas fa-shield-alt text-3xl text-primary"></i>
                </div>
                <h1 class="text-3xl font-display font-black text-light uppercase tracking-tighter mb-2">
                    Verification
                </h1>
                <p class="text-gray-400 text-xs font-mono uppercase tracking-widest">Enter code sent to <?php echo htmlspecialchars($email); ?></p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="clay-card bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 text-xs uppercase tracking-widest mb-6 flex items-start shadow-inner">
                <i class="fas fa-exclamation-circle mt-0.5 mr-3 flex-shrink-0"></i>
                <div><?php echo $error; ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="clay-card bg-primary/10 border border-primary/20 text-primary px-4 py-3 text-xs uppercase tracking-widest mb-6 flex items-start shadow-inner">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0"></i>
                <div><?php echo $success; ?></div>
            </div>
            <?php endif; ?>

            <form class="space-y-6" action="" method="POST" @submit="submitting = true">
                <div>
                    <label for="verification_code" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-2">6-Digit Code</label>
                    <input type="text" id="verification_code" name="verification_code" required maxlength="6"
                           class="clay-card w-full px-4 py-4 bg-dark/30 text-light focus:text-white transition-all text-center text-2xl tracking-[0.5em] font-mono shadow-inner border-transparent focus:border-primary/50 outline-none rounded-xl"
                           placeholder="000000"
                           value="<?php echo htmlspecialchars($_POST['verification_code'] ?? ''); ?>">
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-2">New Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="new_password" name="new_password" required minlength="8"
                               class="clay-card w-full pl-6 pr-10 py-4 bg-dark/30 text-light focus:text-white transition-all placeholder-gray-600 text-sm shadow-inner border-transparent focus:border-primary/50 outline-none rounded-xl"
                               placeholder="Min 8 characters">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-white transition-colors focus:outline-none">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="confirm_password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-2">Confirm Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="confirm_password" name="confirm_password" required
                               class="clay-card w-full pl-6 pr-10 py-4 bg-dark/30 text-light focus:text-white transition-all placeholder-gray-600 text-sm shadow-inner border-transparent focus:border-primary/50 outline-none rounded-xl"
                               placeholder="Repeat new password">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-white transition-colors focus:outline-none">
                            <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="clay-btn w-full flex justify-center py-4 shadow-lg hover:shadow-glow transition-all"
                        :disabled="submitting">
                    <span x-show="!submitting">Reset Access</span>
                    <span x-show="submitting" x-cloak><i class="fas fa-circle-notch fa-spin mr-2"></i> Verifying...</span>
                </button>
            </form>
            
            <form action="" method="POST" class="mt-8 text-center pt-6 border-t border-white/5">
                <input type="hidden" name="resend_code" value="1">
                <button type="submit" class="text-xs font-bold text-gray-500 hover:text-primary transition-colors uppercase tracking-widest bg-transparent border-none cursor-pointer flex items-center justify-center gap-2 mx-auto">
                    <i class="fas fa-redo-alt"></i> Resend Verification Code
                </button>
            </form>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
