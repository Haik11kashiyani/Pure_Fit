<?php
ob_start();
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5 mt-5 pt-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                    My Favorites
                </h1>
                <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                    Your saved items for future purchases
                </p>
            </div>

            <!-- Products Section -->
            <section class="products-section py-4">
                <div class="container-fluid">
                    <div class="row align-items-center mb-4">
                        <div class="col-12 col-md-6 text-center text-md-center mb-2 mb-md-0">
                            <h1 class="mb-0 fw-bold" style="color: #1a1a1a; letter-spacing: 2px; font-family: 'Montserrat', sans-serif;">My Favorites</h1>
                        </div>
                        <div class="col-12 col-md-6 text-md-end text-center">
                            <a href="#" class="btn btn-primary px-4 py-2 rounded-pill" style="background: radial-gradient(90px, #798436ff, #616d10ff); border: none; font-weight: 500;">See all products</a>
                        </div>
                    </div>
                    <div class="row justify-content-center g-4">
                <!-- Favorite Item 1 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                                    <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
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
                                    <h3>Nike Air Max</h3>
                                    <p>Comfortable running shoes</p>
                                    <p>₹8,999</p>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 2 -->
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4">
                        <div class="position-relative">
                                    <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
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
                                    <h3>Adidas Ultraboost</h3>
                                    <p>Premium running sneakers</p>
                                    <p>₹12,999</p>
                        </div>
                    </div>
                </div>

                <!-- Favorite Item 3 -->
                        <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                            <div class="productcant rounded mb-4">
                                <div class="position-relative">
                                    <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
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
                                    <h3>Puma RS-X</h3>
                                    <p>Retro-inspired lifestyle shoes</p>
                                    <p>₹6,999</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>