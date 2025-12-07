<?php
session_start();
ob_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<div class="container-fluid py-4 py-md-5" style="padding-top: 80px !important;">
    <!-- Flash messages handled centrally in master_layout.php -->
    
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 px-3">
            <!-- Page Header -->
            <div class="text-center mb-4 mb-md-5">
                <h1 class="display-5 display-md-4 fw-bold mb-3" style="color: #FDFBD4; text-shadow: 0 3px 8px rgba(0,0,0,0.35);">
                    <i class="fas fa-heart text-danger me-2"></i>My Favorites
                </h1>
                <p class="lead mb-0" style="color: #FDFBD4; opacity: 0.9;">
                    Your saved items for future purchases
                </p>
            </div>

            <!-- Products Section -->
            <section class="products-section py-4">
                <div class="container-fluid">
                    <div class="row justify-content-center g-3 g-md-4">
                        <?php
                        // Fetch favorites from database
                        $fav_query = "SELECT f.*, p.*, p.name as product_name, p.price, p.image_path 
                                     FROM favorites f 
                                     INNER JOIN products p ON f.product_id = p.product_id 
                                     WHERE f.user_id = ? AND p.is_active = 1 
                                     ORDER BY f.created_at DESC";
                        $fav_stmt = $conn->prepare($fav_query);
                        $fav_stmt->bind_param("i", $user_id);
                        $fav_stmt->execute();
                        $fav_result = $fav_stmt->get_result();
                        
                        if ($fav_result->num_rows > 0) {
                            while ($fav = $fav_result->fetch_assoc()) {
                                $img = !empty($fav['image_path']) ? $fav['image_path'] : 'assets/products/1.png';
                                $title = htmlspecialchars($fav['product_name']);
                                $desc = htmlspecialchars(mb_strimwidth($fav['description'] ?? '', 0, 100, '...'));
                                $price = '₹' . number_format($fav['price'], 2);
                        ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 d-flex justify-content-center">
                            <a href="product-details.php?id=<?php echo $fav['product_id']; ?>" class="text-decoration-none w-100" style="max-width: 400px;">
                                <div class="productcant rounded">
                                    <div class="position-relative overflow-hidden rounded-top">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $title; ?>" class="img-fluid w-100" style="aspect-ratio: 1/1; object-fit: cover;">
                                        <div class="product-actions position-absolute top-0 end-0 m-2 m-md-3" style="z-index: 10;">
                                            <form method="POST" action="add_to_favorites.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $fav['product_id']; ?>">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="redirect" value="favorites.php">
                                                <button type="submit" class="btn btn-sm rounded-circle me-1 mb-1" style="background: #e74c3c; color: white; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="add_to_cart.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $fav['product_id']; ?>">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="redirect" value="favorites.php">
                                                <button type="submit" class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.95); color: #713600; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="product-info p-3 text-center rounded-bottom bg-white">
                                        <h3 class="mb-2 fs-5 fs-md-4"><?php echo $title; ?></h3>
                                        <p class="mb-2 text-muted small"><?php echo $desc; ?></p>
                                        <p class="mb-0 fw-bold fs-5" style="color: #713600;"><?php echo $price; ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                            }
                        } else {
                        ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-heart-broken fa-4x mb-3" style="color: rgba(253,251,212,0.75);"></i>
                                <h3 class="fw-bold" style="color: #FDFBD4; text-shadow: 0 2px 6px rgba(0,0,0,0.35);">No Favorites Yet</h3>
                                <p style="color: rgba(253,251,212,0.85);">Start adding products to your favorites!</p>
                                <a href="products.php" class="btn btn-lg px-5 py-3 rounded-pill fw-bold mt-3" style="background: linear-gradient(135deg, #FDFBD4, #F5E7A6); border: none; color: #713600;">
                                    Browse Products
                                </a>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
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
