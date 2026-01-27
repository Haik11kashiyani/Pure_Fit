<?php
session_start();
ob_start();
include 'connection.php';

// Filter Logic Preserved
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

$categories_query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC";
$categories_result = mysqli_query($conn, $categories_query);
?>

<div class="bg-white min-h-screen pb-24 font-sans text-black" x-data="{ filterMobile: false }">
    
    <!-- Title Area -->
    <div class="pt-24 pb-8 px-6 md:px-12 border-b border-gray-100 flex justify-between items-end">
        <div>
            <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter">Collection</h1>
            <p class="mt-2 text-xs text-gray-500 uppercase tracking-widest font-medium">Essentials &bull; 2025</p>
        </div>
        
        <!-- Desktop Sort -->
        <div class="hidden md:flex items-center space-x-4">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Sort By</span>
            <form id="sortForm" method="GET">
                <!-- Keep other params -->
                <?php foreach($_GET as $key=>$val) { if($key!='sort') echo "<input type='hidden' name='$key' value='$val'>"; } ?>
                <select name="sort" onchange="this.form.submit()" class="text-xs font-bold uppercase tracking-widest border-none bg-transparent outline-none cursor-pointer hover:text-gray-500 transition-colors">
                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price Low-High</option>
                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price High-Low</option>
                </select>
            </form>
        </div>

        <button @click="filterMobile = true" class="md:hidden text-xs font-bold uppercase tracking-widest border border-gray-200 px-4 py-2 rounded-full">
            Filter / Sort
        </button>
    </div>

    <div class="flex flex-col md:flex-row">
        
        <!-- Sidebar (Desktop) -->
        <aside class="hidden md:block w-64 p-12 border-r border-gray-100 min-h-screen sticky top-16 self-start">
             <form method="GET" class="space-y-12">
                 
                 <!-- Search -->
                 <div class="space-y-4">
                     <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Search</label>
                     <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full border-b border-gray-200 py-2 outline-none text-sm focus:border-black transition-colors" placeholder="Type...">
                 </div>

                 <!-- Cats -->
                 <div class="space-y-4">
                     <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Category</label>
                     <ul class="space-y-3 text-sm font-medium">
                         <li><a href="products.php" class="<?php echo $category_id == 0 ? 'text-black font-bold' : 'text-gray-500 hover:text-black'; ?>">All</a></li>
                         <?php 
                            if(mysqli_num_rows($categories_result) > 0) {
                                mysqli_data_seek($categories_result, 0);
                                while ($cat = mysqli_fetch_assoc($categories_result)): 
                        ?>
                         <li><a href="products.php?category=<?php echo $cat['category_id']; ?>" class="<?php echo $category_id == $cat['category_id'] ? 'text-black font-bold' : 'text-gray-500 hover:text-black'; ?> transition-colors"><?php echo $cat['name']; ?></a></li>
                         <?php endwhile; } ?>
                     </ul>
                 </div>

                 <!-- Price -->
                 <div class="space-y-4">
                     <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Price</label>
                     <div class="flex gap-2">
                         <input type="number" name="min_price" value="<?php echo $min_price ?: ''; ?>" placeholder="0" class="w-1/2 p-2 bg-gray-50 rounded text-xs">
                         <input type="number" name="max_price" value="<?php echo $max_price ?: ''; ?>" placeholder="MAX" class="w-1/2 p-2 bg-gray-50 rounded text-xs">
                     </div>
                 </div>

                 <button type="submit" class="w-full py-3 bg-black text-white text-xs font-bold uppercase tracking-widest rounded-full hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200/50">
                     Apply Filter
                 </button>
             </form>
        </aside>

        <!-- Product Grid -->
        <main class="flex-grow p-6 md:p-12">
            
            <?php
            // Pagination Logic
            $products_per_page = 12;
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($current_page - 1) * $products_per_page;
            
            $where_conditions = ["p.is_active = 1"];
            if ($search) $where_conditions[] = "(p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
            if ($category_id > 0) $where_conditions[] = "p.category_id = $category_id";
            if ($min_price > 0) $where_conditions[] = "p.price >= $min_price";
            if ($max_price > 0) $where_conditions[] = "p.price <= $max_price";
            $where_clause = implode(' AND ', $where_conditions);
            
            $order_by = "p.created_at DESC"; // Default
             switch ($sort) {
                case 'price_low': $order_by = "p.price ASC"; break;
                case 'price_high': $order_by = "p.price DESC"; break;
            }
            
            $query = "SELECT p.* FROM products p WHERE $where_clause ORDER BY $order_by LIMIT $products_per_page OFFSET $offset";
            $result = mysqli_query($conn, $query);
            ?>

            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 md:gap-x-8 gap-y-12">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($prod = mysqli_fetch_assoc($result)) {
                        $img = !empty($prod['image_path']) ? $prod['image_path'] : 'assets/products/1.png';
                        if (!filter_var($img, FILTER_VALIDATE_URL) && !file_exists($img) && !file_exists(__DIR__ . '/' . $img)) {
                            $img = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                        }
                ?>
                
                <!-- Minimal Product Card -->
                <div class="group flex flex-col relative">
                    <!-- Image -->
                    <div class="aspect-[3/4] w-full bg-gray-100 rounded-2xl overflow-hidden relative mb-4">
                        <a href="product-details.php?id=<?php echo $prod['product_id']; ?>" class="block w-full h-full">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                                 class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-90">
                        </a>
                        
                        <!-- Badge -->
                        <?php if(rand(0,1)): // Simulating 'New' badge logic ?>
                        <span class="absolute top-3 left-3 bg-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm">New</span>
                        <?php endif; ?>

                        <!-- Quick Add (Visible on Hover Desktop, Always Mobile) -->
                        <div class="absolute bottom-4 left-4 right-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <form method="POST" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="quantity" value="1">
                                <button class="w-full py-3 bg-white/90 backdrop-blur text-black text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-black hover:text-white transition-colors shadow-lg">
                                    Quick Add
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Info -->
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-sm leading-tight pr-4">
                                <a href="product-details.php?id=<?php echo $prod['product_id']; ?>" class="hover:text-gray-600 transition-colors">
                                    <?php echo htmlspecialchars($prod['name']); ?>
                                </a>
                            </h3>
                            <span class="text-xs font-medium text-gray-500">₹<?php echo number_format($prod['price']); ?></span>
                        </div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Essential</p>
                    </div>
                </div>

                <?php 
                    }
                } else {
                    echo "<div class='col-span-full text-center py-20 text-gray-400'>No products found.</div>";
                }
                ?>
            </div>

            <!-- Pagination (Clean) -->
             <?php 
            // Count logic
            $count_q = "SELECT COUNT(*) as total FROM products p WHERE $where_clause";
            $count_r = mysqli_query($conn, $count_q);
            $total_products = mysqli_fetch_assoc($count_r)['total'];
            $total_pages = ceil($total_products / $products_per_page);

            if ($total_pages > 1): 
            ?>
            <div class="mt-20 flex justify-center gap-2">
                 <?php 
                $q = $_GET; unset($q['page']); $qs = http_build_query($q);
                for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&<?php echo $qs; ?>" class="w-10 h-10 flex items-center justify-center rounded-full text-xs font-bold transition-all <?php echo $current_page == $i ? 'bg-black text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        </main>
    </div>

    <!-- Mobile Filters Modal -->
    <div x-show="filterMobile" class="fixed inset-0 z-[60] bg-white p-6 overflow-y-auto" x-cloak x-transition.opacity>
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black uppercase tracking-tighter">Filtering</h2>
            <button @click="filterMobile = false"><i class="fas fa-times text-xl"></i></button>
        </div>
        <!-- (Copy of Desktop Form Logic for Mobile) -->
        <form method="GET" class="space-y-8">
             <div class="space-y-4">
                 <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Sort</label>
                 <select name="sort" class="w-full p-4 bg-gray-50 rounded-xl outline-none font-bold text-sm">
                    <option value="newest">Newest</option>
                    <option value="price_low">Price Low-High</option>
                    <option value="price_high">Price High-Low</option>
                 </select>
             </div>
             <!-- ... Can replicate other fields here ... -->
             <button class="w-full py-4 bg-black text-white font-bold uppercase tracking-widest rounded-full">See Results</button>
        </form>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
