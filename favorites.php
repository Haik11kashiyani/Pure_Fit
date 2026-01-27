<?php
session_start();
ob_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>
<div class="min-h-screen bg-white text-black py-32 px-6 md:px-12">
    
    <!-- Header -->
    <div class="flex items-end justify-between border-b border-black pb-8 mb-16">
        <div>
            <h1 class="text-6xl md:text-9xl font-serif italic tracking-tighter">Favorites.</h1>
            <p class="mt-4 text-xs font-bold uppercase tracking-[0.2em] text-gray-500">Your Curated Collection</p>
        </div>
    </div>

    <!-- Products Grid -->
    <?php
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
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-16">
        <?php
        while ($fav = $fav_result->fetch_assoc()) {
            $img = !empty($fav['image_path']) ? $fav['image_path'] : 'assets/products/1.png';
            // Fallback check
            if (!filter_var($img, FILTER_VALIDATE_URL) && !file_exists($img) && !file_exists(__DIR__ . '/' . $img)) {
                $img = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
            }
            $title = htmlspecialchars($fav['product_name']);
            $price = number_format($fav['price'], 2);
        ?>
        <div class="group flex flex-col relative">
            <!-- Image -->
            <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 mb-6">
                <a href="product-details.php?id=<?php echo $fav['product_id']; ?>" class="block w-full h-full">
                    <img src="<?php echo htmlspecialchars($img); ?>" 
                         alt="<?php echo $title; ?>" 
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-transform duration-[1s] group-hover:scale-105">
                </a>
                
                <!-- Quick Actions Overlay -->
                <div class="absolute bottom-0 left-0 w-full flex opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <form method="POST" action="add_to_cart.php" class="w-1/2">
                         <input type="hidden" name="product_id" value="<?php echo $fav['product_id']; ?>">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="redirect" value="favorites.php">
                        <button type="submit" class="w-full py-3 bg-white text-black text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">
                            Add to Bag
                        </button>
                    </form>
                    <form method="POST" action="add_to_favorites.php" class="w-1/2 border-l border-gray-200">
                        <input type="hidden" name="product_id" value="<?php echo $fav['product_id']; ?>">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="redirect" value="favorites.php">
                        <button type="submit" class="w-full py-3 bg-white text-red-500 text-[10px] font-bold uppercase tracking-widest hover:bg-red-500 hover:text-white transition-colors">
                            Remove
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-serif italic mb-1">
                        <a href="product-details.php?id=<?php echo $fav['product_id']; ?>" class="hover:underline decoration-1 underline-offset-4">
                            <?php echo $title; ?>
                        </a>
                    </h3>
                </div>
                <span class="font-mono text-sm">₹<?php echo $price; ?></span>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div class="py-32 flex flex-col items-center justify-center text-center border-t border-black/10">
         <h2 class="text-4xl font-serif italic mb-6">No Favorites Yet.</h2>
         <a href="products.php" class="text-xs font-bold uppercase tracking-widest underline decoration-1 underline-offset-8 hover:text-gray-500 transition-colors">
             Explore The Archive
         </a>
    </div>
    <?php } ?>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>