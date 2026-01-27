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

// Logic to fetch items based on selection or all items
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

<div class="bg-dark min-h-screen py-24" x-data="{ 
    showNewAddress: <?php echo $has_addresses ? 'false' : 'true'; ?>,
    submitting: false,
    toggleAddressForm() {
        this.showNewAddress = !this.showNewAddress;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-display font-black text-light mb-4 uppercase tracking-tighter">Checkout</h1>
        <p class="text-gray-400 mb-12 font-mono text-sm max-w-xl">Complete your order to secure your gear.</p>

        <form method="POST" action="process_order.php" id="checkoutForm" @submit="submitting = true">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Main Content: Shipping & Payment -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Shipping Address Section -->
                    <div class="bg-white/5 border border-white/5 p-8 relative">
                         <div class="absolute top-0 left-0 w-1 h-full bg-white/10"></div>
                        <div class="flex justify-between items-center mb-8 pb-4 border-b border-white/10">
                            <h2 class="text-xl font-bold font-display text-light uppercase tracking-wide flex items-center">
                                <i class="fas fa-map-marker-alt mr-4 text-primary"></i> Shipping Address
                            </h2>
                            <?php if ($has_addresses): ?>
                            <button type="button" @click="toggleAddressForm()" class="text-xs font-bold text-primary hover:text-white uppercase tracking-widest transition-colors focus:outline-none">
                                <span x-text="showNewAddress ? 'Cancel' : '+ Add New'"></span>
                            </button>
                            <?php endif; ?>
                        </div>

                        <!-- Saved Addresses List -->
                        <div x-show="!showNewAddress" class="space-y-4">
                            <?php if ($has_addresses): ?>
                                <?php foreach ($addresses as $index => $addr): ?>
                                <label class="block relative cursor-pointer group">
                                    <input type="radio" name="address_id" value="<?php echo $addr['address_id']; ?>" class="peer sr-only" <?php echo $addr['is_default'] ? 'checked' : ''; ?> :required="!showNewAddress">
                                    <div class="p-6 border border-white/10 peer-checked:border-primary peer-checked:bg-white/5 transition-all hover:border-white/30">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="font-bold text-light uppercase tracking-wide text-sm"><?php echo htmlspecialchars($addr['full_name']); ?></span>
                                                    <?php if ($addr['is_default']): ?>
                                                    <span class="text-[10px] bg-primary text-dark px-2 py-0.5 font-bold uppercase tracking-wide">Default</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-gray-400 text-sm leading-relaxed">
                                                    <?php echo htmlspecialchars($addr['address_line1']); ?><?php echo !empty($addr['address_line2']) ? ', ' . htmlspecialchars($addr['address_line2']) : ''; ?><br>
                                                    <?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> - <?php echo htmlspecialchars($addr['pincode']); ?>
                                                </p>
                                                <p class="text-gray-500 text-xs mt-3 font-mono">Phone: <?php echo htmlspecialchars($addr['phone']); ?></p>
                                            </div>
                                            <div class="w-4 h-4 rounded-full border border-gray-500 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                                <div class="w-1.5 h-1.5 rounded-full bg-dark opacity-0 peer-checked:opacity-100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- New Address Form -->
                        <div x-show="showNewAddress" x-transition class="space-y-6" :class="{'mt-8': <?php echo $has_addresses ? 'true' : 'false'; ?>}">
                             <?php if (!$has_addresses): ?>
                                <div class="bg-primary/10 border border-primary/20 text-primary px-4 py-3 text-xs uppercase tracking-widest mb-6">
                                    <i class="fas fa-info-circle mr-2"></i> Add a delivery address to proceed
                                </div>
                            <?php endif; ?>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" :required="showNewAddress">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" :required="showNewAddress">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Pincode</label>
                                    <input type="text" name="pincode" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" :required="showNewAddress">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">City</label>
                                    <input type="text" name="city" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" :required="showNewAddress">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Address Line 1</label>
                                    <input type="text" name="address_line1" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" placeholder="House No., Building Name" :required="showNewAddress">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Address Line 2 (Optional)</label>
                                    <input type="text" name="address_line2" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" placeholder="Area, Landmark">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">State</label>
                                    <input type="text" name="state" class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors" :required="showNewAddress">
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-6 mt-6 border-t border-white/10 pt-6">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="save_address" value="1" checked class="w-5 h-5 bg-transparent border-gray-500 text-primary focus:ring-primary rounded">
                                    <span class="text-xs text-gray-400 uppercase tracking-wider group-hover:text-light transition-colors">Save address</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox" name="set_default" value="1" class="w-5 h-5 bg-transparent border-gray-500 text-primary focus:ring-primary rounded">
                                    <span class="text-xs text-gray-400 uppercase tracking-wider group-hover:text-light transition-colors">Set default</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="bg-white/5 border border-white/5 p-8 relative">
                         <div class="absolute top-0 left-0 w-1 h-full bg-white/10"></div>
                         <div class="flex justify-between items-center mb-8 pb-4 border-b border-white/10">
                            <h2 class="text-xl font-bold font-display text-light uppercase tracking-wide flex items-center">
                                <i class="fas fa-wallet mr-4 text-primary"></i> Payment Method
                            </h2>
                        </div>
                        <div class="space-y-4">
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cod" class="peer sr-only" checked>
                                <div class="p-6 border border-white/10 peer-checked:border-primary peer-checked:bg-white/5 transition-all hover:bg-white/5">
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave text-light text-xl mr-4 opacity-50 peer-checked:opacity-100 peer-checked:text-primary"></i>
                                        <div class="flex-grow">
                                            <span class="font-bold text-light block uppercase tracking-wide text-sm">Cash on Delivery</span>
                                            <span class="text-xs text-gray-500 font-mono">Pay upon receipt</span>
                                        </div>
                                        <div class="w-4 h-4 rounded-full border border-gray-500 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 rounded-full bg-dark opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="block relative cursor-pointer group opacity-50">
                                <input type="radio" name="payment_method" value="online" class="peer sr-only">
                                <div class="p-6 border border-white/10 peer-checked:border-primary peer-checked:bg-white/5 transition-all">
                                    <div class="flex items-center">
                                         <i class="fas fa-credit-card text-light text-xl mr-4 opacity-50 peer-checked:opacity-100 peer-checked:text-primary"></i>
                                        <div class="flex-grow">
                                            <span class="font-bold text-light block uppercase tracking-wide text-sm">Online Payment</span>
                                            <span class="text-xs text-gray-500 font-mono">UPI / Cards (Coming Soon)</span>
                                        </div>
                                        <div class="w-4 h-4 rounded-full border border-gray-500 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 rounded-full bg-dark opacity-0 peer-checked:opacity-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white/5 border border-white/10 p-8 sticky top-32 backdrop-blur-sm">
                        <h2 class="text-xl font-display font-bold text-light mb-6 pb-4 border-b border-white/10 uppercase tracking-wide">Summary</h2>
                        
                        <!-- Items List -->
                        <div class="max-h-64 overflow-y-auto pr-2 mb-8 space-y-4 custom-scrollbar">
                            <?php foreach ($cart_items as $item): 
                                $img = !empty($item['image_path']) ? $item['image_path'] : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                                if (!filter_var($img, FILTER_VALIDATE_URL) && !file_exists($img) && !file_exists(__DIR__ . '/' . $img)) {
                                    $img = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                                }
                            ?>
                            <div class="flex gap-4">
                                <div class="w-16 h-16 bg-white/5 flex-shrink-0 overflow-hidden border border-white/5">
                                    <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-light uppercase tracking-wide line-clamp-1"><?php echo htmlspecialchars($item['name']); ?></p>
                                    <p class="text-xs text-gray-500 mt-1 font-mono"><?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?></p>
                                </div>
                                <div class="text-sm font-bold text-primary font-mono">
                                    ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Selected Cart IDs Hidden Field -->
                         <?php if (!empty($selected_cart_ids)): ?>
                            <?php foreach ($selected_cart_ids as $cid): ?>
                                <input type="hidden" name="selected_cart[]" value="<?php echo (int)$cid; ?>">
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Totals -->
                        <div class="space-y-4 mb-8 pt-6 border-t border-white/10">
                             <div class="flex justify-between text-gray-400 text-sm">
                                <span>Subtotal</span>
                                <span class="font-mono text-light">₹<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>Shipping</span>
                                <span class="text-primary font-bold uppercase text-xs tracking-widest">Free</span>
                            </div>
                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>Tax (18%)</span>
                                <span class="font-mono text-light">₹<?php echo number_format($tax, 2); ?></span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold text-light pt-4 border-t border-white/10 uppercase tracking-wide">
                                <span>Total</span>
                                <span class="text-2xl font-mono text-primary">₹<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="magnetic-btn w-full py-4 bg-white text-dark font-display font-bold uppercase tracking-widest text-sm hover:bg-primary transition-colors disabled:opacity-50 disabled:cursor-not-allowed" :disabled="submitting">
                             <span x-show="!submitting">Confirm Order</span>
                             <span x-show="submitting" x-cloak class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-2"></i> Processing</span>
                        </button>

                         <a href="cart.php" class="block text-center mt-6 text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">
                            Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
