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
                    $message = "Error changing password. Please try again.";
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
        $message = "First name, last name and email are required fields.";
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
            $message = "This email is already registered to another account.";
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
                $message = "Error updating profile. Please try again.";
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
mysqli_stmt_bind_param($orders_stmt, 'i', $user_id);
mysqli_stmt_execute($orders_stmt);
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
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5 mt-5 pt-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127;  letter-spacing: 2px;">
                    My Profile
                </h1>
                <p class="lead" style="color: #636B2F; ">
                    Manage your account settings and preferences
                </p>
            </div>

            <div class="row g-5">
                <!-- Profile Sidebar -->
                <div class="col-12 col-lg-3">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4 text-center">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; ">
                                <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?>
                            </h5>
                            <p class="mb-3" style="color: #636B2F; font-size: 0.9rem;">
                                <i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email'] ?? ''); ?><br>
                                <?php if (!empty($user['phone'])): ?>
                                <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($user['phone'] ?? ''); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($user['address'])): ?>
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <?php
                                    // handle literal "\n" sequences in stored addresses by converting them to real newlines
                                    $profile_addr_raw = $user['address'];
                                    $profile_addr_raw = str_replace('\\n', "\n", $profile_addr_raw);
                                ?>
                                <?php echo nl2br(htmlspecialchars($profile_addr_raw ?? '')); ?><br>
                                <?php endif; ?>
                                <i class="fas fa-calendar me-2"></i>Member since <?php echo htmlspecialchars(date('F j, Y', strtotime($user['created_at'] ?? 'now'))); ?>
                            </p>

                            <!-- Profile Navigation -->
                            <div class="profile-nav">
                                <button class="btn w-100 mb-2 profile-nav-btn active" data-target="personal-info" 
                                        style="background: #636B2F; color: white; border: none;  transition: all 0.3s ease;">
                                    <i class="fas fa-user me-2"></i>Personal Info
                                </button>
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="orders" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-shopping-bag me-2"></i>Orders
                                </button>
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="addresses" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                                </button>
                              
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="change-password" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </button>
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="language" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-globe me-2"></i>Language
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="col-12 col-lg-9">
                    <!-- Personal Info Section -->
                    <div class="profile-section active" id="personal-info">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Personal Information
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                        <?php echo htmlspecialchars($message); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="update_profile" value="1">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="first_name" class="form-label fw-semibold" style="color: #3D4127; ">
                                                First Name
                                            </label>
                                            <input type="text" class="form-control border-0 py-3" id="first_name" name="first_name" 
                                                   value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required
                                                   style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                        </div>
                                        
                                        <div class="col-md-6 mb-4">
                                            <label for="last_name" class="form-label fw-semibold" style="color: #3D4127; ">
                                                Last Name
                                            </label>
                                            <input type="text" class="form-control border-0 py-3" id="last_name" name="last_name" 
                                                   value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required
                                                   style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Email Address
                                        </label>
                                        <input type="email" class="form-control border-0 py-3" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Phone Number
                                        </label>
                                        <input type="tel" class="form-control border-0 py-3" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>

                                    <div class="mb-4">
                                        <label for="address" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Address
                                        </label>
                                        <textarea class="form-control border-0 py-3" id="address" name="address" rows="2"
                                                style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; letter-spacing: 1px;">
                                        Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="profile-section" id="orders" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127;">
                                    <i class="fas fa-shopping-bag me-2"></i>Order History
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <!-- Flash messages handled centrally in master_layout.php -->
                                
                                <?php if (count($orders) > 0): ?>
                                    <?php foreach ($orders as $order): 
                                        $status_colors = [
                                            'pending' => 'background: #ffc107; color: #856404;',
                                            'confirmed' => 'background: #28a745; color: white;',
                                            'processing' => 'background: #17a2b8; color: white;',
                                            'shipped' => 'background: #007bff; color: white;',
                                            'delivered' => 'background: #636B2F; color: white;',
                                            'cancelled' => 'background: #dc3545; color: white;'
                                        ];
                                        $status_style = $status_colors[$order['order_status']] ?? 'background: #D4DE95; color: #3D4127;';
                                        $is_highlighted = ($highlight_order_id == $order['order_id']);
                                    ?>
                                    <div class="order-item mb-4 p-3 rounded <?php echo $is_highlighted ? 'border border-success' : ''; ?>" style="background: <?php echo $is_highlighted ? '#d4edda' : '#f8f9fa'; ?>; border-left: 4px solid <?php echo $is_highlighted ? '#28a745' : '#D4DE95'; ?> !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1 fw-bold" style="color: #3D4127;">
                                                    <?php $shortOrder = sprintf('%02d', $order['order_id'] % 100); ?>
                                                    Order <?php echo $shortOrder; ?>
                                                </h6>
                                                <small style="color: #636B2F;">
                                                    <i class="fas fa-calendar me-1"></i><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                                                </small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2" style="<?php echo $status_style; ?>">
                                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i><?php echo ucfirst($order['order_status']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="row g-2 mb-3">
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block">Total Amount</small>
                                                <strong style="color: #636B2F;">₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block">Items</small>
                                                <strong style="color: #636B2F;"><?php echo $order['item_count']; ?> item(s)</strong>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block">Payment</small>
                                                <strong style="color: #636B2F;"><?php echo $order['payment_method_id'] == 1 ? 'COD' : 'Online'; ?></strong>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <small class="text-muted d-block">Payment Status</small>
                                                <strong style="color: <?php echo $order['payment_status'] == 'completed' ? '#28a745' : '#ffc107'; ?>;">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </strong>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="order_confirmation.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-sm px-3 py-2 rounded-pill" style="background: #636B2F; color: white; border: none;">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            <?php if ($order['order_status'] == 'confirmed' || $order['order_status'] == 'processing'): ?>
                                            <small class="text-muted">
                                                <i class="fas fa-truck me-1"></i>Expected delivery in 3-5 days
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-shopping-bag fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                                        <h5 class="text-muted">No Orders Yet</h5>
                                        <p class="text-muted">You haven't placed any orders yet.</p>
                                        <a href="products.php" class="btn px-4 py-2 rounded-pill text-white fw-bold mt-3" style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none;">
                                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Section -->
                    <div class="profile-section" id="addresses" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Address
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="" id="address-form" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="update_profile" value="1">
                                    
                                    <div class="mb-4">
                                        <label for="address_edit" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Your Address
                                        </label>
                                        <textarea class="form-control border-0 py-3" id="address_edit" name="address" rows="4"
                                                style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease; resize: none;"
                                                placeholder="Enter your complete address..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; letter-spacing: 1px;">
                                        Update Address
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="profile-section" id="change-password" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127;">
                                    <i class="fas fa-lock me-2"></i>Change Password
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <?php if (!empty($message) && isset($_POST['change_password'])): ?>
                                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                                        <?php echo htmlspecialchars($message); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="" novalidate>
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    <input type="hidden" name="change_password" value="1">
                                    
                                    <div class="mb-4">
                                        <label for="current_password" class="form-label fw-semibold" style="color: #3D4127;">
                                            <i class="fas fa-key me-2"></i>Current Password *
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="current_password" name="current_password" required
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;"
                                               placeholder="Enter your current password">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="new_password" class="form-label fw-semibold" style="color: #3D4127;">
                                            <i class="fas fa-lock me-2"></i>New Password *
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="new_password" name="new_password" required
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;"
                                               placeholder="Enter new password (min 6 characters)">
                                        <small class="text-muted">Password must be at least 6 characters long</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label fw-semibold" style="color: #3D4127;">
                                            <i class="fas fa-check-circle me-2"></i>Confirm New Password *
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="confirm_password" name="confirm_password" required
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;"
                                               placeholder="Re-enter new password">
                                    </div>
                                    
                                    <div class="alert alert-info" style="background: rgba(212, 222, 149, 0.2); border: 2px solid #D4DE95; color: #3D4127;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Password Tips:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>Use at least 6 characters</li>
                                            <li>Mix uppercase and lowercase letters</li>
                                            <li>Include numbers and special characters</li>
                                        </ul>
                                    </div>
                                    
                                    <button type="submit" class="btn px-5 py-3 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; letter-spacing: 1px;">
                                        <i class="fas fa-save me-2"></i>Change Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Language Section -->
                    <div class="profile-section" id="language" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127;">
                                    <i class="fas fa-globe me-2"></i>Language Settings
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-info mb-4" style="background: rgba(212, 222, 149, 0.2); border: 2px solid #D4DE95; color: #3D4127;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Select your preferred language. The entire website will be translated automatically.
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-8">
                                        <label class="form-label fw-bold mb-3" style="color: #3D4127; font-size: 1.1rem;">
                                            <i class="fas fa-language me-2"></i>Choose Language:
                                        </label>
                                        
                                        <!-- Google Translate Dropdown -->
                                        <div class="language-selector-wrapper p-4 rounded-3" style="background: #f8f9fa; border: 2px solid #D4DE95;">
                                            <select id="languageSelector" class="form-select form-select-lg" style="border: 2px solid #636B2F; padding: 15px; font-size: 16px; color: #3D4127; background: white;">
                                                <option value="">Select Language / ભાષા પસંદ કરો</option>
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
                                        </div>
                                        
                                        <script>
                                            // Function to trigger Google Translate
                                            function triggerGoogleTranslate(lang) {
                                                if (!lang || lang === '') {
                                                    lang = 'en'; // Default to English
                                                }
                                                
                                                // Method 1: Try to find and trigger the Google Translate dropdown
                                                var googleSelect = document.querySelector('.goog-te-combo');
                                                if (googleSelect) {
                                                    googleSelect.value = lang;
                                                    googleSelect.dispatchEvent(new Event('change'));
                                                    
                                                    // Show success message
                                                    showLanguageNotification('Language changed to ' + lang.toUpperCase());
                                                } else {
                                                    // Method 2: Use cookie method (more reliable)
                                                    var cookieName = 'googtrans';
                                                    var cookieValue = '/en/' + lang;
                                                    document.cookie = cookieName + '=' + cookieValue + ';path=/';
                                                    
                                                    // Also set the hash
                                                    window.location.hash = 'googtrans(en|' + lang + ')';
                                                    
                                                    // Reload the page to apply translation
                                                    window.location.reload();
                                                }
                                            }
                                            
                                            // Show notification
                                            function showLanguageNotification(message) {
                                                var notification = document.createElement('div');
                                                notification.className = 'alert alert-success position-fixed';
                                                notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
                                                notification.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + message;
                                                document.body.appendChild(notification);
                                                
                                                setTimeout(function() {
                                                    notification.style.opacity = '0';
                                                    notification.style.transition = 'opacity 0.3s';
                                                    setTimeout(function() {
                                                        notification.remove();
                                                    }, 300);
                                                }, 2000);
                                            }
                                            
                                            // Language selector change event
                                            document.getElementById('languageSelector').addEventListener('change', function() {
                                                var lang = this.value;
                                                if (lang) {
                                                    triggerGoogleTranslate(lang);
                                                }
                                            });
                                            
                                            // Check current language on load and set selector
                                            window.addEventListener('load', function() {
                                                // Check cookie first
                                                var cookies = document.cookie.split(';');
                                                var currentLang = 'en';
                                                
                                                for (var i = 0; i < cookies.length; i++) {
                                                    var cookie = cookies[i].trim();
                                                    if (cookie.indexOf('googtrans=') === 0) {
                                                        var value = cookie.substring('googtrans='.length);
                                                        var parts = value.split('/');
                                                        if (parts.length >= 3) {
                                                            currentLang = parts[2];
                                                        }
                                                    }
                                                }
                                                
                                                // Also check hash
                                                var hash = window.location.hash;
                                                if (hash && hash.indexOf('googtrans') > -1) {
                                                    var lang = hash.split('|')[1];
                                                    if (lang) {
                                                        currentLang = lang.replace(')', '');
                                                    }
                                                }
                                                
                                                // Set the selector value
                                                if (currentLang && currentLang !== 'en') {
                                                    document.getElementById('languageSelector').value = currentLang;
                                                }
                                            });
                                        </script>
                                        
                                        <div class="alert alert-success mt-4" style="background: rgba(212, 222, 149, 0.2); border: 2px solid #636B2F; color: #3D4127;">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Available Languages:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>English</li>
                                                <li>हिंदी (Hindi)</li>
                                                <li>ગુજરાતી (Gujarati)</li>
                                                <li>Español (Spanish)</li>
                                                <li>Français (French)</li>
                                                <li>Deutsch (German)</li>
                                                <li>العربية (Arabic)</li>
                                                <li>中文 (Chinese)</li>
                                                <li>日本語 (Japanese)</li>
                                                <li>한국어 (Korean)</li>
                                            </ul>
                                        </div>

                                        <div class="alert alert-warning mt-3" style="background: rgba(255, 193, 7, 0.1); border: 2px solid #ffc107; color: #856404;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Note:</strong> Translation is powered by Google Translate. Some translations may not be perfect.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(99, 107, 47, 0.15) !important;
}

