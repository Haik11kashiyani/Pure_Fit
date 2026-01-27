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

                $register_success = 'Account created! Check your email (and spam) for the verification link.';
                if (!$mail_sent) {
                    $host = $_SERVER['HTTP_HOST'];
                    $path = rtrim(dirname($_SERVER['REQUEST_URI']), '/\\');
                    $verify_url = "http://" . $host . $path . "/verify.php?token=" . $token;
                    // show fallback link for local testing
                    $register_success .= '<br><strong>SMTP not configured:</strong> <a href="' . $verify_url . '" class="text-primary underline">' . $verify_url . '</a>';
                }
            } else {
                $register_error = 'Registration failed: ' . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="min-h-screen bg-dark flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Ambient Background -->
    <div class="absolute bottom-0 right-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="max-w-2xl w-full relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-display font-black text-light mb-2 uppercase tracking-wide">
                Join the Elite
            </h1>
            <p class="text-gray-400 text-sm font-mono uppercase tracking-widest">Create your account to start shopping</p>
        </div>

        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-10 relative shadow-2xl" x-data="{ showPassword: false, showConfirm: false, submitting: false }">
            
            <?php if (!empty($register_error)): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 text-xs uppercase tracking-widest mb-8 flex items-start">
                <i class="fas fa-exclamation-circle mt-0.5 mr-3 flex-shrink-0"></i>
                <div><?php echo $register_error; ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($register_success)): ?>
            <div class="bg-primary/10 border border-primary/20 text-primary px-4 py-3 text-xs uppercase tracking-widest mb-8 flex items-start">
                <i class="fas fa-check-circle mt-0.5 mr-3 flex-shrink-0"></i>
                <div><?php echo $register_success; ?></div>
            </div>
            <?php endif; ?>

            <?php if (empty($register_success)): ?>
            <form class="space-y-6" action="" method="POST" @submit="submitting = true">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstName" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">First Name</label>
                        <input type="text" name="firstName" required 
                               class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm"
                               value="<?php echo htmlspecialchars($_POST['firstName'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="lastName" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Last Name</label>
                        <input type="text" name="lastName" required 
                               class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm"
                               value="<?php echo htmlspecialchars($_POST['lastName'] ?? ''); ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone (Optional)</label>
                        <input type="tel" name="phone" 
                               class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm"
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required 
                                   class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition-colors focus:outline-none">
                                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="confirmPassword" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Confirm Password</label>
                        <div class="relative">
                            <input :type="showConfirm ? 'text' : 'password'" name="confirmPassword" required 
                                   class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:ring-0 transition-colors placeholder-gray-600 text-sm">
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition-colors focus:outline-none">
                                <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 text-primary bg-transparent border-gray-500 rounded focus:ring-primary focus:ring-offset-dark">
                    </div>
                    <div class="ml-3 text-xs">
                        <label for="terms" class="text-gray-400">
                            I agree to the <a href="#" class="text-primary hover:text-white underline">Terms of Service</a> and <a href="#" class="text-primary hover:text-white underline">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <button type="submit" 
                        class="magnetic-btn w-full flex justify-center py-4 bg-white text-dark font-display font-bold uppercase tracking-widest text-sm hover:bg-primary transition-all disabled:opacity-70 disabled:cursor-not-allowed"
                        :disabled="submitting">
                    <span x-show="!submitting">Create Account</span>
                    <span x-show="submitting" x-cloak><i class="fas fa-spinner fa-spin mr-2"></i> Creating...</span>
                </button>
            </form>
            <?php endif; ?>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    Already have an account? 
                    <a href="login.php" class="font-bold text-light hover:text-primary transition-colors ml-1 uppercase tracking-widest">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
