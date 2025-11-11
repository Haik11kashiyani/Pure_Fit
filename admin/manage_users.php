<?php 
include 'layout.php';

// Handle user actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $role_id = (int)$_POST['role_id'];
        
        $query = "INSERT INTO users (username, email, password, first_name, last_name, phone, role_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $username, $email, $password, $first_name, $last_name, $phone, $role_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "User added successfully!";
        } else {
            $error_msg = "Error adding user: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
    
    if (isset($_POST['edit_user'])) {
        $user_id = (int)$_POST['user_id'];
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $role_id = (int)$_POST['role_id'];
        $is_active = (int)$_POST['is_active'];
        
        $query = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ?, phone = ?, role_id = ?, is_active = ? 
                  WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssii', $username, $email, $first_name, $last_name, $phone, $role_id, $is_active, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "User updated successfully!";
        } else {
            $error_msg = "Error updating user.";
        }
        mysqli_stmt_close($stmt);
    }
    
    if (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['user_id'];
        $query = "UPDATE users SET is_active = 0 WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "User deactivated successfully!";
        } else {
            $error_msg = "Error deactivating user.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Get filter parameters
$role_filter = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Build query
$query = "SELECT u.*, r.role_name FROM users u 
          INNER JOIN roles r ON u.role_id = r.role_id 
          WHERE 1=1";

if ($role_filter) {
    $query .= " AND r.role_name = '$role_filter'";
}

if ($search) {
    $query .= " AND (u.username LIKE '%$search%' OR u.email LIKE '%$search%' OR u.first_name LIKE '%$search%' OR u.last_name LIKE '%$search%')";
}

$query .= " ORDER BY u.created_at DESC";
$users_result = mysqli_query($conn, $query);

// Get roles
$roles_result = mysqli_query($conn, "SELECT * FROM roles");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i>Manage Users</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus me-2"></i>Add New User
    </button>
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <a href="manage_users.php" class="btn btn-<?php echo empty($role_filter) ? 'primary' : 'outline-primary'; ?>">
                        All Users
                    </a>
                    <a href="manage_users.php?role=admin" class="btn btn-<?php echo $role_filter == 'admin' ? 'danger' : 'outline-danger'; ?>">
                        Admins
                    </a>
                    <a href="manage_users.php?role=customer" class="btn btn-<?php echo $role_filter == 'customer' ? 'success' : 'outline-success'; ?>">
                        Customers
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <form method="GET" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($users_result) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $user['role_name'] == 'admin' ? 'danger' : 'success'; ?>">
                                    <?php echo ucfirst($user['role_name']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['user_id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($user['user_id'] != $_SESSION['admin_id']): ?>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo $user['user_id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        
                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal<?php echo $user['user_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select name="role_id" class="form-select" required>
                                                    <?php 
                                                    mysqli_data_seek($roles_result, 0);
                                                    while ($role = mysqli_fetch_assoc($roles_result)): 
                                                    ?>
                                                    <option value="<?php echo $role['role_id']; ?>" <?php echo $user['role_id'] == $role['role_id'] ? 'selected' : ''; ?>>
                                                        <?php echo ucfirst($role['role_name']); ?>
                                                    </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="is_active" class="form-select" required>
                                                    <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal<?php echo $user['user_id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Deactivate User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <p>Are you sure you want to deactivate user <strong><?php echo htmlspecialchars($user['username']); ?></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="delete_user" class="btn btn-danger">Deactivate</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" required>
                            <?php 
                            mysqli_data_seek($roles_result, 0);
                            while ($role = mysqli_fetch_assoc($roles_result)): 
                            ?>
                            <option value="<?php echo $role['role_id']; ?>">
                                <?php echo ucfirst($role['role_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
