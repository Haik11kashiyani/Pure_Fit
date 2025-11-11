<?php
// Check authentication
include 'auth_check.php';
include '../connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PureFit Cloths - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #sidebar {
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar-link {
            padding: 10px 15px;
            display: block;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar-link:hover {
            background: #495057;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Ensure proper scaling when zoomed */
        main {
            width: 100%;
            overflow-x: auto;
        }

        /* Fix layout issues on zoom */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>
</head>

<body>

    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php" class="sidebar-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a href="manage_categories.php" class="sidebar-link"><i class="fas fa-folder me-2"></i>Categories</a></li>
                <li class="nav-item"><a href="manage_subcategories.php" class="sidebar-link"><i class="fas fa-folder-open me-2"></i>Subcategories</a></li>
                <li class="nav-item"><a href="manage_products.php" class="sidebar-link"><i class="fas fa-box me-2"></i>Products</a></li>
                <li class="nav-item"><a href="manage_orders.php" class="sidebar-link"><i class="fas fa-shopping-cart me-2"></i>Orders</a></li>
                <li class="nav-item"><a href="manage_users.php" class="sidebar-link"><i class="fas fa-users me-2"></i>Users</a></li>
                <li class="nav-item"><a href="manage_messages.php" class="sidebar-link"><i class="fas fa-envelope me-2"></i>Messages</a></li>
                <li class="nav-item"><a href="logout.php" class="sidebar-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar for Desktop -->
            <nav class="col-lg-2 d-none d-lg-block bg-dark text-white p-3" id="sidebar">
                <h3 class="text-center mb-4">PureFit Admin</h3>
                <div class="text-center mb-3 pb-3 border-bottom">
                    
                    <p class="mb-0 small"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></p>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="index.php" class="sidebar-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a href="manage_categories.php" class="sidebar-link"><i class="fas fa-folder me-2"></i>Categories</a></li>
                    <li class="nav-item"><a href="manage_subcategories.php" class="sidebar-link"><i class="fas fa-folder-open me-2"></i>Subcategories</a></li>
                    <li class="nav-item"><a href="manage_products.php" class="sidebar-link"><i class="fas fa-box me-2"></i>Products</a></li>
                    <li class="nav-item"><a href="manage_orders.php" class="sidebar-link"><i class="fas fa-shopping-cart me-2"></i>Orders</a></li>
                    <li class="nav-item"><a href="manage_users.php" class="sidebar-link"><i class="fas fa-users me-2"></i>Users</a></li>
                    <li class="nav-item mt-3"><a href="logout.php" class="sidebar-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </nav>

            <!-- Page Content -->
            <main class="col-lg-10 p-4 content-wrapper">