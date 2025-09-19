<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pure Fit Cloths</title>
    <link rel="stylesheet" href="assets/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                                    <a class="nav-link" href="cart.php">Cart</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="favorites.php">Favorite</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Login</a>
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
                            
                            
                            <li class="mb-2"><a href="aboutus.php">About us</a></li>
                            <li class="mb-2"><a href="contact.php">Contact us</a></li>
                            
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
                    
                    <div class="footer-copy">
                        &copy; 2025 Pure Fit Cloths. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Product Actions jQuery -->
    <script>
        // Cart and Favorites functionality using jQuery
        $(document).ready(function() {
            // Add to Cart functionality
            $('.add-to-cart').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const $productCard = $button.closest('.productcant');
                const productName = $productCard.find('h3').text();
                const productPrice = $productCard.find('p:last-child').text();
                
                // Add to cart logic (you can customize this)
                addToCart(productName, productPrice);
                
                // Visual feedback
                $button.html('<i class="fas fa-check"></i>');
                $button.css({
                    'background': '#28a745',
                    'color': 'white'
                });
                
                setTimeout(() => {
                    $button.html('<i class="fas fa-shopping-cart"></i>');
                    $button.css({
                        'background': 'rgba(255, 255, 255, 0.95)',
                        'color': '#636B2F'
                    });
                }, 1000);
            });

            // Add to Favorites functionality
            $('.add-to-favorites').on('click', function(e) {
                e.preventDefault();
                const $button = $(this);
                const $productCard = $button.closest('.productcant');
                const productName = $productCard.find('h3').text();
                const productPrice = $productCard.find('p:last-child').text();
                
                // Add to favorites logic (you can customize this)
                addToFavorites(productName, productPrice);
                
                // Visual feedback
                $button.html('<i class="fas fa-heart-fill"></i>');
                $button.css({
                    'background': '#e74c3c',
                    'color': 'white'
                });
                
                setTimeout(() => {
                    $button.html('<i class="fas fa-heart"></i>');
                    $button.css({
                        'background': 'rgba(255, 255, 255, 0.95)',
                        'color': '#e74c3c'
                    });
                }, 1000);
            });
        });

        function addToCart(productName, productPrice) {
            // You can implement your cart logic here
            console.log('Added to cart:', productName, productPrice);
            // Example: Store in localStorage, send to server, etc.
            showNotification('Product added to cart!', 'success');
        }

        function addToFavorites(productName, productPrice) {
            // You can implement your favorites logic here
            console.log('Added to favorites:', productName, productPrice);
            // Example: Store in localStorage, send to server, etc.
            showNotification('Product added to favorites!', 'success');
        }

        function showNotification(message, type) {
            // Create notification element using jQuery
            const $notification = $('<div>')
                .addClass(`alert alert-${type} position-fixed`)
                .css({
                    'top': '20px',
                    'right': '20px',
                    'z-index': '9999',
                    'min-width': '300px'
                })
                .html(message);
            
            $('body').append($notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>
</body>
</html>
