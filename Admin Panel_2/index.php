<?php include 'layout.php'; ?>
<h2 class="mb-4">Dashboard</h2>

<!-- Top Summary Cards with Actions -->
<div class="row g-4 mb-4">
    <!-- Categories -->
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Categories</h5>
                <p class="fs-4 fw-bold">10</p>
                <a href="manage_category.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Products -->
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Products</h5>
                <p class="fs-4 fw-bold">25</p>
                <a href="manage_products.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Users -->
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Users</h5>
                <p class="fs-4 fw-bold">100</p>
                <a href="manage_users.php" class="btn btn-light btn-sm">Manage</a>
            </div>
        </div>
    </div>
    <!-- Orders -->
    <div class="col-md-3">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Orders</h5>
                <p class="fs-4 fw-bold">50</p>
                <a href="#" class="btn btn-light btn-sm">View Orders</a>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h5 class="mb-3">Sales Analytics</h5>
            <canvas id="salesChart" height="150"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h5 class="mb-3">User Activity</h5>
            <canvas id="userChart" height="150"></canvas>
        </div>
    </div>
</div>

<!-- Quick Access Buttons -->
<div class="card p-4 shadow-sm mb-4">
    <h5 class="mb-3">Quick Actions</h5>
    <div class="d-flex flex-wrap gap-3">
        <a href="manage_category.php" class="btn btn-outline-primary">Add Category</a>
        <a href="manage_products.php" class="btn btn-outline-success">Add Product</a>
        <a href="manage_users.php" class="btn btn-outline-warning">Add User</a>
        <a href="settings.php" class="btn btn-outline-dark">Settings</a>
    </div>
</div>

<!-- Recent Activity with Stats -->
<div class="card p-4 shadow-sm">
    <h5 class="mb-3">Recent Activity & Statistics</h5>
    <div class="row">
        <!-- Stats Overview -->
        <div class="col-md-4">
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Users</span>
                    <span class="badge bg-primary">100</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Products</span>
                    <span class="badge bg-success">25</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Categories</span>
                    <span class="badge bg-info text-dark">10</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Orders</span>
                    <span class="badge bg-danger">50</span>
                </li>
            </ul>
        </div>
        <!-- Recent Actions -->
        <div class="col-md-8">
            <ul class="list-group">
                <li class="list-group-item">✅ New Product <b>"Blue T-Shirt"</b> added by Admin</li>
                <li class="list-group-item">👤 User <b>"John Doe"</b> registered</li>
                <li class="list-group-item">✏️ Category <b>"Women's Wear"</b> updated</li>
                <li class="list-group-item">📦 Order #12345 confirmed</li>
                <li class="list-group-item">🔒 Admin settings updated</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>