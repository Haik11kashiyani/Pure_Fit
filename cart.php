<?php
session_start();
ob_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Database Logic (Preserved)
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

<div class="min-h-screen pt-32 pb-24 px-6 md:px-12 bg-white text-black font-sans">
    
    <!-- Header -->
    <div class="flex items-baseline justify-between border-b border-gray-100 pb-8 mb-12">
        <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter">Your Bag</h1>
        <span class="text-xs font-bold uppercase tracking-widest text-gray-400"><?php echo $cart_count; ?> Items</span>
    </div>

    <?php if ($cart_count > 0): ?>
    <div class="flex flex-col lg:flex-row gap-12 lg:gap-24">
        
        <!-- Items List -->
        <div class="flex-grow space-y-8">
            <div class="hidden md:flex justify-between text-xs font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100 pb-4">
                <span class="w-1/2">Product</span>
                <span class="w-1/4 text-center">Quantity</span>
                <span class="w-1/4 text-right">Total</span>
            </div>

            <?php foreach ($cart_items as $item): 
                $img = !empty($item['image_path']) ? $item['image_path'] : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                if (!filter_var($img, FILTER_VALIDATE_URL) && !file_exists($img) && !file_exists(__DIR__ . '/' . $img)) {
                    $img = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                }
                $item_total = $item['price'] * $item['quantity'];
                
                // Fallback for missing variant_id display
                $variant_display = '';
                if(!empty($item['variant_id'])) {
                    // Try to fetch size if possible, or just show ID
                    $variant_display = "Size: " . $item['variant_id']; 
                }
            ?>
            
            <div class="flex flex-col md:flex-row gap-6 relative py-6 border-b border-gray-100 hover:bg-gray-50 transition-colors -mx-4 px-4 rounded-xl">
                <!-- Checkbox -->
                <div class="absolute top-4 right-4 md:static md:flex md:items-center">
                     <input type="checkbox" class="cart-select w-4 h-4 text-black border-gray-300 rounded focus:ring-black cursor-pointer" 
                            data-cart-id="<?php echo $item['cart_id']; ?>" 
                            checked>
                </div>

                <!-- Image -->
                <div class="w-24 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                    <a href="product-details.php?id=<?php echo $item['product_id']; ?>">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                    </a>
                </div>

                <!-- Info -->
                <div class="flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wide mb-1">
                            <a href="product-details.php?id=<?php echo $item['product_id']; ?>">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </a>
                        </h3>
                        <p class="text-xs text-gray-500 font-medium">
                            <?php echo $variant_display; ?> 
                            <span class="mx-2 text-gray-300">|</span> 
                            ₹<?php echo number_format($item['price'], 2); ?>
                        </p>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="flex items-center gap-4 md:justify-center w-full md:w-1/4">
                    <form method="POST" action="add_to_cart.php" class="flex items-center bg-gray-50 rounded-full px-2 py-1">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <?php if (!empty($item['variant_id'])): ?>
                            <input type="hidden" name="variant_id" value="<?php echo $item['variant_id']; ?>">
                        <?php endif; ?>
                        
                        <button type="submit" name="action" value="update_quantity" formaction="add_to_cart.php?action=update_quantity&qty=<?php echo max(1, $item['quantity'] - 1); ?>"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-black transition-colors"
                                onclick="this.form.querySelector('[name=quantity]').value = <?php echo max(1, $item['quantity'] - 1); ?>">
                            -
                        </button>
                        
                        <input type="hidden" name="action" value="update_quantity">
                        <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>"> 
                        <span class="w-8 text-center text-xs font-bold"><?php echo $item['quantity']; ?></span>
                        
                        <button type="submit" name="action" value="update_quantity"
                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-black transition-colors"
                                onclick="this.form.querySelector('[name=quantity]').value = <?php echo $item['quantity'] + 1; ?>">
                            +
                        </button>
                    </form>
                    
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="action" value="remove">
                         <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors" title="Remove">
                             <i class="fas fa-trash-alt text-xs"></i>
                         </button>
                    </form>
                </div>

                <!-- Total -->
                <div class="md:w-1/4 text-right font-bold text-sm">
                    ₹<?php echo number_format($item_total, 2); ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="pt-8">
                <a href="products.php" class="text-xs font-bold uppercase tracking-widest hover:text-gray-500 transition-colors inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                </a>
            </div>
        </div>

        <!-- Summary Sticky -->
        <div class="lg:w-96 flex-shrink-0">
             <div class="h-fit sticky top-32 p-8 bg-gray-50 rounded-2xl">
                <h2 class="text-lg font-bold uppercase tracking-wide mb-6">Order Summary</h2>
                
                <div class="space-y-4 mb-8 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium">₹<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Shipping</span>
                        <span class="text-xs uppercase bg-gray-200 px-2 py-1 rounded">Calculated at Link</span>
                    </div>
                    <div class="border-t border-gray-200 my-4"></div>
                    <div class="flex justify-between text-lg font-black">
                        <span>Total</span>
                        <span>₹<?php echo number_format($total_amount, 2); ?></span>
                    </div>
                </div>

                <form id="checkoutForm" method="POST" action="checkout.php">
                    <div id="checkoutInputs"></div>
                    <button type="submit" class="w-full py-4 bg-black text-white text-xs font-bold uppercase tracking-[0.2em] rounded-full hover:scale-[1.02] transition-transform shadow-lg">
                        Proceed to Checkout
                    </button>
                </form>
                
                <div class="mt-6 flex justify-center space-x-4 text-gray-300 text-xl">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                </div>
            </div>
        </div>

    </div>
    <?php else: ?>
    <div class="flex flex-col items-center justify-center py-32 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-6">
            <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Your Bag is Empty</h2>
        <p class="text-gray-500 text-sm mb-8">Looks like you haven't made any choices yet.</p>
        <a href="products.php" class="px-8 py-3 bg-black text-white rounded-full text-xs font-bold uppercase tracking-widest hover:scale-105 transition-transform">
            Shop Essentials
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const container = document.getElementById('checkoutInputs');
        container.innerHTML = '';
        const checked = Array.from(document.querySelectorAll('.cart-select:checked'));
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select item(s) to checkout.');
            return false;
        }
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_cart[]';
            input.value = cb.getAttribute('data-cart-id');
            container.appendChild(input);
        });
    });
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
