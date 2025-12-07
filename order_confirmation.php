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

<div class="container-fluid py-4 py-md-5" style="padding-top: 80px !important;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 px-3">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x" style="color: #713600;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3" style="color: #713600;">
                    Order Placed Successfully!
                </h1>
                
                <?php if ($order['payment_method_id'] == 1): // COD ?>
                <div class="alert alert-info mb-4 mx-auto" style="max-width: 600px; background: rgba(212, 222, 149, 0.2); border: 2px solid #C05800; color: #713600;">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <strong>Cash on Delivery</strong><br>
                    <small>You will pay ₹<?php echo number_format($order['total_amount'], 2); ?> when your order is delivered</small>
                </div>
                <?php else: ?>
                <div class="alert alert-warning mb-4 mx-auto" style="max-width: 600px; background: rgba(255, 193, 7, 0.1); border: 2px solid #ffc107; color: #856404;">
                    <i class="fas fa-credit-card me-2"></i>
                    <strong>Payment Pending</strong><br>
                    <small>Please complete your payment to confirm the order</small>
                </div>
                <?php endif; ?>
                
                <p class="lead mb-4" style="color: #713600;">
                    <?php echo $order['order_status'] === 'confirmed' ? 'Your order has been confirmed and will be processed soon.' : 'Your order is pending payment confirmation.'; ?>
                </p>
                <div class="alert alert-success d-inline-block px-5 py-3" style="background: rgba(212, 222, 149, 0.3); border: 2px solid #C05800; border-radius: 50px;">
                    <?php $displayOrderShort = sprintf('%02d', $order['order_id'] % 100); ?>
                    <strong style="color: #713600;">Order ID: <?php echo $displayOrderShort; ?></strong>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card shadow-lg border-0 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #713600, #C05800);">
                    <h4 class="mb-0 fw-bold" style="color: #713600;">
                        <i class="fas fa-box me-2"></i>Order Details
                    </h4>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <p class="mb-1 small" style="color: #713600;">Order Date:</p>
                            <p class="mb-0 fw-bold" style="color: #713600;"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1 small" style="color: #713600;">Total Amount:</p>
                            <p class="mb-0 fw-bold h5" style="color: #713600;">₹<?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1 small" style="color: #713600;">Payment Method:</p>
                            <p class="mb-0 fw-bold" style="color: #713600;">
                                <?php echo ($order['payment_method_id'] == 1) ? 'Cash on Delivery' : 'Online Payment'; ?>
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1 small" style="color: #713600;">Order Status:</p>
                            <?php
                            $status_colors = [
                                'pending' => 'background: #ffc107; color: #856404;',
                                'confirmed' => 'background: #28a745; color: white;',
                                'processing' => 'background: #17a2b8; color: white;',
                                'shipped' => 'background: #007bff; color: white;',
                                'delivered' => 'background: #713600; color: white;',
                                'cancelled' => 'background: #dc3545; color: white;'
                            ];
                            $status_style = $status_colors[$order['order_status']] ?? 'background: #C05800; color: #713600;';
                            ?>
                            <span class="badge px-3 py-2" style="<?php echo $status_style; ?> font-size: 0.9rem;">
                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i><?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="mb-1 small" style="color: #713600;">Payment Status:</p>
                            <?php
                            $payment_colors = [
                                'pending' => 'background: #ffc107; color: #856404;',
                                'completed' => 'background: #28a745; color: white;',
                                'failed' => 'background: #dc3545; color: white;'
                            ];
                            $payment_style = $payment_colors[$order['payment_status']] ?? 'background: #C05800; color: #713600;';
                            ?>
                            <span class="badge px-3 py-2" style="<?php echo $payment_style; ?> font-size: 0.9rem;">
                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i><?php echo ucfirst($order['payment_status']); ?>
                            </span>
                        </div>
                    </div>

                    <hr style="border-color: #C05800;">

                    <h5 class="fw-bold mb-3" style="color: #713600;">Shipping Address:</h5>
                    <?php
                        // Some addresses were stored containing literal backslash-n sequences ("\n").
                        // Convert literal "\n" to actual newlines so nl2br() renders them correctly.
                        $raw_addr = $order['shipping_address'] ?? '';
                        $raw_addr = str_replace('\\n', "\n", $raw_addr);
                    ?>
                    <p class="mb-0" style="color: #713600; white-space: pre-line;">
                        <?php echo nl2br(htmlspecialchars($raw_addr)); ?>
                    </p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-lg border-0 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #713600, #C05800);">
                    <h4 class="mb-0 fw-bold" style="color: #713600;">
                        <i class="fas fa-shopping-bag me-2"></i>Items (<?php echo count($order_items); ?>)
                    </h4>
                </div>
                <div class="card-body p-3 p-md-4">
                    <?php foreach ($order_items as $item): 
                        $img = !empty($item['image_path']) ? $item['image_path'] : 'assets/products/1.png';
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold" style="color: #713600;"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <p class="mb-0 small" style="color: #713600;">
                                Quantity: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                            </p>
                        </div>
                        <span class="fw-bold" style="color: #713600;">₹<?php echo number_format($item_total, 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="index.php" class="btn btn-lg px-5 py-3 rounded-pill text-white fw-bold me-2 mb-2" style="background: linear-gradient(135deg, #713600, #713600); border: none;">
                    <i class="fas fa-home me-2"></i>Continue Shopping
                </a>
                <a href="profile.php" class="btn btn-lg px-5 py-3 rounded-pill fw-bold mb-2" style="background: #f8f9fa; color: #713600; border: 2px solid #C05800;">
                    <i class="fas fa-user me-2"></i>View Orders
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
