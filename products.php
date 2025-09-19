<?php
ob_start();
?>
<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10 text-center">
            <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                Our Products
            </h1>
            <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                Discover our complete collection of high-quality fitness apparel
            </p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-2" placeholder="Search products..." 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                <option selected>All Categories</option>
                                <option>MALE</option>
                                <option>FEMALE</option>
                                <option>KIDS</option>
                                
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                <option selected>Brands</option>
                                <option>NIKE</option>
                                <option>ADDIDAS</option>
                                <option>GUCCI</option>
                                <option>H&M</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                    style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; ">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-section py-4">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-12 col-md-6 text-center text-md-center mb-2 mb-md-0">
                    <h1 class="mb-0 fw-bold" style="color: #1a1a1a; letter-spacing: 2px; font-family: 'Montserrat', sans-serif;">Products</h1>
                </div>
                <div class="col-12 col-md-6 text-md-end text-center">
                    <a href="#" class="btn btn-primary px-4 py-2 rounded-pill" style="background: radial-gradient(90px, #798436ff, #616d10ff); border: none; font-weight: 500;">See all products</a>
                </div>
            </div>
            <div class="row justify-content-center g-4">
                <!-- Product 1 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Premium Workout Tank" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Nike Dri-FIT Tank</h3>
                            <p>Lightweight & breathable workout essential</p>
                            <p>₹2,999</p>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Performance Leggings" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Adidas Yoga Leggings</h3>
                            <p>High-waisted comfort for all-day wear</p>
                            <p>₹3,499</p>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Sports Bra" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Puma Sports Bra</h3>
                            <p>Maximum support for high-intensity workouts</p>
                            <p>₹1,899</p>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Running Shorts" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Reebok Running Shorts</h3>
                            <p>Quick-dry fabric with side pockets</p>
                            <p>₹1,599</p>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Performance Hoodie" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Under Armour Hoodie</h3>
                            <p>Fleece-lined warmth for outdoor training</p>
                            <p>₹4,299</p>
                        </div>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Premium Yoga Mat" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Lululemon Yoga Mat</h3>
                            <p>Eco-friendly mat with superior grip</p>
                            <p>₹5,999</p>
                        </div>
                    </div>
                </div>

                <!-- Product 7 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Gym Gloves" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Gym Gloves</h3>
                            <p>Protective grip for weight training</p>
                            <p>₹899</p>
                        </div>
                    </div>
                </div>

                <!-- Product 8 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Water Bottle" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Insulated Water Bottle</h3>
                            <p>Stainless steel keeps drinks cold for hours</p>
                            <p>₹1,299</p>
                        </div>
                    </div>
                </div>

                <!-- Product 9 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                            <img src="assets/products/1.png" alt="Resistance Bands" class="img-fluid w-100 rounded">
                            <div class="product-actions position-absolute top-0 end-0 m-2" style="z-index: 10;">
                                <button class="btn btn-sm rounded-circle me-1 add-to-favorites" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle add-to-cart" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-2 text-center rounded">
                            <h3>Resistance Bands Set</h3>
                            <p>Complete set for home workouts</p>
                            <p>₹1,599</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>

<?php
ob_start();
?>
<div class="container-fluid py-5">
    <!-- Page Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10 text-center">
            <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                Our Products
            </h1>
            <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                Discover our complete collection of high-quality fitness apparel
            </p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-2" placeholder="Search products..." 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                <option selected>All Categories</option>
                                <option>Tops</option>
                                <option>Bottoms</option>
                                <option>Sports Bras</option>
                                <option>Outerwear</option>
                                <option>Accessories</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                <option selected>Sort By</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Newest First</option>
                                <option>Best Rated</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                    style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="row g-4">
                <!-- Product 1 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Premium Workout Tank" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                            <div class="product-badge position-absolute top-0 start-0 m-3">
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
                                High-performance moisture-wicking fabric with breathable mesh panels.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Performance Leggings" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                            <div class="product-badge position-absolute top-0 start-0 m-3">
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
                                Compression-fit leggings with four-way stretch and built-in support.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Sports Bra" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Sports Bra
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                High-support sports bra with adjustable straps and moisture-wicking technology.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Running Shorts" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Running Shorts
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Lightweight running shorts with built-in liner and reflective details.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Performance Hoodie" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Performance Hoodie
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Warm and breathable hoodie perfect for pre and post-workout sessions.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card product-card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="product-image-container position-relative">
                            <img src="assets/products/1.png" alt="Yoga Mat" class="img-fluid w-100" style="height: 280px; object-fit: cover;">
                            <div class="product-overlay position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm rounded-circle me-2" style="background: rgba(255, 255, 255, 0.9); color: #e74c3c; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.9); color: #636B2F; border: none; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold mb-2" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Premium Yoga Mat
                            </h5>
                            <p class="mb-2" style="color: #636B2F; font-size: 0.9rem; line-height: 1.4;">
                                Non-slip yoga mat with perfect thickness for comfort and stability.
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
                                <a href="product-details.php" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                   style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; font-family: 'Montserrat', sans-serif;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="row justify-content-center mt-5">
                <div class="col-12">
                    <nav aria-label="Products pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link rounded-start" href="#" style="border-color: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#" style="background: #636B2F; border-color: #636B2F; color: white;">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" style="border-color: #D4DE95; color: #636B2F;">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" style="border-color: #D4DE95; color: #636B2F;">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link rounded-end" href="#" style="border-color: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.2) !important;
}

.product-image-container {
    overflow: hidden;
}

.product-image-container img {
    transition: transform 0.5s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.1);
}

.product-overlay button {
    transition: all 0.3s ease;
}

.product-overlay button:hover {
    transform: scale(1.1);
}

.product-overlay button:first-child:hover {
    background: #e74c3c !important;
    color: white !important;
}

.product-overlay button:last-child:hover {
    background: #636B2F !important;
    color: white !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-control:focus, .form-select:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

.rating i {
    transition: transform 0.2s ease;
}

.rating:hover i {
    transform: scale(1.2);
}

.pagination .page-link {
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: #D4DE95;
    border-color: #D4DE95;
    color: #636B2F;
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
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>

