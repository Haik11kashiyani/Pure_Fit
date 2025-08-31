<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PureFit Cloths - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-3 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                ☰
            </button>
            <a class="navbar-brand" href="index.php">PureFit Admin</a>
            <div class="d-flex align-items-center">
                <img src="assets/images/logo.jpg" class="rounded-circle" width="40" height="40" alt="Admin">
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php" class="sidebar-link">Dashboard</a></li>
                <li class="nav-item"><a href="manage_categories.php" class="sidebar-link">Manage Categories</a></li>
                <li class="nav-item"><a href="manage_products.php" class="sidebar-link">Manage Products</a></li>
                <li class="nav-item"><a href="manage_users.php" class="sidebar-link">Manage Users</a></li>
                <li class="nav-item"><a href="settings.php" class="sidebar-link">Settings</a></li>
                <li class="nav-item"><a href="logout.php" class="sidebar-link">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar for Desktop -->
            <nav class="col-lg-2 d-none d-lg-block bg-dark text-white p-3" id="sidebar">
                <h3 class="text-center mb-4">PureFit Admin</h3>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="index.php" class="sidebar-link">Dashboard</a></li>
                    <li class="nav-item"><a href="manage_categories.php" class="sidebar-link">Manage Categories</a></li>
                    <li class="nav-item"><a href="manage_products.php" class="sidebar-link">Manage Products</a></li>
                    <li class="nav-item"><a href="manage_users.php" class="sidebar-link">Manage Users</a></li>
                    <li class="nav-item"><a href="settings.php" class="sidebar-link">Settings</a></li>
                    <li class="nav-item"><a href="logout.php" class="sidebar-link">Logout</a></li>
                </ul>
            </nav>

            <!-- Page Content -->
            <main class="col-lg-10 p-4 content-wrapper">