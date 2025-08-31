<?php
ob_start();
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php" style="color: #636B2F; text-decoration: none;">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="products.php" style="color: #636B2F; text-decoration: none;">Products</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #3D4127; font-weight: 600;">
                        Premium Workout Tank
                    </li>
                </ol>
            </nav>

            <div class="row g-5">
                <!-- Product Images -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-0">
                            <div class="main-image-container position-relative">
                                <img src="assets/products/1.png" alt="Premium Workout Tank" class="img-fluid w-100" id="mainImage" style="height: 500px; object-fit: cover;">
                                <div class="product-badge position-absolute top-0 start-0 m-3">
                                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #636B2F, #3D4127); color: white; font-size: 0.9rem;">
                                        Best Seller
                                    </span>
                                </div>
                            </div>
                            <div class="thumbnail-images d-flex justify-content-center gap-2 p-3">
                                <img src="assets/products/1.png" alt="Thumbnail 1" class="img-thumbnail thumbnail-img" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid #D4DE95; transition: all 0.3s ease;">
                                <img src="assets/products/1.png" alt="Thumbnail 2" class="img-thumbnail thumbnail-img" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                                <img src="assets/products/1.png" alt="Thumbnail 3" class="img-thumbnail thumbnail-img" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                                <img src="assets/products/1.png" alt="Thumbnail 4" class="img-thumbnail thumbnail-img" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4">
                            <h1 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 1px;">
                                Premium Workout Tank
                            </h1>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating me-3">
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <i class="fas fa-star" style="color: #f39c12;"></i>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">(128 reviews)</span>
                                </div>
                                <span class="badge rounded-pill px-3 py-2" style="background: #D4DE95; color: #636B2F; font-size: 0.8rem;">
                                    In Stock
                                </span>
                            </div>
                            
                            <div class="price-section mb-4">
                                <span class="h2 fw-bold" style="color: #636B2F;">$49.99</span>
                                <span class="text-decoration-line-through ms-2" style="color: #636B2F; font-size: 1.1rem;">$69.99</span>
                                <span class="badge bg-danger ms-2">Save 29%</span>
                            </div>
                            
                            <p class="mb-4" style="color: #636B2F; font-family: 'Montserrat', sans-serif; line-height: 1.6;">
                                Experience ultimate comfort and performance with our Premium Workout Tank. Made from high-quality moisture-wicking fabric with breathable mesh panels, this tank top is designed to keep you cool and dry during even the most intense workouts. The ergonomic fit ensures freedom of movement while the stylish design keeps you looking great both in and out of the gym.
                            </p>
                            
                            <!-- Size Selection -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Select Size:</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn size-btn" style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; min-width: 50px; transition: all 0.3s ease;">XS</button>
                                    <button class="btn size-btn" style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; min-width: 50px; transition: all 0.3s ease;">S</button>
                                    <button class="btn size-btn active" style="background: #636B2F; color: white; border: 2px solid #636B2F; min-width: 50px; transition: all 0.3s ease;">M</button>
                                    <button class="btn size-btn" style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; min-width: 50px; transition: all 0.3s ease;">L</button>
                                    <button class="btn size-btn" style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; min-width: 50px; transition: all 0.3s ease;">XL</button>
                                </div>
                            </div>
                            
                            <!-- Color Selection -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Select Color:</h6>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="color-option active" style="width: 30px; height: 30px; border-radius: 50%; background: #000000; border: 3px solid #636B2F; cursor: pointer; transition: all 0.3s ease;"></div>
                                    <div class="color-option" style="width: 30px; height: 30px; border-radius: 50%; background: #ffffff; border: 3px solid #D4DE95; cursor: pointer; transition: all 0.3s ease;"></div>
                                    <div class="color-option" style="width: 30px; height: 30px; border-radius: 50%; background: #808080; border: 3px solid #D4DE95; cursor: pointer; transition: all 0.3s ease;"></div>
                                    <span class="ms-2" style="color: #636B2F; font-size: 0.9rem;">Black</span>
                                </div>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Quantity:</h6>
                                <div class="d-flex align-items-center">
                                    <button class="btn rounded-circle me-3" style="background: #D4DE95; color: #636B2F; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="fw-bold mx-3" style="color: #3D4127; font-size: 1.2rem; min-width: 30px; text-align: center;">1</span>
                                    <button class="btn rounded-circle ms-3" style="background: #D4DE95; color: #636B2F; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-3 mb-4">
                                <button class="btn py-3 rounded-pill fw-bold text-white" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif; letter-spacing: 1px;">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Add to Cart
                                </button>
                                <button class="btn py-3 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; font-family: 'Montserrat', sans-serif;">
                                    <i class="fas fa-heart me-2"></i>
                                    Add to Wishlist
                                </button>
                            </div>
                            
                            <!-- Product Features -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Key Features:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        <i class="fas fa-check-circle me-2" style="color: #D4DE95;"></i>
                                        Moisture-wicking technology
                                    </li>
                                    <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        <i class="fas fa-check-circle me-2" style="color: #D4DE95;"></i>
                                        Breathable mesh panels
                                    </li>
                                    <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        <i class="fas fa-check-circle me-2" style="color: #D4DE95;"></i>
                                        Four-way stretch fabric
                                    </li>
                                    <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        <i class="fas fa-check-circle me-2" style="color: #D4DE95;"></i>
                                        Anti-odor treatment
                                    </li>
                                    <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        <i class="fas fa-check-circle me-2" style="color: #D4DE95;"></i>
                                        UPF 50+ sun protection
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <ul class="nav nav-tabs border-0" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active border-0 fw-bold" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" 
                                            style="color: #3D4127; background: none; font-family: 'Montserrat', sans-serif;">
                                        Description
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 fw-bold" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" 
                                            style="color: #636B2F; background: none; font-family: 'Montserrat', sans-serif;">
                                        Specifications
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 fw-bold" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" 
                                            style="color: #636B2F; background: none; font-family: 'Montserrat', sans-serif;">
                                        Reviews
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content" id="productTabsContent">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <h4 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Product Description</h4>
                                    <p style="color: #636B2F; font-family: 'Montserrat', sans-serif; line-height: 1.8;">
                                        Our Premium Workout Tank is the perfect choice for fitness enthusiasts who demand both style and performance. Crafted from premium technical fabric, this tank top features advanced moisture-wicking technology that pulls sweat away from your body, keeping you dry and comfortable throughout your workout.
                                    </p>
                                    <p style="color: #636B2F; font-family: 'Montserrat', sans-serif; line-height: 1.8;">
                                        The strategic mesh panels provide enhanced breathability in high-heat areas, while the four-way stretch fabric ensures unrestricted movement during any type of exercise. The ergonomic fit is designed to stay in place during dynamic movements, and the anti-odor treatment helps keep your gear fresh between washes.
                                    </p>
                                </div>
                                
                                <!-- Specifications Tab -->
                                <div class="tab-pane fade" id="specifications" role="tabpanel">
                                    <h4 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Technical Specifications</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Material:</strong> 88% Polyester, 12% Spandex
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Weight:</strong> 150 g/m²
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Fit:</strong> Athletic
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Care:</strong> Machine wash cold
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>UPF Rating:</strong> 50+
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Moisture Wicking:</strong> Yes
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Anti-Odor:</strong> Yes
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                                    <strong>Made in:</strong> Vietnam
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reviews Tab -->
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <h4 class="fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Customer Reviews</h4>
                                    <div class="review-item mb-4 p-3 rounded" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Sarah M.</h6>
                                            <div class="rating">
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                            </div>
                                        </div>
                                        <p class="mb-0" style="color: #636B2F; font-family: 'Montserrat', sans-serif; line-height: 1.6;">
                                            "Absolutely love this tank top! It's incredibly comfortable and keeps me dry during intense workouts. The fit is perfect and it looks great too!"
                                        </p>
                                    </div>
                                    
                                    <div class="review-item mb-4 p-3 rounded" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Mike R.</h6>
                                            <div class="rating">
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="fas fa-star" style="color: #f39c12;"></i>
                                                <i class="far fa-star" style="color: #f39c12;"></i>
                                            </div>
                                        </div>
                                        <p class="mb-0" style="color: #636B2F; font-family: 'Montserrat', sans-serif; line-height: 1.6;">
                                            "Great quality fabric and the moisture-wicking really works. Only giving 4 stars because I wish it came in more colors."
                                        </p>
                                    </div>
                                </div>
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

