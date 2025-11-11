<?php
// layout.php - Main layout template for Admin Panel
// Usage in other pages (e.g., dashboard.php):
// ob_start();
// // Your page-specific content here
// $content = ob_get_clean();
// $pageTitle = 'Dashboard'; // Optional: Set custom page title
// include 'layout.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin Panel' : 'Admin Panel'; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <!-- Custom Styles for a modern, designful look -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 56px;
            /* Adjust for navbar height */
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #495057;
        }

        .sidebar .nav-item .nav-icon {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .footer {
            margin-left: 250px;
            background-color: #343a40;
            color: #adb5bd;
            padding: 15px;
            text-align: center;
            position: relative;
            bottom: 0;
            width: calc(100% - 250px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .sidebar.active {
                width: 250px;
            }

            .main-content,
            .footer {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/30" class="rounded-circle" alt="User"> Admin User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_categorys.php">
                    <span class="nav-icon">👥</span> Manage Category
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_products.php">
                    <span class="nav-icon">👥</span> Manage Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_users.php">
                    <span class="nav-icon">👥</span> Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <span class="nav-icon">⚙️</span> Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reports.php">
                    <span class="nav-icon">📊</span> Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <span class="nav-icon">🚪</span> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">
        <?php echo isset($content) ? $content : '<p>No content provided.</p>'; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> Admin Panel. All rights reserved. | Version 1.0
    </footer>

    <!-- Bootstrap JS -->
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Sidebar toggle for mobile -->
    <script>
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>