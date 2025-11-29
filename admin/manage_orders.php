<?php 
include 'layout.php';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    
    $update_query = "UPDATE orders SET order_status = ?, payment_status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssi', $new_status, $payment_status, $order_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_msg = "Order status updated successfully!";
    } else {
        $error_msg = "Error updating order status.";
    }
    mysqli_stmt_close($stmt);
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query
$query = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone, pm.display_name as payment_method
          FROM orders o
          INNER JOIN users u ON o.user_id = u.user_id
          LEFT JOIN payment_methods pm ON o.payment_method_id = pm.payment_method_id
          WHERE 1=1";

if ($status_filter) {
    $query .= " AND o.order_status = '$status_filter'";
}

if ($search) {
    $query .= " AND (o.order_id LIKE '%$search%' OR u.first_name LIKE '%$search%' OR u.last_name LIKE '%$search%' OR u.email LIKE '%$search%')";
}

$query .= " ORDER BY o.created_at DESC";
$orders_result = mysqli_query($conn, $query);
// Buffer for capturing modals markup when rendering the list — printed after the table to keep markup clean
$order_modals_html = '';

// Get order counts by status
$status_counts = [];
$status_query = "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status";
$status_result = mysqli_query($conn, $status_query);
while ($row = mysqli_fetch_assoc($status_result)) {
    $status_counts[$row['order_status']] = $row['count'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-shopping-cart me-2"></i>Manage Orders</h2>
</div>

<?php if (isset($success_msg)): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($error_msg)): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Status Filter Tabs -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <div class="btn-group" role="group">
                    <a href="manage_orders.php" class="btn btn-<?php echo empty($status_filter) ? 'primary' : 'outline-primary'; ?>">
                        All (<?php echo array_sum($status_counts); ?>)
                    </a>
                    <a href="manage_orders.php?status=pending" class="btn btn-<?php echo $status_filter == 'pending' ? 'warning' : 'outline-warning'; ?>">
                        Pending (<?php echo $status_counts['pending'] ?? 0; ?>)
                    </a>
                    <a href="manage_orders.php?status=confirmed" class="btn btn-<?php echo $status_filter == 'confirmed' ? 'info' : 'outline-info'; ?>">
                        Confirmed (<?php echo $status_counts['confirmed'] ?? 0; ?>)
                    </a>
                    <a href="manage_orders.php?status=processing" class="btn btn-<?php echo $status_filter == 'processing' ? 'primary' : 'outline-primary'; ?>">
                        Processing (<?php echo $status_counts['processing'] ?? 0; ?>)
                    </a>
                    <a href="manage_orders.php?status=shipped" class="btn btn-<?php echo $status_filter == 'shipped' ? 'success' : 'outline-success'; ?>">
                        Shipped (<?php echo $status_counts['shipped'] ?? 0; ?>)
                    </a>
                    <a href="manage_orders.php?status=delivered" class="btn btn-<?php echo $status_filter == 'delivered' ? 'success' : 'outline-success'; ?>">
                        Delivered (<?php echo $status_counts['delivered'] ?? 0; ?>)
                    </a>
                    <a href="manage_orders.php?status=cancelled" class="btn btn-<?php echo $status_filter == 'cancelled' ? 'danger' : 'outline-danger'; ?>">
                        Cancelled (<?php echo $status_counts['cancelled'] ?? 0; ?>)
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <form method="GET" class="input-group" novalidate>
                    <input type="text" name="search" class="form-control" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($orders_result) > 0): ?>
                        <?php 
                            // We'll collect modal HTML for each order and output it after the table
                            $order_modals_html = '';
                            while ($order = mysqli_fetch_assoc($orders_result)): ?>
                        <tr>
                            <?php $adminOrderShort = sprintf('%02d', $order['order_id'] % 100); ?>
                            <td><strong><?php echo $adminOrderShort; ?></strong></td>
                            <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                            <td>
                                <small><?php echo htmlspecialchars($order['email']); ?></small><br>
                                <small><?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></small>
                            </td>
                            <td><strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                            <td><?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                switch($order['order_status']) {
                                    case 'pending': $status_class = 'warning'; break;
                                    case 'confirmed': $status_class = 'info'; break;
                                    case 'processing': $status_class = 'primary'; break;
                                    case 'shipped': $status_class = 'success'; break;
                                    case 'delivered': $status_class = 'success'; break;
                                    case 'cancelled': $status_class = 'danger'; break;
                                    default: $status_class = 'secondary';
                                }
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst($order['order_status']); ?></span>
                            </td>
                            <td>
                                <?php
                                $payment_class = '';
                                switch($order['payment_status']) {
                                    case 'pending': $payment_class = 'warning'; break;
                                    case 'completed': $payment_class = 'success'; break;
                                    case 'failed': $payment_class = 'danger'; break;
                                    default: $payment_class = 'secondary';
                                }
                                ?>
                                <span class="badge bg-<?php echo $payment_class; ?>"><?php echo ucfirst($order['payment_status']); ?></span>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $order['order_id']; ?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        
                        <?php ob_start(); ?>
                        <!-- Order Details Modal -->
                        <div class="modal fade" id="orderModal<?php echo $order['order_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <?php $modalShort = sprintf('%02d', $order['order_id'] % 100); ?>
                                        <h5 class="modal-title">Order <?php echo $modalShort; ?> Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h6>Customer Information</h6>
                                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                                <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Order Information</h6>
                                                <p class="mb-1"><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                                                <p class="mb-1"><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                                                <p class="mb-1"><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        
                                        <h6>Shipping Address</h6>
                                        <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                                        
                                        <h6>Order Items</h6>
                                        <?php
                                        $items_query = "SELECT oi.*, p.name, p.image_path 
                                                       FROM order_items oi 
                                                       INNER JOIN products p ON oi.product_id = p.product_id 
                                                       WHERE oi.order_id = " . $order['order_id'];
                                        $items_result = mysqli_query($conn, $items_query);
                                        ?>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                    <td><?php echo $item['quantity']; ?></td>
                                                    <td>₹<?php echo number_format($item['price_at_time'], 2); ?></td>
                                                    <td>₹<?php echo number_format($item['price_at_time'] * $item['quantity'], 2); ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                        
                                        <hr>
                                        <h6>Update Order Status</h6>
                                        <form method="POST" novalidate>
                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Order Status</label>
                                                    <select name="order_status" class="form-select" required>
                                                        <option value="pending" <?php echo $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="confirmed" <?php echo $order['order_status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="processing" <?php echo $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="shipped" <?php echo $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="cancelled" <?php echo $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Payment Status</label>
                                                    <select name="payment_status" class="form-select" required>
                                                        <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="completed" <?php echo $order['payment_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                        <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button type="submit" name="update_status" class="btn btn-primary">
                                                    <i class="fas fa-save me-2"></i>Update Status
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $order_modals_html .= ob_get_clean();
                            endwhile; // end while loop
                        ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                No orders found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Output buffered modals collected while rendering orders (if any)
if (!empty($order_modals_html)) echo $order_modals_html;
?>

<?php include 'footer.php'; ?>
