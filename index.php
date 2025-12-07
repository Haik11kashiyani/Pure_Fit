<?php
        ob_start();
?>
        <div class="hero-section">
            <div class="container-fluid h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12 col-lg-6">
                        <div class="hero-text d-flex flex-column justify-content-center">
                            <h1 class="mb-3">Pure Fit Cloths</h1>
                            <p class="mb-2">Dress Your Vibe</p>
                            <p class="mb-4">Quality never goes out of style.</p>
                            <div class="d-none d-md-block">
                                <a href="products.php" class="btn btn-lg px-5 py-3 rounded-pill fw-bold" style="background: #FDFBD4; color: #713600; border: 2px solid #C05800; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                    Shop Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section py-4 py-md-5">
        <div class="container-fluid px-3 px-md-4">
            <div class="row mb-4 mb-md-5">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                        <h1 class="mb-3 mb-md-0 fw-bold" style="color: #713600;">Our Products</h1>
                        <a href="products.php" class="btn btn-primary px-4 py-2 rounded-pill" style="background: linear-gradient(90deg, #C05800, #E8A850); border: none; font-weight: 600; color: #FDFBD4 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            See All Products <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center g-3 g-md-4">
                <!-- Products from database -->
                <?php
                    include 'connection.php';
                    $prod_q = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3";
                    $prod_r = mysqli_query($conn, $prod_q);
                    if ($prod_r && mysqli_num_rows($prod_r) > 0) {
                        while ($prod = mysqli_fetch_assoc($prod_r)) {
                            $img = !empty($prod['image_path']) ? $prod['image_path'] : 'assets/products/1.png';
                            $pname = htmlspecialchars($prod['name']);
                            $pdesc = htmlspecialchars($prod['description'] ?? '');
                            $pprice = '₹' . number_format($prod['price'], 2);
                ?>
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 d-flex justify-content-center">
                    <a href="product-details.php?id=<?php echo $prod['product_id']; ?>" class="text-decoration-none w-100" style="max-width: 400px;">
                        <div class="productcant rounded">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="<?php echo $img; ?>" alt="<?php echo $pname; ?>" class="img-fluid w-100" style="aspect-ratio: 1/1; object-fit: cover;">
                                <div class="product-actions position-absolute top-0 end-0 m-2 m-md-3" style="z-index: 10;">
                                    <form method="POST" action="add_to_favorites.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                        <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="redirect" value="index.php">
                                        <button type="submit" class="btn btn-sm rounded-circle me-1 mb-1" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="add_to_cart.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                        <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="redirect" value="index.php">
                                        <button type="submit" class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.95); color: #713600; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-info p-3 text-center rounded-bottom bg-white">
                                <h3 class="mb-2 fs-5 fs-md-4"><?php echo $pname; ?></h3>
                                <p class="mb-2 text-muted small"><?php echo mb_strimwidth($pdesc, 0, 80, '...'); ?></p>
                                <p class="mb-0 fw-bold fs-5" style="color: #713600;"><?php echo $pprice; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
                        }
                    }
                 else {
                    // optional: show nothing or a placeholder when no products
                }
                ?>


   
    <?php
        $contant = ob_get_clean();
        include_once 'master_layout.php';
    ?>
