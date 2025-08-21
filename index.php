<?php
        ob_start();
?>
        <div class="hero-section">
            <div class="hero-text d-flex flex-column justify-content-center">
                <h1>Pure Fit Cloths</h1>
                <p>Dress Your Vibe</p>
                <p>Quality never goes out of style.</p>
            </div>
            <div class="hero-imgs d-flex align-items-center justify-content-center">
                <div class="hero-imgs-right">
                    <p>name products</p>
                    <img src="assets/img/hero-img2.png" alt="hero-img2" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section py-4">
        <div class="container-fluid">
            <div class="row justify-content-center g-4">
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4 overflow-hidden">
                        <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
                        <div class="product-info p-2 text-center rounded">
                            <h3>product name</h3>
                            <p>Lorem ipsum dolor sit.</p>
                            <p>20</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4 overflow-hidden">
                        <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
                        <div class="product-info p-2 text-center rounded">
                            <h3>product name</h3>
                            <p>Lorem ipsum dolor sit.</p>
                            <p>20</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                    <div class="productcant rounded mb-4 overflow-hidden">
                        <img src="assets/products/1.png" alt="product-img" class="img-fluid w-100 rounded">
                        <div class="product-info p-2 text-center rounded">
                            <h3>product name</h3>
                            <p>Lorem ipsum dolor sit.</p>
                            <p>20</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Section -->
    <section class="brand-section text-center py-5">
        <div class="container">
            <h2>Our Brands</h2>
            <div class="d-flex justify-content-center flex-wrap gap-4 mt-4">
                <img src="assets/brands/brand1.png" alt="Brand 1" class="img-fluid" style="max-width: 120px;">
                <img src="assets/brands/brand2.png" alt="Brand 2" class="img-fluid" style="max-width: 120px;">
                <img src="assets/brands/brand3.png" alt="Brand 3" class="img-fluid" style="max-width: 120px;">
                <img src="assets/brands/brand4.png" alt="Brand 4" class="img-fluid" style="max-width: 120px;">
            </div>
        </div>
    </section>

    <!-- Review Section -->
    <section class="my-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="review-box rounded p-4 text-center h-100">hii</div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="review-box rounded p-4 text-center h-100">hi</div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="review-box rounded p-4 text-center h-100">h</div>
                </div>
            </div>
        </div>
    </section>

    <?php
        $contant = ob_get_clean();
        include_once 'master_layout.php';
    ?>