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
                $message = 'Your account has been successfully verified. Access granted.';
                
                // Redirect to login with success message after 3 seconds
                header('refresh:3;url=login.php?verified=1');
            } else {
                $message = 'Verification failed. Contact support.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Pure Fit</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#eab308', 
                        secondary: '#d9f99d',
                        dark: '#09090b',
                        light: '#fafafa',
                        surface: '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'], // Added a techy mono font
                    },
                     animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark min-h-screen font-sans antialiased text-gray-200 flex items-center justify-center p-4">
    
    <div class="relative max-w-lg w-full">
        <!-- Ambient Background -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-primary/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="relative bg-white/5 backdrop-blur-md border border-white/10 p-10 text-center shadow-2xl">
             <div class="mb-8">
                 <h1 class="text-4xl font-display font-black text-light uppercase tracking-tighter">Pure Fit</h1>
                 <div class="h-1 w-12 bg-primary mx-auto mt-4"></div>
             </div>

            <?php if ($success): ?>
                <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full border-2 border-primary/50 text-primary relative shadow-[0_0_30px_rgba(234,179,8,0.3)] animate-pulse-slow">
                     <i class="fas fa-check text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-light uppercase tracking-wide mb-4">Verification Complete</h2>
                <p class="text-gray-400 text-sm font-mono mb-8"><?php echo htmlspecialchars($message); ?></p>
                
                <div class="text-xs text-gray-500 uppercase tracking-widest animate-pulse">
                     <i class="fas fa-circle-notch fa-spin mr-2"></i> Redirecting to login...
                </div>
            <?php else: ?>
                 <div class="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full border-2 border-red-500/50 text-red-500 relative shadow-[0_0_30px_rgba(239,68,68,0.3)]">
                     <i class="fas fa-times text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-light uppercase tracking-wide mb-4">Verification Failed</h2>
                <p class="text-gray-400 text-sm font-mono mb-8"><?php echo htmlspecialchars($message); ?></p>
                
                <div class="flex flex-col gap-4">
                    <a href="register.php" class="px-8 py-3 bg-white text-dark font-bold font-display uppercase tracking-widest text-xs hover:bg-primary transition-colors">
                        Register Again
                    </a>
                    <a href="contact.php" class="text-xs text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
                        Contact Support
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>