<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
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
    
    <!-- Google Translate -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,gu,es,fr,de,ar,zh-CN,ja,ko',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    <style>
        /* Hide Google Translate banner and frame */
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }
        body {
            top: 0px !important;
            position: static !important;
        }
        .skiptranslate iframe {
            display: none !important;
        }
        iframe.skiptranslate {
            display: none !important;
        }
        body > .skiptranslate {
            display: none !important;
        }
        .goog-te-banner-frame {
            display: none !important;
        }
        #goog-gt-tt, .goog-te-balloon-frame {
            display: none !important;
        }
        .goog-text-highlight {
            background: none !important;
            box-shadow: none !important;
        }
        /* Google Translate widget styling */
        #google_translate_element,
        #google_translate_element_profile {
            display: block;
        }
        #google_translate_element select,
        #google_translate_element_profile select {
            border: 2px solid #636B2F !important;
            border-radius: 10px !important;
            padding: 12px 20px !important;
            font-size: 16px !important;
            color: #3D4127 !important;
            background: white !important;
            cursor: pointer !important;
            outline: none !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        #google_translate_element select:hover,
        #google_translate_element_profile select:hover {
            background: #f8f9fa !important;
            border-color: #3D4127 !important;
        }
        .goog-te-gadget {
            font-family: inherit !important;
        }
        .goog-te-gadget-simple {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            font-size: 16px !important;
        }
        .goog-te-gadget-icon {
            display: none !important;
        }
        .goog-logo-link {
            display: none !important;
        }
        .goog-te-gadget span {
            display: none !important;
        }
        .goog-te-combo {
            margin: 0 !important;
        }
        /* Force show select element */
        .goog-te-gadget select.goog-te-combo {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <section class="header-section">
        <header>
            <div class="nav-container">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <div class="container-fluid px-3 px-md-4">
                        <!-- Brand/Logo for mobile -->
                        <a class="navbar-brand d-lg-none fw-bold" href="index.php" style="color: #f1f8cc;">Pure Fit</a>
                        
                        <!-- Hamburger toggler -->
                        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <!-- Collapsible navbar -->
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto align-items-lg-center">
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="index.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="products.php">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="cart.php">
                                        <i class="fas fa-shopping-cart d-lg-none me-2"></i>Cart
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="favorites.php">
                                        <i class="fas fa-heart d-lg-none me-2"></i>Favorites
                                    </a>
                                </li>
                                <?php if (!empty($_SESSION['user_id'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="profile.php">
                                        <i class="fas fa-user d-lg-none me-2"></i>Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="logout.php">
                                        <i class="fas fa-sign-out-alt d-lg-none me-2"></i>Logout
                                    </a>
                                </li>
                                <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link px-2 px-lg-3 py-2" href="login.php">
                                        <i class="fas fa-sign-in-alt d-lg-none me-2"></i>Login
                                    </a>
                                </li>
                                <?php endif; ?>
                                <!-- Google Translate Widget -->
                                <li class="nav-item ms-lg-2">
                                    <div id="google_translate_element" class="d-inline-block"></div>
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
                        <p>Phone: +91 234 567 8901</p>
                        <p>Location: Rajkot, Gujarat, India</p>
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

    <!-- Product Actions with AJAX -->
    <script>
        $(document).ready(function() {
            // Add to Cart functionality
            $(document).on('click', '.add-to-cart', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $button = $(this);
                
                // Find product ID - try multiple methods
                let productId = null;
                
                // Method 1: Check if button has data-product-id attribute
                productId = $button.data('product-id');
                
                // Method 2: Find closest link with product ID in href
                if (!productId) {
                    const $link = $button.closest('.productcant').find('a[href*="product-details.php"]').first();
                    if ($link.length) {
                        const match = $link.attr('href').match(/id=(\d+)/);
                        if (match) productId = match[1];
                    }
                }
                
                // Method 3: Check parent link
                if (!productId) {
                    const $parentLink = $button.closest('a[href*="product-details.php"]');
                    if ($parentLink.length) {
                        const match = $parentLink.attr('href').match(/id=(\d+)/);
                        if (match) productId = match[1];
                    }
                }
                
                if (!productId) {
                    showNotification('Error: Product ID not found', 'danger');
                    console.error('Could not find product ID for cart');
                    return;
                }
                
                // Show loading state
                const originalHTML = $button.html();
                $button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                
                $.ajax({
                    url: 'ajax_cart.php',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        action: 'add',
                        quantity: 1
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Success feedback
                            $button.html('<i class="fas fa-check"></i>');
                            $button.css({
                                'background': '#28a745',
                                'color': 'white'
                            });
                            showNotification(response.message, 'success');
                            
                            // Reset after 1.5 seconds
                            setTimeout(() => {
                                $button.html(originalHTML);
                                $button.css({
                                    'background': 'rgba(255, 255, 255, 0.95)',
                                    'color': '#636B2F'
                                }).prop('disabled', false);
                            }, 1500);
                        } else {
                            $button.html(originalHTML).prop('disabled', false);
                            showNotification(response.message, 'warning');
                        }
                    },
                    error: function() {
                        $button.html(originalHTML).prop('disabled', false);
                        showNotification('Error adding to cart', 'danger');
                    }
                });
            });

            // Add to Favorites functionality
            $(document).on('click', '.add-to-favorites', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $button = $(this);
                
                // Find product ID - try multiple methods
                let productId = null;
                
                // Method 1: Check if button has data-product-id attribute
                productId = $button.data('product-id');
                
                // Method 2: Find closest link with product ID in href
                if (!productId) {
                    const $link = $button.closest('.productcant').find('a[href*="product-details.php"]').first();
                    if ($link.length) {
                        const match = $link.attr('href').match(/id=(\d+)/);
                        if (match) productId = match[1];
                    }
                }
                
                // Method 3: Check parent link
                if (!productId) {
                    const $parentLink = $button.closest('a[href*="product-details.php"]');
                    if ($parentLink.length) {
                        const match = $parentLink.attr('href').match(/id=(\d+)/);
                        if (match) productId = match[1];
                    }
                }
                
                if (!productId) {
                    showNotification('Error: Product ID not found', 'danger');
                    console.error('Could not find product ID');
                    return;
                }
                
                // Check if already favorited
                const isFavorited = $button.hasClass('favorited');
                const action = isFavorited ? 'remove' : 'add';
                
                // Show loading state
                const originalHTML = $button.html();
                $button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                
                $.ajax({
                    url: 'ajax_favorites.php',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        action: action
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (response.action === 'added') {
                                // Added to favorites
                                $button.addClass('favorited');
                                $button.html('<i class="fas fa-heart"></i>');
                                $button.css({
                                    'background': '#e74c3c',
                                    'color': 'white'
                                });
                                showNotification(response.message, 'success');
                            } else {
                                // Removed from favorites
                                $button.removeClass('favorited');
                                $button.html('<i class="fas fa-heart"></i>');
                                $button.css({
                                    'background': 'rgba(255, 255, 255, 0.95)',
                                    'color': '#e74c3c'
                                });
                                showNotification(response.message, 'success');
                                
                                // If on favorites page, remove the card
                                if (window.location.pathname.includes('favorites.php')) {
                                    $button.closest('.col-12').fadeOut(300, function() {
                                        $(this).remove();
                                        // Check if no more favorites
                                        if ($('.productcant').length === 0) {
                                            location.reload();
                                        }
                                    });
                                }
                            }
                            $button.prop('disabled', false);
                        } else {
                            $button.html(originalHTML).prop('disabled', false);
                            showNotification(response.message, 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        $button.html(originalHTML).prop('disabled', false);
                        showNotification('Error updating favorites', 'danger');
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });

        function showNotification(message, type) {
            const $notification = $('<div>')
                .addClass(`alert alert-${type} alert-dismissible fade show position-fixed`)
                .css({
                    'top': '80px',
                    'right': '20px',
                    'z-index': '9999',
                    'min-width': '300px',
                    'box-shadow': '0 4px 12px rgba(0,0,0,0.15)'
                })
                .html(`
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `);
            
            $('body').append($notification);
            
            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>
    <?php
        // Central flash message renderer: if server set flash messages in session,
        // render them using the page's showNotification JS so all messages are consistent.
        $flashKeys = [
            'success_message' => 'success',
            'error_message' => 'danger',
            'success' => 'success',
            'error' => 'danger',
            'order_success' => 'success',
            'order_error' => 'warning',
            'message' => 'info'
        ];

        foreach ($flashKeys as $key => $type) {
            if (!empty($_SESSION[$key])) {
                // Use json_encode to ensure proper escaping
                $msg = json_encode($_SESSION[$key]);
                echo "<script>document.addEventListener('DOMContentLoaded', function(){ try{ showNotification($msg, '$type'); } catch(e){ console.error('showNotification not available', e); } });</script>\n";
                unset($_SESSION[$key]);
            }
        }
    ?>
</body>
</html>
