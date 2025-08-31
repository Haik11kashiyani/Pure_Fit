<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pure Fit Cloths</title>
    <link rel="stylesheet" href="assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    
    
</head>

<body>
    <!-- Header Section -->
    <section class="header-section">
        <header>
            <div class="nav-container">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="products.php">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="aboutus.php">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact Us</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="cart.php">Cart</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="favorites.php">Favorite</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="register.php">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">Profile</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>


<?php
        if(isset($contant))
        {
                echo $contant;
        }
?>


    <!-- Footer -->
    <footer class="footer rounded py-5 mt-5">
        <div class="container">
            <div class="footer-content">
                <div class="footer-main row pb-4 mb-3">
                    <div class="footer-brand col-12 col-md-4 mb-4">
                        <h2>Pure Fit Cloths</h2>
                        <p>
                            Pure Fit Cloths is your go-to destination for the latest in fitness apparel. We offer a wide
                            range of high-quality, stylish, and comfortable clothing designed to enhance your workout experience.
                        </p>
                    </div>
                    <div class="footer-links col-12 col-md-4 mb-4">
                        <h4>Quick Links</h4>
                        <ul class="list-unstyled p-0 m-0">
                            <li class="mb-2"><a href="index.php">Home</a></li>
                            <li class="mb-2"><a href="products.php">Products</a></li>
                            <li class="mb-2"><a href="aboutus.php">About us</a></li>
                            <li class="mb-2"><a href="contact.php">Contact us</a></li>
                            <li class="mb-2"><a href="cart.php">Cart</a></li>
                            <li class="mb-2"><a href="favorites.php">Favorite</a></li>
                            <li class="mb-2"><a href="login.php">Login</a></li>
                            <li class="mb-2"><a href="register.php">Register</a></li>
                            <li class="mb-2"><a href="profile.php">Profile</a></li>
                        </ul>
                    </div>
                    <div class="footer-contact col-12 col-md-4 mb-4">
                        <h4>Contact</h4>
                        <p>Email: info@purefitcloths.com</p>
                        <p>Phone: +1 234 567 8901</p>
                        <p>Location: Your City, Country</p>
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3">
                    <div class="mb-2 mb-md-0">
                        <a href="#" class="me-3">Facebook</a>
                        <a href="#" class="me-3">Instagram</a>
                        <a href="#" class="me-3">Twitter</a>
                    </div>
                    <div class="mb-2 mb-md-0">
                        <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a>
                    </div>
                    <div class="footer-copy">
                        &copy; 2025 Pure Fit Cloths. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>