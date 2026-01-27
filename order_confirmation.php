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
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch order details
$order_query = "SELECT o.*, COUNT(oi.order_item_id) as item_count 
                FROM orders o 
                LEFT JOIN order_items oi ON o.order_id = oi.order_id 
                WHERE o.order_id = ? AND o.user_id = ? 
                GROUP BY o.order_id";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch order items
$items_query = "SELECT oi.*, p.name, p.image_path, oi.price_at_time as price
                FROM order_items oi 
                INNER JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_query);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

$order_items = [];
while ($item = $items_result->fetch_assoc()) {
    $order_items[] = $item;
}
?>

<div class="min-h-screen bg-dark py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Message -->
        <div class="text-center mb-16 relative">
            <!-- Animated Checkmark Background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-primary/10 blur-[80px] rounded-full pointer-events-none"></div>

            <div class="mb-8 inline-flex items-center justify-center w-24 h-24 lg:w-32 lg:h-32 rounded-full border-2 border-primary/50 text-primary relative shadow-[0_0_30px_rgba(234,179,8,0.3)] animate-pulse-slow">
                <i class="fas fa-check text-4xl lg:text-5xl"></i>
            </div>
            <h1 class="text-3xl md:text-5xl font-display font-black text-light mb-4 uppercase tracking-tight">
                Order Confirmed
            </h1>
            
            <p class="text-gray-400 text-sm md:text-base font-mono mb-8 max-w-lg mx-auto uppercase tracking-widest">
                <?php echo $order['order_status'] === 'confirmed' ? 'Your order has been sanctioned. Preparation imminent.' : 'Awaiting payment confirmation to proceed.'; ?>
            </p>
            
            <div class="inline-block px-6 py-2 border border-white/10 bg-white/5 backdrop-blur-md rounded-full">
                <?php $displayOrderShort = sprintf('%02d', $order['order_id'] % 100); ?>
                <span class="text-xs text-gray-400 uppercase tracking-widest mr-2">Order ID</span>
                <strong class="text-primary font-mono text-sm">#<?php echo $displayOrderShort; ?></strong>
            </div>

            <?php if ($order['payment_method_id'] == 1): // COD ?>
            <div class="max-w-md mx-auto mt-8 bg-white/5 border border-white/10 p-4 flex items-center justify-center gap-4">
                <i class="fas fa-money-bill-wave text-primary"></i>
                <div class="text-left">
                    <strong class="block text-light text-xs uppercase tracking-widest">Cash on Delivery</strong>
                    <p class="text-[10px] text-gray-500 font-mono">Pay ₹<?php echo number_format($order['total_amount'], 2); ?> upon arrival.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Order Details Card -->
            <div class="bg-white/5 border border-white/5 p-8 relative overflow-hidden group hover:border-white/20 transition-colors">
                <div class="flex items-center mb-8 pb-4 border-b border-white/10">
                    <i class="fas fa-info-circle text-primary mr-3"></i>
                    <h3 class="text-lg font-bold font-display text-light uppercase tracking-wide">Order Summary</h3>
                </div>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-xs uppercase tracking-widest">Date</span>
                        <span class="font-mono text-light text-sm"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-xs uppercase tracking-widest">Total</span>
                        <span class="font-mono text-primary text-xl font-bold">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-xs uppercase tracking-widest">Payment</span>
                        <span class="font-mono text-light text-sm">
                            <?php echo ($order['payment_method_id'] == 1) ? 'COD' : 'Online'; ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                         <span class="text-gray-500 text-xs uppercase tracking-widest">Status</span>
                         <?php
                            $status_colors = [
                                'pending' => 'text-yellow-400 border-yellow-400',
                                'confirmed' => 'text-green-400 border-green-400',
                                'processing' => 'text-blue-400 border-blue-400',
                                'shipped' => 'text-indigo-400 border-indigo-400',
                                'delivered' => 'text-primary border-primary',
                                'cancelled' => 'text-red-400 border-red-400'
                            ];
                            $status_class = $status_colors[$order['order_status']] ?? 'text-gray-400 border-gray-400';
                        ?>
                        <span class="px-2 py-1 border <?php echo $status_class; ?> text-[10px] font-bold uppercase tracking-widest bg-transparent">
                            <?php echo ucfirst($order['order_status']); ?>
                        </span>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-white/10">
                    <h4 class="font-bold text-light text-xs uppercase tracking-widest mb-4">Shipping Destination</h4>
                     <?php
                        $raw_addr = $order['shipping_address'] ?? '';
                        $raw_addr = str_replace('\\n', "\n", $raw_addr);
                    ?>
                    <p class="text-gray-400 text-sm font-mono leading-relaxed bg-black/20 p-4 border border-white/5">
                        <?php echo nl2br(htmlspecialchars($raw_addr)); ?>
                    </p>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="bg-white/5 border border-white/5 p-8 relative overflow-hidden group hover:border-white/20 transition-colors h-fit">
                <div class="flex items-center mb-8 pb-4 border-b border-white/10">
                    <i class="fas fa-box-open text-primary mr-3"></i>
                    <h3 class="text-lg font-bold font-display text-light uppercase tracking-wide">Acquired Items</h3>
                </div>
                
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php foreach ($order_items as $item): 
                        $img = !empty($item['image_path']) ? $item['image_path'] : 'assets/products/1.png';
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <div class="flex items-center p-4 bg-black/20 border border-white/5 hover:border-primary/30 transition-colors group/item">
                        <div class="w-16 h-16 flex-shrink-0 bg-white/5 overflow-hidden relative border border-white/10">
                            <img src="<?php echo htmlspecialchars($img); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 class="w-full h-full object-cover filter grayscale group-hover/item:grayscale-0 transition-all">
                        </div>
                        <div class="ml-6 flex-grow min-w-0">
                            <h6 class="text-sm font-bold text-light truncate uppercase tracking-wide"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <p class="text-xs text-gray-500 mt-1 font-mono">
                                <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                            </p>
                        </div>
                        <div class="ml-4 text-right flex-shrink-0">
                            <span class="block font-mono text-primary text-sm font-bold">₹<?php echo number_format($item_total, 2); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-16 text-center flex flex-col sm:flex-row justify-center items-center gap-6">
            <a href="index.php" class="magnetic-btn px-10 py-4 bg-white text-dark font-bold font-display uppercase tracking-widest text-xs hover:bg-primary transition-colors w-full sm:w-auto">
                Continue Shopping
            </a>
            <a href="profile.php?tab=orders" class="magnetic-btn px-10 py-4 border border-white/20 text-light font-bold font-display uppercase tracking-widest text-xs hover:bg-white hover:text-dark transition-colors w-full sm:w-auto">
                View All Orders
            </a>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
