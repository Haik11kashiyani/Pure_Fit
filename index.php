<?php
        ob_start();
?>
        <div class="hero-section">
            <div class="hero-text d-flex flex-column justify-content-center">
                <h1>Pure Fit Cloths</h1>
                <p>Dress Your Vibe</p>
                <p>Quality never goes out of style.</p>
            </div>
      <div class="row"  >

          <div class="card" style="width:20dvw; right:0; left:62dvw; top:55dvh; position: relative; height: 38dvh; overflow: hidden; border-radius: 10px;" >
              <img src="assets/img/hero2.png" alt="Card image" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0">
              <div class="card-img-overlay d-flex flex-column justify-content-end text-white">
                  <h4 class="card-title">John Doe</h4>
                  <p class="card-text">Some example text.</p>
              </div>
</div>
    <div class="card" style="width:10dvw; right:0; margin-left:10px; left:62dvw; top:62dvh;  position: relative; height: 25dvh; border-radius: 10px; "  >
            
   <img src="assets/img/hero3.png" alt="Card image" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style=" backdrop-filter: blur(7px);">
  <div class="card-img-overlay d-flex flex-column justify-content-end text-white ">
      <h4 class="card-title">John Doe</h4>
      <p class="card-text">Some example text.</p>
      
    </div>
</div>
            
        </div>
    </section>

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