.thumbnail-img {
    transition: all 0.3s ease;
}

.thumbnail-img:hover {
    transform: scale(1.1);
    border-color: #636B2F !important;
}

.thumbnail-img.active {
    border-color: #636B2F !important;
}

.size-btn {
    transition: all 0.3s ease;
}

.size-btn:hover {
    background: #D4DE95 !important;
    border-color: #D4DE95 !important;
    transform: scale(1.05);
}

.size-btn.active {
    background: #636B2F !important;
    border-color: #636B2F !important;
    color: white !important;
}

.color-option {
    transition: all 0.3s ease;
}

.color-option:hover {
    transform: scale(1.2);
}

.color-option.active {
    border-color: #636B2F !important;
    transform: scale(1.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.nav-link {
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: #3D4127 !important;
}

.nav-link.active {
    color: #3D4127 !important;
    background: none !important;
}

.review-item {
    transition: all 0.3s ease;
}

.review-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
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
.container-fluid > *:nth-child(3) { animation-delay: 0.3s; }

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
    
    .main-image-container img {
        height: 300px !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Thumbnail click functionality
document.querySelectorAll('.thumbnail-img').forEach(thumb => {
    thumb.addEventListener('click', function() {
        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail-img').forEach(t => t.style.borderColor = 'transparent');
        // Add active class to clicked thumbnail
        this.style.borderColor = '#636B2F';
        // Update main image
        document.getElementById('mainImage').src = this.src;
    });
});

// Size button functionality
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active class from all size buttons
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = '#f8f9fa';
            b.style.color = '#636B2F';
            b.style.borderColor = '#D4DE95';
        });
        // Add active class to clicked button
        this.classList.add('active');
        this.style.background = '#636B2F';
        this.style.color = 'white';
        this.style.borderColor = '#636B2F';
    });
});

// Color option functionality
document.querySelectorAll('.color-option').forEach(color => {
    color.addEventListener('click', function() {
        // Remove active class from all color options
        document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
        // Add active class to clicked color
        this.classList.add('active');
    });
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
