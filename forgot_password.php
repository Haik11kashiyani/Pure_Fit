<?php
ob_start();
include 'connection.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    if (!$email) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if email exists in database
        $stmt = mysqli_prepare($conn, "SELECT user_id, first_name, is_email_verified FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $user_id, $first_name, $is_email_verified);
            mysqli_stmt_fetch($stmt);
            
            if (!$is_email_verified) {
                $error = 'Your email is not verified. Please verify your email first.';
            } else {
                // Generate 6-digit verification code
                $verification_code = sprintf('%06d', mt_rand(0, 999999));
                $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                // Store verification code in database
                $update_stmt = mysqli_prepare($conn, "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($update_stmt, 'ssi', $verification_code, $expiry_time, $user_id);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    // Send email with verification code
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
                        $mail->Subject = 'Password Reset Verification Code';
                        $mail->Body = "
                            <div style='font-family: 'Inter', sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                                <div style='background: linear-gradient(135deg, #18181b, #27272a); padding: 30px; text-align: center; border-radius: 16px 16px 0 0;'>
                                    <h1 style='color: #ffffff; margin: 0; font-family: serif; font-size: 28px; text-transform: uppercase; letter-spacing: 2px;'>Pure Fit</h1>
                                </div>
                                <div style='background: #ffffff; padding: 40px; border-radius: 0 0 16px 16px; border: 1px solid #e5e5e5; border-top: none;'>
                                    <h2 style='color: #18181b; margin-top: 0;'>Password Reset Request</h2>
                                    <p style='color: #4b5563; font-size: 16px; line-height: 1.5;'>Hi $first_name,</p>
                                    <p style='color: #4b5563; font-size: 16px; line-height: 1.5;'>A request has been initiated to reset your credentials. Utilize the code below to proceed:</p>
                                    <div style='background: #f4f4f5; color: #eab308; font-size: 32px; font-weight: bold; padding: 24px; text-align: center; border-radius: 12px; margin: 30px 0; letter-spacing: 8px; border: 1px solid #e5e5e5;'>
                                        $verification_code
                                    </div>
                                    <p style='color: #6b7280; font-size: 14px;'>This code will expire in 15 minutes.</p>
                                    <p style='color: #6b7280; font-size: 14px;'>If you didn't request this, please disregard this transmission.</p>
                                </div>
                            </div>
                        ";
                        
                        $mail->send();
                        
                        // Store email in session for verification page
                        $_SESSION['reset_email'] = $email;
                        
                        // Show success message and redirect
                        $success = 'Verification code sent! Check your inbox.';
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'verify_reset_code.php';
                            }, 2000);
                        </script>";
                    } catch (Exception $e) {
                        $error = 'Failed to send verification email: ' . $mail->ErrorInfo;
                    }
                } else {
                    $error = 'System error. Please retry.';
                }
                mysqli_stmt_close($update_stmt);
            }
        } else {
            // Email not found - For security, we can either clear $error or show generic message
            // or for UX showing specific error. Let's show specific for now as per original
            $error = 'Email address not found in our records.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden text-center">
    <!-- Fluid Background -->
    <div class="fixed inset-0 pointer-events-none z-0">
         <div class="liquid-blob w-[500px] h-[500px] top-[15%] right-[15%] opacity-20"></div>
    </div>

    <div class="max-w-md w-full relative z-10 text-left">
        <div class="glass-panel p-10 backdrop-blur-xl border border-white/5 relative overflow-hidden shadow-2xl" x-data="{ submitting: false }">
            
            <div class="text-center mb-8">
                 <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/5">
                    <i class="fas fa-lock-open text-primary text-xl"></i>
                </div>
                <h1 class="text-2xl font-display font-black text-light uppercase tracking-tighter mb-2">
                    Access Recovery
                </h1>
                <p class="text-gray-400 text-[10px] font-mono uppercase tracking-widest">Restore secure entry to the archive</p>
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
                     <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-2">Email Address</label>
                    <div class="relative group">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-500 text-xs group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                               class="clay-card w-full pl-10 pr-4 py-4 bg-dark/20 text-light focus:text-white transition-all placeholder-gray-600 text-sm shadow-inner border-transparent focus:border-primary/30 outline-none rounded-xl" 
                               placeholder="name@example.com"
                               value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    </div>
                </div>

                <button type="submit" 
                        class="clay-btn w-full flex justify-center py-4 shadow-lg hover:shadow-glow transition-all"
                        :disabled="submitting">
                    <span x-show="!submitting">Initiate Reset Protocol</span>
                    <span x-show="submitting" x-cloak><i class="fas fa-circle-notch fa-spin mr-2"></i> Transmitting...</span>
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <a href="login.php" class="text-xs font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i> Return to Login
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
