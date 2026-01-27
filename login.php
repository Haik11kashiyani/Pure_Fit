<?php
ob_start();
include 'connection.php';
session_start();
$login_error = '';
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
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'is_email_verified'");
        $column_exists = mysqli_num_rows($check_column) > 0;
        
        if ($column_exists) {
            $stmt = mysqli_prepare($conn, "SELECT u.user_id, u.password, u.is_active, u.is_email_verified, u.first_name, u.last_name, r.role_name FROM users u INNER JOIN roles r ON u.role_id = r.role_id WHERE u.email = ? LIMIT 1");
        } else {
            $stmt = mysqli_prepare($conn, "SELECT u.user_id, u.password, u.is_active, 1 as is_email_verified, u.first_name, u.last_name, r.role_name FROM users u INNER JOIN roles r ON u.role_id = r.role_id WHERE u.email = ? LIMIT 1");
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
                $login_error = 'Account Check: Inactive.';
            } elseif (!$is_email_verified) {
                $login_error = 'Identity Unverified. <a href="resend_verification.php" class="underline hover:text-black">Resend Signal</a>.';
            } else {
                if ($role_name == 'admin') {
                    $_SESSION['admin_id'] = $user_id;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_name'] = $first_name . ' ' . $last_name;
                    header('Location: admin/index.php');
                    exit;
                } else {
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

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2 bg-white text-black">
    
    <!-- Left: Decorative (Hidden on mobile) -->
    <div class="hidden md:flex bg-gray-100 items-center justify-center relative overflow-hidden text-center p-12">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=2576&auto=format&fit=crop')] bg-cover bg-center grayscale opacity-80 mix-blend-multiply"></div>
        <div class="relative z-10">
            <h2 class="text-7xl font-serif italic text-white mix-blend-difference mb-4">Pure Form.</h2>
            <p class="text-white text-xs font-bold uppercase tracking-[0.3em] opacity-80">Access The Archive</p>
        </div>
    </div>

    <!-- Right: Form -->
    <div class="flex items-center justify-center p-8 md:p-24 relative">
        <div class="w-full max-w-sm">
            
            <div class="mb-16">
                <h1 class="text-4xl font-serif italic mb-2">Identify.</h1>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Login to Account</p>
            </div>

            <?php if (!empty($login_error)): ?>
            <div class="mb-8 text-xs font-bold uppercase tracking-widest text-red-500 border-l-2 border-red-500 pl-4 py-2">
                <?php echo $login_error; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($verified_msg)): ?>
            <div class="mb-8 text-xs font-bold uppercase tracking-widest text-green-600 border-l-2 border-green-600 pl-4 py-2">
                <?php echo $verified_msg; ?>
            </div>
            <?php endif; ?>

            <form class="space-y-12" action="" method="POST">
                <div class="space-y-8">
                    <div class="group relative">
                        <input id="email" name="email" type="email" required 
                               class="w-full py-4 bg-transparent border-b border-gray-300 focus:border-black outline-none transition-colors text-sm placeholder-transparent" 
                               placeholder="Email"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <label for="email" class="absolute left-0 top-0 text-xs font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 pointer-events-none group-focus-within:-translate-y-6 group-focus-within:text-black">Email Identity</label>
                    </div>

                    <div class="group relative">
                        <input id="password" name="password" type="password" required 
                               class="w-full py-4 bg-transparent border-b border-gray-300 focus:border-black outline-none transition-colors text-sm placeholder-transparent" 
                               placeholder="Password">
                         <label for="password" class="absolute left-0 top-0 text-xs font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 pointer-events-none group-focus-within:-translate-y-6 group-focus-within:text-black">Passkey</label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 border-gray-300 text-black focus:ring-black">
                        <label for="remember-me" class="ml-2 block text-xs text-gray-500 uppercase tracking-widest">Remember</label>
                    </div>
                    <a href="forgot_password.php" class="text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-black transition-colors">Lost Key?</a>
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-black text-white text-xs font-bold uppercase tracking-[0.2em] hover:bg-white hover:text-black border border-black transition-colors">
                    Authenticate
                </button>
            </form>
            
            <div class="mt-16 text-center">
                <p class="text-xs text-gray-400 uppercase tracking-widest">
                    No Access? 
                    <a href="register.php" class="text-black ml-2 hover:underline decoration-1 underline-offset-4">Apply for Membership</a>
                </p>
            </div>
        </div>
        
        <a href="index.php" class="absolute top-8 right-8 text-xs font-bold uppercase tracking-widest hover:text-gray-500">Close</a>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>