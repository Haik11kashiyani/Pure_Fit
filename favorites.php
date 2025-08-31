<?php
ob_start();
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                    My Favorites
                </h1>
                <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                    Your saved items for future purchases
                </p>
            </div>

            <!-- Favorites Grid -->
            <div class="row g-4">
                <!-- Favorite Item 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Premium Workout Tank" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="favorite-badge position-absolute top-0 start-0 m-3">
                                <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #636B2F, #3D4127); color: white; font-size: 0.8rem;">
                                    Best Seller
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Premium Workout Tank
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                High-performance moisture-wicking fabric with breathable mesh panels for ultimate comfort during intense workouts.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold mb-0" style="color: #636B2F;">$49.99</span>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(128)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Performance Leggings" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="favorite-badge position-absolute top-0 start-0 m-3">
                                <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; font-size: 0.8rem;">
                                    Sale
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Performance Leggings
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Compression-fit leggings with four-way stretch and built-in support for high-impact activities.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <span class="h5 fw-bold mb-0 me-2" style="color: #e74c3c;">$59.99</span>
                                    <span class="text-decoration-line-through" style="color: #636B2F; font-size: 0.9rem;">$79.99</span>
                                </div>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="far fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(95)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Sports Bra" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Sports Bra
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                High-support sports bra with adjustable straps and moisture-wicking technology for maximum comfort.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold mb-0" style="color: #636B2F;">$39.99</span>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(156)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 4 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Running Shorts" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Running Shorts
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Lightweight running shorts with built-in liner and reflective details for safety during night runs.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold mb-0" style="color: #636B2F;">$34.99</span>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="far fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(87)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 5 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Hoodie" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Performance Hoodie
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Warm and breathable hoodie perfect for pre and post-workout sessions in cooler weather.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold mb-0" style="color: #636B2F;">$69.99</span>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(203)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 6 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card favorite-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="favorite-image-container position-relative">
                            <img src="assets/products/1.png" alt="Yoga Mat" class="img-fluid w-100" style="height: 250px; object-fit: cover;">
                            <div class="favorite-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Premium Yoga Mat
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Non-slip yoga mat with perfect thickness for comfort and stability during all types of yoga practice.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 fw-bold mb-0" style="color: #636B2F;">$89.99</span>
                                <div class="rating">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(167)</span>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <button class="btn w-100 py-2 rounded-pill fw-bold text-white mb-2" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    Add to Cart
                                </button>
                                <button class="btn w-100 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State (Hidden by default) -->
            <div class="text-center py-5" id="empty-favorites" style="display: none;">
                <i class="fas fa-heart-broken fa-4x mb-4" style="color: #D4DE95;"></i>
                <h3 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                    No Favorites Yet
                </h3>
                <p class="mb-4" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                    Start building your wishlist by browsing our products and adding items you love!
                </p>
                <a href="#" class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                    Browse Products
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.favorite-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.favorite-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.2) !important;
}

.favorite-image-container {
    overflow: hidden;
}

.favorite-image-container img {
    transition: transform 0.5s ease;
}

.favorite-card:hover .favorite-image-container img {
    transform: scale(1.1);
}

.favorite-overlay button {
    transition: all 0.3s ease;
}

.favorite-overlay button:hover {
    background: #e74c3c !important;
    color: white !important;
    transform: scale(1.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.rating i {
    transition: transform 0.2s ease;
}

.rating:hover i {
    transform: scale(1.2);
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
