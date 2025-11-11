<?php include 'layout.php'; 

// Get statistics from database
$stats = [];

// Count categories
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM categories WHERE is_active = 1");
$stats['categories'] = mysqli_fetch_assoc($result)['count'];

// Count products
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE is_active = 1");
$stats['products'] = mysqli_fetch_assoc($result)['count'];

// Count users
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE is_active = 1");
$stats['users'] = mysqli_fetch_assoc($result)['count'];

// Count orders
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$stats['orders'] = mysqli_fetch_assoc($result)['count'];

// Get total revenue
$result = mysqli_query($conn, "SELECT SUM(total_amount) as revenue FROM orders WHERE payment_status = 'completed'");
$stats['revenue'] = mysqli_fetch_assoc($result)['revenue'] ?? 0;

// Get pending orders
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'");
$stats['pending_orders'] = mysqli_fetch_assoc($result)['count'];

// Count messages
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages");
$stats['messages'] = mysqli_fetch_assoc($result)['count'];

// Count unread messages
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$stats['unread_messages'] = mysqli_fetch_assoc($result)['count'];

// Get recent orders
$recent_orders_query = "SELECT o.order_id, o.total_amount, o.order_status, o.created_at, u.first_name, u.last_name 
                        FROM orders o 
                        INNER JOIN users u ON o.user_id = u.user_id 
                        ORDER BY o.created_at DESC LIMIT 5";
$recent_orders = mysqli_query($conn, $recent_orders_query);
?>
<h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>

<!-- Top Summary Cards with Actions -->
<div class="row g-4 mb-4">
    <!-- Categories -->
    <div class="col-md-3">
        <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #636B2F, #3D4127);">
            <div class="card-body text-center">
                <i class="fas fa-folder fa-3x mb-2"></i>
                <h5 class="card-title">Categories</h5>
                <p class="fs-4 fw-bold"><?php echo $stats['categories']; ?></p>
                <a href="manage_categories.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Products -->
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-box fa-3x mb-2"></i>
                <h5 class="card-title">Products</h5>
                <p class="fs-4 fw-bold"><?php echo $stats['products']; ?></p>
                <a href="manage_products.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Users -->
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-2"></i>
                <h5 class="card-title">Users</h5>
                <p class="fs-4 fw-bold"><?php echo $stats['users']; ?></p>
                <a href="manage_users.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Orders -->
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-3x mb-2"></i>
                <h5 class="card-title">Orders</h5>
                <p class="fs-4 fw-bold"><?php echo $stats['orders']; ?></p>
                <a href="manage_orders.php" class="btn btn-light btn-sm">View Orders</a>
            </div>
        </div>
    </div>
    <!-- Messages -->
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-3x mb-2"></i>
                <h5 class="card-title">Messages</h5>
                <p class="fs-4 fw-bold">
                    <?php echo $stats['messages']; ?>
                    <?php if ($stats['unread_messages'] > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $stats['unread_messages']; ?> new</span>
                    <?php endif; ?>
                </p>
                <a href="manage_messages.php" class="btn btn-light btn-sm">View Messages</a>
            </div>
        </div>
    </div>
</div>

<!-- Revenue & Pending Orders -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-rupee-sign fa-3x mb-2"></i>
                <h5 class="card-title">Total Revenue</h5>
                <p class="fs-4 fw-bold">₹<?php echo number_format($stats['revenue'], 2); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-secondary shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-2"></i>
                <h5 class="card-title">Pending Orders</h5>
                <p class="fs-4 fw-bold"><?php echo $stats['pending_orders']; ?></p>
                <a href="manage_orders.php?status=pending" class="btn btn-light btn-sm">View</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card p-4 shadow-sm">
    <h5 class="mb-3"><i class="fas fa-shopping-cart me-2"></i>Recent Orders</h5>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                    <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                    <tr>
                        <td>#<?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
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
                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="manage_orders.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No orders yet</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-end mt-3">
        <a href="manage_orders.php" class="btn btn-primary">View All Orders <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
</div>

<?php include 'footer.php'; ?>