<?php
ob_start();
include 'connection.php';
if (session_status() == PHP_SESSION_NONE) session_start();

// Redirect to home if not logged in
if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$messageType = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All password fields are required.";
        $messageType = 'danger';
    } else if ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
        $messageType = 'danger';
    } else if (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = 'danger';
    } else {
        // Verify current password
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            if (password_verify($current_password, $row['password'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");
                mysqli_stmt_bind_param($update_stmt, 'si', $hashed_password, $_SESSION['user_id']);
                
                if (mysqli_stmt_execute($update_stmt)) {
                    $message = "Password changed successfully!";
                    $messageType = 'success';
                } else {
                    $message = "Error changing password.";
                    $messageType = 'danger';
                }
                mysqli_stmt_close($update_stmt);
            } else {
                $message = "Current password is incorrect.";
                $messageType = 'danger';
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $message = "First name, last name and email are required.";
        $messageType = 'danger';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = 'danger';
    } else {
        // Check if email is already taken by another user
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ? AND user_id != ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'si', $email, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $message = "Email already registered to another account.";
            $messageType = 'danger';
        } else {
            // Update user info
            $stmt = mysqli_prepare($conn, 
                "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?, 
                    phone = ?, 
                    address = ?
                WHERE user_id = ?"
            );
            mysqli_stmt_bind_param($stmt, 'sssssi', 
                $first_name, 
                $last_name, 
                $email, 
                $phone, 
                $address,
                $_SESSION['user_id']
            );
            
            if (mysqli_stmt_execute($stmt)) {
                $message = "Profile updated successfully!";
                $messageType = 'success';
            } else {
                $message = "Error updating profile.";
                $messageType = 'danger';
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch user data
$user_id = (int)$_SESSION['user_id'];
$user = null;
$stmt = mysqli_prepare($conn, "SELECT username, email, first_name, last_name, phone, address, created_at FROM users WHERE user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($res && $row = mysqli_fetch_assoc($res)) {
    $user = $row;
}
mysqli_stmt_close($stmt);

// Fetch user orders
$orders_query = "SELECT o.*, COUNT(oi.order_item_id) as item_count 
                 FROM orders o 
                 LEFT JOIN order_items oi ON o.order_id = oi.order_id 
                 WHERE o.user_id = ? 
                 GROUP BY o.order_id 
                 ORDER BY o.created_at DESC";
$orders_stmt = mysqli_prepare($conn, $orders_query);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = mysqli_stmt_get_result($orders_stmt);
$orders = [];
while ($order = mysqli_fetch_assoc($orders_result)) {
    $orders[] = $order;
}
mysqli_stmt_close($orders_stmt);

// Check for active tab and order_id from URL
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'personal-info';
$highlight_order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="min-h-screen bg-paper dark:bg-ink py-24 transition-colors duration-500" x-data="{ 
    activeTab: '<?php echo $active_tab; ?>',
    init() {
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('tab')) {
            this.activeTab = urlParams.get('tab');
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl font-serif italic text-ink dark:text-paper uppercase tracking-tighter mb-2">My Profile</h1>
            <p class="text-ink/60 dark:text-paper/60 font-sans text-sm">Control center for your account.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-silver/30 dark:bg-white/5 border border-ink/5 dark:border-white/5 p-6 sticky top-32">
                    <div class="text-center mb-8 pb-8 border-b border-ink/10 dark:border-white/10">
                         <div class="w-20 h-20 bg-ink text-paper dark:bg-paper dark:text-ink rounded-full mx-auto flex items-center justify-center text-3xl font-bold mb-4 border border-ink/10 shadow-lg">
                            <?php echo strtoupper(substr($user['first_name'] ?? 'U', 0, 1)); ?>
                        </div>
                        <h2 class="text-xl font-bold text-ink dark:text-paper uppercase tracking-wide"><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></h2>
                        <p class="text-xs text-ink/60 dark:text-paper/60 font-sans mt-1"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                         <div class="mt-4 text-[10px] text-ink/40 dark:text-paper/40 uppercase tracking-widest">
                             Since <?php echo htmlspecialchars(date('M Y', strtotime($user['created_at'] ?? 'now'))); ?>
                         </div>
                    </div>

                    <nav class="space-y-1">
                        <button @click="activeTab = 'personal-info'" 
                                :class="activeTab === 'personal-info' ? 'bg-ink text-paper dark:bg-paper dark:text-ink font-bold' : 'text-ink/60 dark:text-paper/60 hover:text-ink dark:hover:text-paper hover:bg-ink/5 dark:hover:bg-white/5'"
                                class="w-full flex items-center px-4 py-3 text-xs uppercase tracking-widest transition-all mb-1 rounded-sm">
                            <i class="fas fa-user w-6 text-center mr-3"></i>
                            Personal Info
                        </button>
                        <button @click="activeTab = 'orders'" 
                                :class="activeTab === 'orders' ? 'bg-ink text-paper dark:bg-paper dark:text-ink font-bold' : 'text-ink/60 dark:text-paper/60 hover:text-ink dark:hover:text-paper hover:bg-ink/5 dark:hover:bg-white/5'"
                                class="w-full flex items-center px-4 py-3 text-xs uppercase tracking-widest transition-all mb-1 rounded-sm">
                            <i class="fas fa-box w-6 text-center mr-3"></i>
                            Orders
                        </button>
                         <button @click="activeTab = 'addresses'" 
                                :class="activeTab === 'addresses' ? 'bg-ink text-paper dark:bg-paper dark:text-ink font-bold' : 'text-ink/60 dark:text-paper/60 hover:text-ink dark:hover:text-paper hover:bg-ink/5 dark:hover:bg-white/5'"
                                class="w-full flex items-center px-4 py-3 text-xs uppercase tracking-widest transition-all mb-1 rounded-sm">
                            <i class="fas fa-map w-6 text-center mr-3"></i>
                            Address
                        </button>
                        <button @click="activeTab = 'change-password'" 
                                :class="activeTab === 'change-password' ? 'bg-ink text-paper dark:bg-paper dark:text-ink font-bold' : 'text-ink/60 dark:text-paper/60 hover:text-ink dark:hover:text-paper hover:bg-ink/5 dark:hover:bg-white/5'"
                                class="w-full flex items-center px-4 py-3 text-xs uppercase tracking-widest transition-all mb-1 rounded-sm">
                            <i class="fas fa-key w-6 text-center mr-3"></i>
                            Password
                        </button>
                         <button @click="activeTab = 'language'" 
                                :class="activeTab === 'language' ? 'bg-ink text-paper dark:bg-paper dark:text-ink font-bold' : 'text-ink/60 dark:text-paper/60 hover:text-ink dark:hover:text-paper hover:bg-ink/5 dark:hover:bg-white/5'"
                                class="w-full flex items-center px-4 py-3 text-xs uppercase tracking-widest transition-all mb-1 rounded-sm">
                            <i class="fas fa-globe w-6 text-center mr-3"></i>
                            Language
                        </button>
                        
                        <div class="pt-6 mt-6 border-t border-ink/10 dark:border-white/10">
                             <a href="logout.php" class="w-full flex items-center px-4 py-3 text-xs font-bold text-red-500 uppercase tracking-widest hover:text-red-600 transition-all">
                                <i class="fas fa-sign-out-alt w-6 text-center mr-3"></i>
                                Sign Out
                            </a>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-silver/30 dark:bg-white/5 border border-ink/5 dark:border-white/5 p-8 min-h-[500px] relative">
                    
                     <!-- Alerts -->
                    <?php if (!empty($message)): ?>
                    <div class="mb-8 rounded-none border-l-2 p-4 <?php echo $messageType === 'success' ? 'border-green-500 bg-green-500/10 text-green-600 dark:text-green-400' : 'border-red-500 bg-red-500/10 text-red-600 dark:text-red-400'; ?> flex items-start">
                         <i class="fas <?php echo $messageType === 'success' ? 'fa-check' : 'fa-exclamation-triangle'; ?> mt-0.5 mr-3 flex-shrink-0 text-sm"></i>
                         <div class="text-xs font-bold uppercase tracking-wide"><?php echo $message; ?></div>
                    </div>
                    <?php endif; ?>

                    <!-- Personal Info Tab -->
                    <div x-show="activeTab === 'personal-info'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-xl font-bold font-serif text-ink dark:text-paper mb-8 uppercase tracking-wide">Personal Details</h3>
                        <form method="POST" action="" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="update_profile" value="1">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">First Name</label>
                                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required
                                           class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Last Name</label>
                                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required
                                           class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required
                                       class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                       class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                            </div>

                            <button type="submit" class="px-8 py-4 bg-ink text-paper dark:bg-paper dark:text-ink font-bold uppercase tracking-widest text-xs hover:opacity-80 transition-opacity mt-4">
                                Save Changes
                            </button>
                        </form>
                    </div>

                    <!-- Orders Tab -->
                    <div x-show="activeTab === 'orders'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                         <h3 class="text-xl font-bold font-serif text-ink dark:text-paper mb-8 uppercase tracking-wide">Order History</h3>
                         
                         <?php if (count($orders) > 0): ?>
                            <div class="space-y-6">
                                <?php foreach ($orders as $order): 
                                    $status_colors = [
                                        'pending' => 'text-yellow-600 dark:text-yellow-400 border-yellow-400',
                                        'confirmed' => 'text-green-600 dark:text-green-400 border-green-400',
                                        'processing' => 'text-blue-600 dark:text-blue-400 border-blue-400',
                                        'shipped' => 'text-indigo-600 dark:text-indigo-400 border-indigo-400',
                                        'delivered' => 'text-ink dark:text-paper border-ink dark:border-paper',
                                        'cancelled' => 'text-red-600 dark:text-red-400 border-red-400'
                                    ];
                                    $status_class = $status_colors[$order['order_status']] ?? 'text-gray-400 border-gray-400';
                                    $is_highlighted = ($highlight_order_id == $order['order_id']);
                                ?>
                                <div class="bg-white border <?php echo $is_highlighted ? 'border-ink dark:border-paper' : 'border-ink/5 dark:border-white/5'; ?> dark:bg-white/5 p-6 transition-all hover:shadow-md group">
                                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                                        <div>
                                            <h4 class="font-bold text-ink dark:text-paper text-lg font-serif uppercase tracking-wide">Order #<?php echo sprintf('%02d', $order['order_id'] % 100); ?></h4>
                                            <p class="text-xs text-ink/50 dark:text-paper/50 font-sans mt-1"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                                        </div>
                                        <span class="px-3 py-1 border <?php echo $status_class; ?> text-[10px] font-bold uppercase tracking-widest bg-transparent">
                                            <?php echo ucfirst($order['order_status']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                                        <div>
                                            <span class="block text-ink/40 dark:text-paper/40 text-[10px] uppercase tracking-widest mb-1">Total</span>
                                            <span class="font-sans text-ink dark:text-paper font-bold">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                                        </div>
                                         <div>
                                            <span class="block text-ink/40 dark:text-paper/40 text-[10px] uppercase tracking-widest mb-1">Items</span>
                                            <span class="font-sans text-ink dark:text-paper"><?php echo $order['item_count']; ?></span>
                                        </div>
                                         <div>
                                            <span class="block text-ink/40 dark:text-paper/40 text-[10px] uppercase tracking-widest mb-1">Payment</span>
                                            <span class="font-sans text-ink dark:text-paper"><?php echo $order['payment_method_id'] == 1 ? 'COD' : 'Online'; ?></span>
                                        </div>
                                         <div>
                                            <span class="block text-ink/40 dark:text-paper/40 text-[10px] uppercase tracking-widest mb-1">Payment Status</span>
                                            <span class="font-bold text-xs uppercase tracking-wide <?php echo $order['payment_status'] == 'completed' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'; ?>">
                                                <?php echo ucfirst($order['payment_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end pt-4 border-t border-ink/5 dark:border-white/5">
                                        <a href="order_confirmation.php?order_id=<?php echo $order['order_id']; ?>" class="text-xs font-bold text-ink dark:text-paper hover:underline uppercase tracking-widest transition-colors flex items-center">
                                            View Details <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                         <?php else: ?>
                            <div class="text-center py-24 border border-dashed border-ink/10 dark:border-white/10">
                                <i class="fas fa-box-open text-4xl text-ink/20 dark:text-paper/20 mb-4"></i>
                                <h4 class="text-xl font-bold text-ink dark:text-paper uppercase tracking-wide">No Orders Found</h4>
                                <p class="text-ink/60 dark:text-paper/60 mt-2 mb-8 font-light">The void awaits your selection.</p>
                                <a href="products.php" class="px-8 py-3 bg-ink text-paper dark:bg-paper dark:text-ink font-bold uppercase tracking-widest text-xs hover:opacity-80 transition-opacity">
                                    Start Shopping
                                </a>
                            </div>
                         <?php endif; ?>
                    </div>

                    <!-- Addresses Tab -->
                    <div x-show="activeTab === 'addresses'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-xl font-bold font-serif text-ink dark:text-paper mb-8 uppercase tracking-wide">Saved Address</h3>
                         <form method="POST" action="" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="update_profile" value="1">
                            
                            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">

                             <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Complete Address</label>
                                <textarea name="address" rows="5" 
                                          class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors leading-relaxed"
                                          placeholder="Enter your street, area, city, and pincode..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="px-8 py-4 bg-ink text-paper dark:bg-paper dark:text-ink font-bold uppercase tracking-widest text-xs hover:opacity-80 transition-opacity mt-4">
                                Update Address
                            </button>
                        </form>
                    </div>

                     <!-- Change Password Tab -->
                    <div x-show="activeTab === 'change-password'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                         <h3 class="text-xl font-bold font-serif text-ink dark:text-paper mb-8 uppercase tracking-wide">Change Password</h3>
                         <form method="POST" action="" class="space-y-6 max-w-lg">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="change_password" value="1">

                             <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Current Password</label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                            </div>

                             <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">New Password</label>
                                <input type="password" name="new_password" required minlength="6"
                                       class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wider">Min 6 characters</p>
                            </div>

                             <div>
                                <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Confirm New Password</label>
                                <input type="password" name="confirm_password" required
                                       class="w-full px-4 py-3 bg-transparent border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:border-ink dark:focus:border-paper focus:outline-none transition-colors">
                            </div>

                            <button type="submit" class="px-8 py-4 bg-ink text-paper dark:bg-paper dark:text-ink font-bold uppercase tracking-widest text-xs hover:opacity-80 transition-opacity mt-4">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Language Tab -->
                    <div x-show="activeTab === 'language'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <h3 class="text-xl font-bold font-serif text-ink dark:text-paper mb-8 uppercase tracking-wide">Language</h3>
                        
                         <div class="max-w-md">
                             <label class="block text-xs font-bold text-ink/60 dark:text-paper/60 uppercase tracking-widest mb-2">Select Language</label>
                             <div class="relative group">
                                 <select id="languageSelector" class="block w-full px-4 py-3 bg-paper dark:bg-ink border border-ink/20 dark:border-white/20 text-ink dark:text-paper focus:outline-none focus:border-ink dark:focus:border-paper text-sm appearance-none cursor-pointer hover:border-ink/40 dark:hover:border-white/40 transition-colors">
                                    <option value="">Default (English)</option>
                                    <option value="en">English</option>
                                    <option value="hi">हिंदी (Hindi)</option>
                                    <option value="gu">ગુજરાતી (Gujarati)</option>
                                    <option value="es">Español (Spanish)</option>
                                    <option value="fr">Français (French)</option>
                                    <option value="de">Deutsch (German)</option>
                                    <option value="ar">العربية (Arabic)</option>
                                    <option value="zh-CN">中文 (Chinese)</option>
                                    <option value="ja">日本語 (Japanese)</option>
                                    <option value="ko">한국어 (Korean)</option>
                                 </select>
                                 <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-ink/40 dark:text-paper/40 group-hover:text-ink dark:group-hover:text-paper transition-colors">
                                     <i class="fas fa-chevron-down text-xs"></i>
                                 </div>
                             </div>
                         </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function triggerGoogleTranslate(lang) {
        if (!lang || lang === '') { lang = 'en'; }
        var googleSelect = document.querySelector('.goog-te-combo');
        if (googleSelect) {
            googleSelect.value = lang;
            googleSelect.dispatchEvent(new Event('change'));
        } else {
            var cookieName = 'googtrans';
            var cookieValue = '/en/' + lang;
            document.cookie = cookieName + '=' + cookieValue + ';path=/';
            window.location.hash = 'googtrans(en|' + lang + ')';
            window.location.reload();
        }
    }
    
    document.getElementById('languageSelector').addEventListener('change', function() {
        var lang = this.value;
        if (lang) { triggerGoogleTranslate(lang); }
    });
    
    window.addEventListener('load', function() {
        var cookies = document.cookie.split(';');
        var currentLang = 'en';
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf('googtrans=') === 0) {
                var value = cookie.substring('googtrans='.length);
                var parts = value.split('/');
                if (parts.length >= 3) { currentLang = parts[2]; }
            }
        }
        var hash = window.location.hash;
        if (hash && hash.indexOf('googtrans') > -1) {
            var lang = hash.split('|')[1];
            if (lang) { currentLang = lang.replace(')', ''); }
        }
        if (currentLang && currentLang !== 'en') {
            document.getElementById('languageSelector').value = currentLang;
        }
    });
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