.profile-avatar {
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
    border-color: #636B2F !important;
}

.profile-nav-btn {
    transition: all 0.3s ease;
}

.profile-nav-btn:hover {
    transform: translateX(5px);
}

.profile-nav-btn.active {
    background: #636B2F !important;
    color: white !important;
    border-color: #636B2F !important;
}

.form-control:focus, .form-select:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.order-item, .address-item {
    transition: all 0.3s ease;
}

.order-item:hover, .address-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
}

.form-check-input:checked {
    background-color: #636B2F;
    border-color: #636B2F;
}

.language-card {
    transition: all 0.3s ease;
}

.language-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(99, 107, 47, 0.2);
    border-color: #636B2F !important;
}

/* Animation for page elements */
.container-fluid > * {
    animation: fadeInUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

.container-fluid > *:nth-child(1) { animation-delay: 0.1s; }
.container-fluid > *:nth-child(2) { animation-delay: 0.2s; }

@keyframes fadeInUp {
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
    
    .profile-avatar {
        width: 100px !important;
        height: 100px !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Sync address between forms
document.getElementById('address_edit').addEventListener('input', function() {
    document.getElementById('address').value = this.value;
});

document.getElementById('address').addEventListener('input', function() {
    document.getElementById('address_edit').value = this.value;
});

// Handle address form submission
document.getElementById('address-form').addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('address').value = document.getElementById('address_edit').value;
    document.querySelector('form').submit();
});

// Profile navigation functionality
document.querySelectorAll('.profile-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = this.getAttribute('data-target');
        
        // Hide all profile sections
        document.querySelectorAll('.profile-section').forEach(section => {
            section.style.display = 'none';
        });
        
        // Show target section
        document.getElementById(target).style.display = 'block';
        
        // Update active button
        document.querySelectorAll('.profile-nav-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = '#f8f9fa';
            b.style.color = '#636B2F';
            b.style.borderColor = '#D4DE95';
        });
        
        this.classList.add('active');
        this.style.background = '#636B2F';
        this.style.color = 'white';
        this.style.borderColor = '#636B2F';
    });
});

// Check for active tab from URL
const urlParams = new URLSearchParams(window.location.search);
const activeTab = urlParams.get('tab');
if (activeTab) {
    const targetBtn = document.querySelector(`[data-target="${activeTab}"]`);
    if (targetBtn) {
        targetBtn.click();
    }
}
</script>

<?php
$contant = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Pure Fit</title>
</head>
<body class="profile-page">
<?php
include_once 'master_layout.php';
?>
</body>
</html>
