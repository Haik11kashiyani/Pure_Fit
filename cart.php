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

// Fetch cart items from database
$cart_query = "SELECT c.*, p.name, p.price, p.image_path, p.stock_quantity 
               FROM cart c 
               INNER JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = ? AND p.is_active = 1 
               ORDER BY c.created_at DESC";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$cart_items = [];
$total_amount = 0;

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $total_amount += ($item['price'] * $item['quantity']);
}

$cart_count = count($cart_items);
?>
<div class="container-fluid py-4 py-md-5" style="padding-top: 80px !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 px-3">
            <!-- Page Header -->
            <div class="text-center mb-4 mb-md-5">
                <h1 class="display-5 display-md-4 fw-bold mb-3" style="color: #713600;">
                    <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                </h1>
                <p class="lead mb-0" style="color: #713600;">
                    Review your items and proceed to checkout
                </p>
            </div>

            <?php if ($cart_count > 0): ?>
            <div class="row g-4 g-md-5">
                <!-- Cart Items -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-3 py-md-4" style="background: linear-gradient(135deg, #713600, #C05800);">
                            <h3 class="mb-0 fw-bold" style="color: #713600;">
                                Your Items (<?php echo $cart_count; ?>)
                            </h3>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <?php foreach ($cart_items as $item): 
                                $img = !empty($item['image_path']) ? $item['image_path'] : 'assets/products/1.png';
                                $item_total = $item['price'] * $item['quantity'];
                            ?>
                            <!-- Cart Item -->
                            <div class="cart-item mb-3 mb-md-4 p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #C05800; position: relative;">
                                <!-- selection checkbox (small, default checked) -->
                                <input type="checkbox" class="form-check-input cart-select" data-cart-id="<?php echo $item['cart_id']; ?>" checked
                                       style="position: absolute; left: 12px; top: 18px; transform: scale(1.1);">
                                <div class="row align-items-center g-2">
                                    <div class="col-4 col-md-2">
                                        <a href="product-details.php?id=<?php echo $item['product_id']; ?>">
                                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid rounded" style="max-height: 80px; object-fit: cover; width: 100%;">
                                        </a>
                                    </div>
                                    <div class="col-8 col-md-4">
                                        <a href="product-details.php?id=<?php echo $item['product_id']; ?>" class="text-decoration-none">
                                            <h6 class="fw-bold mb-1" style="color: #713600;">
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </h6>
                                        </a>
                                        <p class="mb-1 small" style="color: #713600;">
                                            Price: ₹<?php echo number_format($item['price'], 2); ?>
                                        </p>
                                        <p class="mb-0 fw-bold d-md-none" style="color: #713600;">
                                            Total: ₹<?php echo number_format($item_total, 2); ?>
                                        </p>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="quantity-controls d-flex align-items-center justify-content-center">
                                            <form method="POST" action="add_to_cart.php" style="display: inline;" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <?php if (!empty($item['variant_id'])): ?>
                                                    <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                                                <?php endif; ?>
                                                <input type="hidden" name="action" value="update_quantity">
                                                <input type="hidden" name="quantity" value="<?php echo max(1, $item['quantity'] - 1); ?>">
                                                <input type="hidden" name="redirect" value="cart.php">
                                                <button type="submit" class="btn btn-sm rounded-circle me-2" style="background: #C05800; color: #713600; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-minus fa-xs"></i>
                                                </button>
                                            </form>
                                            <span class="fw-bold mx-2" style="color: #713600; min-width: 30px; text-align: center;"><?php echo $item['quantity']; ?></span>
                                            <form method="POST" action="add_to_cart.php" style="display: inline;" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <?php if (!empty($item['variant_id'])): ?>
                                                    <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                                                <?php endif; ?>
                                                <input type="hidden" name="action" value="update_quantity">
                                                <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                                <input type="hidden" name="redirect" value="cart.php">
                                                <button type="submit" class="btn btn-sm rounded-circle ms-2" style="background: #C05800; color: #713600; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-plus fa-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 text-end">
                                        <p class="mb-2 fw-bold d-none d-md-block" style="color: #713600;">
                                            ₹<?php echo number_format($item_total, 2); ?>
                                        </p>
                                        <form method="POST" action="add_to_cart.php" style="display: inline;" novalidate>
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <?php if (!empty($item['variant_id'])): ?>
                                                <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                                            <?php endif; ?>
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="redirect" value="cart.php">
                                            <button type="submit" class="btn btn-sm text-danger" style="background: none; border: none;">
                                                <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Remove</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <!-- Continue Shopping and Checkout Selected -->
                            <div class="text-center mt-4 d-flex justify-content-center gap-3 flex-wrap">
                                <a href="products.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill" style="border-color: #C05800; color: #713600; transition: all 0.3s ease;">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Continue Shopping
                                </a>

                                <!-- Form to submit selected cart ids to checkout.php -->
                                <form id="selectedCheckoutForm" method="POST" action="checkout.php" style="display:inline-block;">
                                    <!-- hidden container for selected cart ids (populated by JS) -->
                                    <div id="selectedCartInputs"></div>
                                    <button type="submit" id="checkoutSelectedBtn" class="btn btn-outline-success px-4 py-2 rounded-pill" style="border-color: #C05800; color: #713600;">
                                        <i class="fas fa-shopping-cart me-2"></i>Checkout Selected
                                    </button>
                                </form>
                            </div>
                        </div>
                    <script>
                        // When submitting the selected-checkout form, collect checked cart items
                        document.getElementById('selectedCheckoutForm').addEventListener('submit', function(e) {
                            const container = document.getElementById('selectedCartInputs');
                            container.innerHTML = '';
                            const checked = Array.from(document.querySelectorAll('.cart-select:checked'));
                            if (checked.length === 0) {
                                e.preventDefault();
                                try { showNotification('Please select at least one item to checkout', 'danger'); } catch (err) { alert('Please select at least one item to checkout'); }
                                return false;
                            }
                            // Add hidden inputs for selected cart ids
                            checked.forEach(cb => {
                                const val = cb.getAttribute('data-cart-id');
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'selected_cart[]';
                                input.value = val;
                                container.appendChild(input);
                            });
                        });
                    </script>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-3 py-md-4" style="background: linear-gradient(135deg, #713600, #C05800);">
                            <h3 class="mb-0 fw-bold" style="color: #713600;">
                                Order Summary
                            </h3>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #713600;">Subtotal (<?php echo $cart_count; ?> items):</span>
                                <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>
                        
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #713600;">Shipping:</span>
                                <span class="fw-bold" style="color: #713600;">Free</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #713600;">Tax (GST 18%):</span>
                                <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($total_amount * 0.18, 2); ?></span>
                            </div>
                            <hr style="border-color: #C05800;">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold" style="color: #713600;">Total:</span>
                                <span class="h5 fw-bold" style="color: #713600;">₹<?php echo number_format($total_amount * 1.18, 2); ?></span>
                            </div>
                            
                            <a href="checkout.php" class="btn w-100 py-3 rounded-pill fw-bold text-white mb-3 text-decoration-none" 
                                    style="background: linear-gradient(135deg, #713600, #713600); border: none; transition: all 0.3s ease; letter-spacing: 1px; display: block;">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Empty Cart -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-5x text-muted mb-4" style="opacity: 0.3;"></i>
                        <h3 class="fw-bold mb-3" style="color: #713600;">Your Cart is Empty</h3>
                        <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                        <a href="products.php" class="btn btn-lg px-5 py-3 rounded-pill text-white fw-bold" style="background: linear-gradient(135deg, #713600, #713600); border: none;">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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

.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
}

.quantity-controls button {
    transition: all 0.3s ease;
}

.quantity-controls button:hover {
    background: #713600 !important;
    color: #ffffff !important;
    transform: scale(1.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-control:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
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
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
