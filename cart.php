<?php
ob_start();
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                    Shopping Cart
                </h1>
                <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                    Review your items and proceed to checkout
                </p>
            </div>

            <div class="row g-5">
                <!-- Cart Items -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <h3 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Your Items (3)
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Cart Item 1 -->
                            <div class="cart-item mb-4 p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="assets/products/1.png" alt="Product 1" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <h6 class="fw-bold mb-1" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                            Premium Workout Tank
                                        </h6>
                                        <p class="mb-1" style="color: #636B2F; font-size: 0.9rem;">
                                            Size: M | Color: Black
                                        </p>
                                        <p class="mb-0 fw-bold" style="color: #636B2F;">
                                            $49.99
                                        </p>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <div class="quantity-controls d-flex align-items-center">
                                            <button class="btn btn-sm rounded-circle me-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-minus fa-xs"></i>
                                            </button>
                                            <span class="fw-bold" style="color: #3D4127; min-width: 30px; text-align: center;">1</span>
                                            <button class="btn btn-sm rounded-circle ms-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-plus fa-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-end mt-3 mt-md-0">
                                        <button class="btn btn-sm text-danger" style="background: none; border: none;">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Item 2 -->
                            <div class="cart-item mb-4 p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="assets/products/1.png" alt="Product 2" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <h6 class="fw-bold mb-1" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                            Performance Leggings
                                        </h6>
                                        <p class="mb-1" style="color: #636B2F; font-size: 0.9rem;">
                                            Size: S | Color: Navy
                                        </p>
                                        <p class="mb-0 fw-bold" style="color: #636B2F;">
                                            $79.99
                                        </p>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <div class="quantity-controls d-flex align-items-center">
                                            <button class="btn btn-sm rounded-circle me-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-minus fa-xs"></i>
                                            </button>
                                            <span class="fw-bold" style="color: #3D4127; min-width: 30px; text-align: center;">2</span>
                                            <button class="btn btn-sm rounded-circle ms-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-plus fa-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-end mt-3 mt-md-0">
                                        <button class="btn btn-sm text-danger" style="background: none; border: none;">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Item 3 -->
                            <div class="cart-item mb-4 p-3 rounded-3" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="assets/products/1.png" alt="Product 3" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <h6 class="fw-bold mb-1" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                            Sports Bra
                                        </h6>
                                        <p class="mb-1" style="color: #636B2F; font-size: 0.9rem;">
                                            Size: 34B | Color: Gray
                                        </p>
                                        <p class="mb-0 fw-bold" style="color: #636B2F;">
                                            $39.99
                                        </p>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <div class="quantity-controls d-flex align-items-center">
                                            <button class="btn btn-sm rounded-circle me-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-minus fa-xs"></i>
                                            </button>
                                            <span class="fw-bold" style="color: #3D4127; min-width: 30px; text-align: center;">1</span>
                                            <button class="btn btn-sm rounded-circle ms-2" style="background: #D4DE95; color: #636B2F; border: none; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-plus fa-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 text-end mt-3 mt-md-0">
                                        <button class="btn btn-sm text-danger" style="background: none; border: none;">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Continue Shopping -->
                            <div class="text-center mt-4">
                                <a href="#" class="btn btn-outline-secondary px-4 py-2 rounded-pill" style="border-color: #D4DE95; color: #636B2F; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease;">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <h3 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Order Summary
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #636B2F; font-family: 'Montserrat', sans-serif;">Subtotal (4 items):</span>
                                <span class="fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">$209.96</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #636B2F; font-family: 'Montserrat', sans-serif;">Shipping:</span>
                                <span class="fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">$9.99</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span style="color: #636B2F; font-family: 'Montserrat', sans-serif;">Tax:</span>
                                <span class="fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">$18.90</span>
                            </div>
                            <hr style="border-color: #D4DE95;">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="h5 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Total:</span>
                                <span class="h5 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">$238.85</span>
                            </div>
                            
                            <button class="btn w-100 py-3 rounded-pill fw-bold text-white mb-3" 
                                    style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif; letter-spacing: 1px;">
                                Proceed to Checkout
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">
                                    <i class="fas fa-lock me-2"></i>
                                    Secure checkout powered by Stripe
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Promo Code -->
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-4" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Have a Promo Code?
                            </h5>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control border-0 py-2" placeholder="Enter code" 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                <button class="btn" style="background: #D4DE95; color: #636B2F; border: none;">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(99, 107, 47, 0.15) !important;
}

.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
}

.quantity-controls button {
    transition: all 0.3s ease;
}

.quantity-controls button:hover {
    background: #636B2F !important;
    color: #ffffff !important;
    transform: scale(1.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-control:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

/* Animation for page elements */
.container-fluid > * {
    animation: fadeInUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

.container-fluid > *:nth-child(1) { animation-delay: 0.1s; }
.container-fluid > *:nth-child(2) { animation-delay: 0.2s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card {
        margin: 1rem;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
