<?php
session_start();
ob_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items — if specific selected_cart[] were posted (from cart page), use only those ids
$selected_cart_ids = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_cart']) && is_array($_POST['selected_cart'])) {
    // sanitize integers
    foreach ($_POST['selected_cart'] as $id) {
        $iv = (int)$id;
        if ($iv > 0) $selected_cart_ids[] = $iv;
    }
}

if (!empty($selected_cart_ids)) {
    // Build placeholders
    $placeholders = implode(',', array_fill(0, count($selected_cart_ids), '?'));
    $types = str_repeat('i', count($selected_cart_ids));
    $cart_query = "SELECT c.*, p.name, p.price, p.image_path FROM cart c INNER JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? AND p.is_active = 1 AND c.cart_id IN ($placeholders)";
    $cart_stmt = $conn->prepare($cart_query);
    $params = array_merge([$user_id], $selected_cart_ids);
    // dynamically bind
    $bind_names = [];
    $types_all = 'i' . $types; // first param user_id
    $bind_names[] = &$types_all;
    foreach ($params as $k => $v) {
        $bind_names[] = &$params[$k];
    }
    call_user_func_array([$cart_stmt, 'bind_param'], $bind_names);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
} else {
    // default: all cart items for the user
    $cart_query = "SELECT c.*, p.name, p.price, p.image_path 
               FROM cart c 
               INNER JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = ? AND p.is_active = 1";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
}

$cart_items = [];
$subtotal = 0;

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $subtotal += ($item['price'] * $item['quantity']);
}

// If cart is empty, redirect to cart page
if (count($cart_items) === 0) {
    header('Location: cart.php');
    exit;
}

$tax = $subtotal * 0.18;
$total = $subtotal + $tax;

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch user addresses
$addresses_query = "SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
$addresses_stmt = $conn->prepare($addresses_query);
$addresses_stmt->bind_param("i", $user_id);
$addresses_stmt->execute();
$addresses_result = $addresses_stmt->get_result();

$addresses = [];
$default_address = null;

while ($addr = $addresses_result->fetch_assoc()) {
    $addresses[] = $addr;
    if ($addr['is_default'] == 1) {
        $default_address = $addr;
    }
}

$has_addresses = count($addresses) > 0;
?>

