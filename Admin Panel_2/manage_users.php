<?php include 'layout.php'; ?>
<div class="container-fluid py-4">
    <h2 class="text-center text-primary mb-4">Manage Users</h2>

    <!-- Search & Filter Card -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Search & Filter</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-lg-4 col-md-6">
                    <input type="text" id="searchUser" class="form-control" placeholder="Search by name or email">
                </div>

                <!-- Role Filter -->
                <div class="col-lg-3 col-md-6">
                    <select id="filterRole" class="form-select">
                        <option value="">Filter by Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Customer">Customer</option>
                        <option value="Seller">Seller</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-lg-3 col-md-6">
                    <select id="filterStatus" class="form-select">
                        <option value="">Filter by Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                </div>

                <!-- Apply Button -->
                <div class="col-lg-2 col-md-6">
                    <button class="btn btn-success w-100" onclick="filterUsers()">
                        <i class="bi bi-funnel"></i> Apply
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="card shadow-lg">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">User List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Registered On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td><span class="badge bg-primary">Admin</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2025-08-28 14:35</td>
                            <td>2025-08-01</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewUser(1)">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Mary Smith</td>
                            <td>mary@example.com</td>
                            <td><span class="badge bg-warning text-dark">Customer</span></td>
                            <td><span class="badge bg-secondary">Inactive</span></td>
                            <td>2025-08-25 10:12</td>
                            <td>2025-07-15</td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewUser(2)">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <!-- Dynamic rows can be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>User ID</th>
                            <td id="modalUserId"></td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td id="modalUserName"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="modalUserEmail"></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td id="modalUserRole"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="modalUserStatus"></td>
                        </tr>
                        <tr>
                            <th>Last Login</th>
                            <td id="modalLastLogin"></td>
                        </tr>
                        <tr>
                            <th>Registered On</th>
                            <td id="modalRegistered"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function viewUser(userId) {
        const users = {
            1: {
                id: 1,
                name: 'John Doe',
                email: 'john@example.com',
                role: 'Admin',
                status: 'Active',
                lastLogin: '2025-08-28 14:35',
                registered: '2025-08-01'
            },
            2: {
                id: 2,
                name: 'Mary Smith',
                email: 'mary@example.com',
                role: 'Customer',
                status: 'Inactive',
                lastLogin: '2025-08-25 10:12',
                registered: '2025-07-15'
            }
        };

        const user = users[userId];
        document.getElementById('modalUserId').innerText = user.id;
        document.getElementById('modalUserName').innerText = user.name;
        document.getElementById('modalUserEmail').innerText = user.email;
        document.getElementById('modalUserRole').innerText = user.role;
        document.getElementById('modalUserStatus').innerText = user.status;
        document.getElementById('modalLastLogin').innerText = user.lastLogin;
        document.getElementById('modalRegistered').innerText = user.registered;

        new bootstrap.Modal(document.getElementById('userModal')).show();
    }

    function filterUsers() {
        alert("Filter functionality can be implemented with AJAX or JS");
    }
</script>

<?php include 'footer.php'; ?>