<div class="container-fluid py-4 py-md-5" style="padding-top: 80px !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 px-3">
            <!-- Flash messages handled centrally in master_layout.php -->
            
            <!-- Page Header -->
            <div class="text-center mb-4 mb-md-5">
                <h1 class="display-5 display-md-4 fw-bold mb-3" style="color: #713600;">
                    <i class="fas fa-credit-card me-2"></i>Checkout
                </h1>
                <p class="lead mb-0" style="color: #713600;">
                    Complete your order
                </p>
            </div>

            <form method="POST" action="process_order.php" id="checkoutForm" novalidate>
                <div class="row g-4 g-md-5">
                    <!-- Checkout Form -->
                    <div class="col-12 col-lg-7">
                        <!-- Shipping Information -->
                        <div class="card shadow-lg border-0 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #713600, #C05800);">
                                <h4 class="mb-0 fw-bold" style="color: #713600;">
                                    <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                                </h4>
                                <?php if ($has_addresses): ?>
                                <button type="button" class="btn btn-sm" onclick="toggleAddressForm()" style="background: #713600; color: white; border: none;">
                                    <i class="fas fa-plus me-1"></i>Add New
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <?php if ($has_addresses): ?>
                                <!-- Saved Addresses -->
                                <div id="savedAddresses">
                                    <h6 class="fw-bold mb-3" style="color: #713600;">Select Delivery Address:</h6>
                                    <?php foreach ($addresses as $index => $addr): ?>
                                    <div class="form-check mb-3 p-3 rounded" style="background: #f8f9fa; border: 2px solid <?php echo $addr['is_default'] ? '#713600' : '#C05800'; ?>;">
                                        <input class="form-check-input" type="radio" name="address_id" id="addr<?php echo $addr['address_id']; ?>" value="<?php echo $addr['address_id']; ?>" <?php echo $addr['is_default'] ? 'checked' : ''; ?> required>
                                        <label class="form-check-label w-100" for="addr<?php echo $addr['address_id']; ?>">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong style="color: #713600;"><?php echo htmlspecialchars($addr['full_name']); ?></strong>
                                                    <?php if ($addr['is_default']): ?>
                                                    <span class="badge ms-2" style="background: #713600; color: white;">Default</span>
                                                    <?php endif; ?>
                                                    <p class="mb-1 mt-2 small" style="color: #713600;">
                                                        <?php echo htmlspecialchars($addr['address_line1']); ?><?php echo !empty($addr['address_line2']) ? ', ' . htmlspecialchars($addr['address_line2']) : ''; ?><br>
                                                        <?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> - <?php echo htmlspecialchars($addr['pincode']); ?><br>
                                                        Phone: <?php echo htmlspecialchars($addr['phone']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- New Address Form (Hidden by default) -->
                                <div id="newAddressForm" style="display: none;">
                                    <h6 class="fw-bold mb-3" style="color: #713600;">Add New Address:</h6>
                                <?php else: ?>
                                <!-- No addresses - show form -->
                                <div id="newAddressForm">
                                    <div class="alert alert-info mb-3" style="background: rgba(212, 222, 149, 0.2); border: 2px solid #C05800; color: #713600;">
                                        <i class="fas fa-info-circle me-2"></i>Please add your delivery address to continue
                                    </div>
                                <?php endif; ?>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">Full Name *</label>
                                        <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">Email *</label>
                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">Phone Number *</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;" placeholder="+91 1234567890">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">Pincode *</label>
                                        <input type="text" name="pincode" id="pincode" class="form-control" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;" placeholder="400001">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold" style="color: #713600;">Address Line 1 *</label>
                                        <input type="text" name="address_line1" id="address_line1" class="form-control" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;" placeholder="House No., Building Name">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold" style="color: #713600;">Address Line 2</label>
                                        <input type="text" name="address_line2" id="address_line2" class="form-control" style="border: 2px solid #C05800;" placeholder="Road Name, Area, Colony">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">City *</label>
                                        <input type="text" name="city" id="city" class="form-control" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold" style="color: #713600;">State *</label>
                                        <input type="text" name="state" id="state" class="form-control" <?php echo !$has_addresses ? 'required' : ''; ?> style="border: 2px solid #C05800;">
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="save_address" id="saveAddress" value="1" checked>
                                            <label class="form-check-label" for="saveAddress" style="color: #713600;">
                                                <i class="fas fa-save me-1"></i>Save this address for future orders
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="set_default" id="setDefault" value="1">
                                            <label class="form-check-label" for="setDefault" style="color: #713600;">
                                                <i class="fas fa-star me-1"></i>Set as default address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="card shadow-lg border-0 rounded-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                            <div class="card-header py-3" style="background: linear-gradient(135deg, #713600, #C05800);">
                                <h4 class="mb-0 fw-bold" style="color: #713600;">
                                    <i class="fas fa-wallet me-2"></i>Payment Method
                                </h4>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <div class="form-check mb-3 p-3 rounded" style="background: #f8f9fa; border: 2px solid #C05800;">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label fw-bold" for="cod" style="color: #713600;">
                                        <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery (COD)
                                    </label>
                                    <p class="mb-0 mt-2 small text-muted">Pay when you receive your order</p>
                                </div>
                                <div class="form-check mb-3 p-3 rounded" style="background: #f8f9fa; border: 2px solid #C05800;">
                                    <input class="form-check-input" type="radio" name="payment_method" id="online" value="online">
                                    <label class="form-check-label fw-bold" for="online" style="color: #713600;">
                                        <i class="fas fa-credit-card me-2"></i>Online Payment
                                    </label>
                                    <p class="mb-0 mt-2 small text-muted">Pay securely using UPI, Card, or Net Banking</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-12 col-lg-5">
                        <div class="card shadow-lg border-0 rounded-4 sticky-top" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); top: 100px;">
                            <div class="card-header py-3" style="background: linear-gradient(135deg, #713600, #C05800);">
                                <h4 class="mb-0 fw-bold" style="color: #713600;">
                                    <i class="fas fa-receipt me-2"></i>Order Summary
                                </h4>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <!-- Cart Items -->
                                <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                                    <?php foreach ($cart_items as $item): 
                                        $img = !empty($item['image_path']) ? $item['image_path'] : 'assets/products/1.png';
                                    ?>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold" style="color: #713600; font-size: 0.9rem;"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <p class="mb-0 small" style="color: #713600;">Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?></p>
                                        </div>
                                        <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                    </div>
                                    <?php endforeach; ?>

                                    <!-- If we came from cart with selection, preserve the selected cart ids for order processing -->
                                    <?php if (!empty($selected_cart_ids)): ?>
                                        <?php foreach ($selected_cart_ids as $cid): ?>
                                            <input type="hidden" name="selected_cart[]" value="<?php echo (int)$cid; ?>">
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: #713600;">Subtotal:</span>
                                        <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($subtotal, 2); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span style="color: #713600;">Shipping:</span>
                                        <span class="fw-bold text-success">Free</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span style="color: #713600;">Tax (GST 18%):</span>
                                        <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($tax, 2); ?></span>
                                    </div>
                                    <hr style="border-color: #C05800;">
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="h5 fw-bold" style="color: #713600;">Total:</span>
                                        <span class="h5 fw-bold" style="color: #713600;">₹<?php echo number_format($total, 2); ?></span>
                                    </div>

                                    <!-- Place Order Button -->
                                    <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold text-white" style="background: linear-gradient(135deg, #713600, #713600); border: none; letter-spacing: 1px;">
                                        <i class="fas fa-check-circle me-2"></i>Place Order
                                    </button>
                                    <a href="cart.php" class="btn w-100 mt-2 py-2 rounded-pill fw-bold" style="background: #f8f9fa; color: #713600; border: 2px solid #C05800;">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleAddressForm() {
    const newAddressForm = document.getElementById('newAddressForm');
    
    if (newAddressForm.style.display === 'none') {
        newAddressForm.style.display = 'block';
        // Enable form fields
        document.getElementById('full_name').required = true;
        document.getElementById('email').required = true;
        document.getElementById('phone').required = true;
        document.getElementById('pincode').required = true;
        document.getElementById('address_line1').required = true;
        document.getElementById('city').required = true;
        document.getElementById('state').required = true;
        
        // Uncheck address radio buttons
        document.querySelectorAll('input[name="address_id"]').forEach(radio => {
            radio.required = false;
        });
    } else {
        newAddressForm.style.display = 'none';
        // Disable form fields
        document.getElementById('full_name').required = false;
        document.getElementById('email').required = false;
        document.getElementById('phone').required = false;
        document.getElementById('pincode').required = false;
        document.getElementById('address_line1').required = false;
        document.getElementById('city').required = false;
        document.getElementById('state').required = false;
        
        // Make address selection required again
        document.querySelectorAll('input[name="address_id"]').forEach(radio => {
            radio.required = true;
        });
    }
}

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const hasAddresses = <?php echo $has_addresses ? 'true' : 'false'; ?>;
    const newFormVisible = document.getElementById('newAddressForm').style.display !== 'none';
    
    if (hasAddresses && !newFormVisible) {
        // Using saved address - check if one is selected
        const selectedAddress = document.querySelector('input[name="address_id"]:checked');
        if (!selectedAddress) {
            e.preventDefault();
            try { showNotification('Please select a delivery address', 'danger'); } catch(e){ console.error('showNotification not available', e); }
            return false;
        }
    }
    
    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
