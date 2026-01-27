<?php
ob_start();
include 'connection.php';

// Product Logic (Preserved)
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT p.*, c.name as category_name, s.subcategory_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id 
          WHERE p.product_id = ? AND p.is_active = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) { header('Location: products.php'); exit; }
$product = $result->fetch_assoc();

// Variants Logic
$variantsQuery = "SELECT * FROM product_variants WHERE product_id = ? AND is_active = 1 ORDER BY size";
$variantsStmt = $conn->prepare($variantsQuery);
$variantsStmt->bind_param("i", $productId);
$variantsStmt->execute();
$variantsResult = $variantsStmt->get_result();
$variants = [];
while ($variant = $variantsResult->fetch_assoc()) { $variants[] = $variant; }

// Data Prep
$currentProduct = [
    'id' => $product['product_id'],
    'name' => $product['name'],
    'price' => '₹' . number_format($product['price'], 2),
    'description' => $product['description'] ?? 'No description available.',
    'image' => !empty($product['image_path']) ? $product['image_path'] : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop',
    'stock' => $product['stock_quantity'],
    'category' => $product['category_name'] ?? 'Uncategorized',
    'subcategory' => $product['subcategory_name'] ?? ''
];

// Fallback Image
if (!filter_var($currentProduct['image'], FILTER_VALIDATE_URL) && !file_exists($currentProduct['image']) && !file_exists(__DIR__ . '/' . $currentProduct['image'])) {
    $currentProduct['image'] = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
}

// Calculate Stock
$totalVariantStock = 0;
foreach ($variants as $v) { $totalVariantStock += (int)($v['stock_quantity'] ?? 0); }
if (!empty($variants)) { $currentProduct['stock'] = $totalVariantStock; }

$variantsJson = json_encode($variants);
?>

<div class="bg-paper dark:bg-ink text-ink dark:text-paper min-h-screen pt-24 pb-12 transition-colors duration-500" x-data="{ 
    selectedVariant: <?php echo !empty($variants) ? htmlspecialchars(json_encode($variants[0])) : 'null'; ?>,
    quantity: 1,
    mainImage: '<?php echo addslashes($currentProduct['image']); ?>',
    variants: <?php echo htmlspecialchars($variantsJson); ?>,
    
    get maxStock() {
        if (this.variants.length > 0 && this.selectedVariant) return parseInt(this.selectedVariant.stock_quantity);
        return <?php echo (int)$currentProduct['stock']; ?>;
    },
    
    increment() { if (this.quantity < this.maxStock) this.quantity++; },
    decrement() { if (this.quantity > 1) this.quantity--; },
    
    selectVariant(variant) {
        this.selectedVariant = variant;
        if (this.quantity > this.maxStock) this.quantity = Math.max(1, this.maxStock);
        if (this.maxStock === 0) this.quantity = 0;
        else if (this.quantity === 0) this.quantity = 1;
    }
}">

    <div class="max-w-[1800px] mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 px-6 md:px-12">
        
        <!-- Left: Image Gallery (Scrollable) -->
        <div class="lg:col-span-8 space-y-4">
            <!-- Primary Image -->
            <div class="aspect-[4/5] bg-gray-100 dark:bg-charcoal overflow-hidden relative cursor-zoom-in group">
                <img :src="mainImage" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" alt="Product View 1">
            </div>
            <!-- Secondary Images Loop -->
            <div class="grid grid-cols-2 gap-4">
                <div class="aspect-[3/4] bg-gray-100 dark:bg-charcoal overflow-hidden">
                     <img src="<?php echo htmlspecialchars($currentProduct['image']); ?>" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity" alt="Detail 1">
                </div>
                <!-- Logic for more images would go here -->
            </div>
        </div>

        <!-- Right: Details (Sticky) -->
        <div class="lg:col-span-4 h-fit lg:sticky lg:top-24 pt-8 lg:pt-0">
            
            <!-- Header -->
            <div class="mb-12 border-b border-ink/10 dark:border-white/10 pb-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-5xl md:text-7xl font-serif italic tracking-tighter mix-blend-difference text-ink dark:text-paper z-10"><?php echo htmlspecialchars($currentProduct['name']); ?></h1>
                </div>
                <div class="flex justify-between items-center text-sm font-bold uppercase tracking-widest text-ink/60 dark:text-paper/60">
                    <span><?php echo htmlspecialchars($currentProduct['category']); ?></span>
                    <span><?php echo $currentProduct['price']; ?></span>
                </div>
            </div>

            <!-- Variants Selection -->
            <?php if (!empty($variants)): ?>
            <div class="mb-12">
                <label class="block text-xs font-bold uppercase tracking-widest text-ink/40 dark:text-paper/40 mb-6">Select Size</label>
                <div class="flex flex-wrap gap-3">
                    <template x-for="variant in variants" :key="variant.variant_id">
                        <button @click="selectVariant(variant)" 
                                class="w-14 h-14 border flex items-center justify-center text-xs font-bold transition-all relative group"
                                :class="{
                                    'bg-ink text-paper border-ink dark:bg-paper dark:text-ink dark:border-paper': selectedVariant && selectedVariant.variant_id === variant.variant_id,
                                    'bg-transparent text-ink border-ink/20 hover:bg-ink hover:text-paper dark:text-paper dark:border-paper/20 dark:hover:bg-paper dark:hover:text-ink': (!selectedVariant || selectedVariant.variant_id !== variant.variant_id) && variant.stock_quantity > 0,
                                    'opacity-30 cursor-not-allowed border-ink/10 dark:border-paper/10 text-ink/30 dark:text-paper/30': variant.stock_quantity <= 0
                                }"
                                :disabled="variant.stock_quantity <= 0">
                            <span x-text="variant.size"></span>
                            <!-- Diagonal Strikethrough for Sold Out -->
                            <span x-show="variant.stock_quantity <= 0" class="absolute inset-0 flex items-center justify-center">
                                <div class="w-full h-[1px] bg-ink dark:bg-paper rotate-45 transform scale-x-125"></div>
                            </span>
                        </button>
                    </template>
                </div>
            </div>
            <?php endif; ?>

            <!-- Add to Cart Form -->
            <form method="POST" action="add_to_cart.php" class="space-y-6 mb-12" novalidate>
                <input type="hidden" name="product_id" value="<?php echo $currentProduct['id']; ?>">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="redirect" value="product-details.php?id=<?php echo $currentProduct['id']; ?>">
                <input type="hidden" name="quantity" :value="quantity">
                <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.variant_id : ''">

                <div class="flex gap-4">
                    <!-- Add Button -->
                    <button type="submit" class="flex-1 py-5 bg-ink text-paper dark:bg-paper dark:text-ink text-sm font-bold uppercase tracking-[0.2em] hover:opacity-90 transition-opacity disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed group relative overflow-hidden" :disabled="maxStock <= 0">
                        <span class="relative z-10" x-text="maxStock > 0 ? 'Add to Bag' : 'Sold Out'"></span>
                    </button>
                    
                    <!-- Favorites Button (Restored) -->
                    <a href="add_to_favorites.php?id=<?php echo $currentProduct['id']; ?>" 
                       class="w-16 flex items-center justify-center border border-ink/20 dark:border-paper/20 hover:bg-ink hover:text-paper dark:hover:bg-paper dark:hover:text-ink transition-colors">
                        <i class="far fa-heart text-lg"></i>
                    </a>
                </div>
                
                <div class="text-xs text-center text-ink/40 dark:text-paper/40 uppercase tracking-widest">
                    Free shipping on global orders over $300
                </div>
            </form>

            <!-- Accordions -->
            <div class="border-t border-ink/10 dark:border-white/10" x-data="{ openItem: 1 }">
                <!-- Description -->
                <div class="border-b border-ink/10 dark:border-white/10">
                    <button @click="openItem = openItem === 1 ? null : 1" class="w-full py-6 flex justify-between items-center text-left group">
                        <span class="text-xs font-bold uppercase tracking-widest text-ink/60 dark:text-paper/60 group-hover:text-ink dark:group-hover:text-paper transition-colors">Description & Context</span>
                        <i class="fas fa-plus text-xs transition-transform duration-300 text-ink/60 dark:text-paper/60" :class="openItem === 1 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="openItem === 1" x-collapse>
                        <div class="pb-6 text-sm text-gray-600 dark:text-gray-400 font-serif leading-relaxed max-w-md">
                            <?php echo htmlspecialchars($currentProduct['description']); ?>
                            <br><br>
                            Designed in 2025.
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="border-b border-ink/10 dark:border-white/10">
                    <button @click="openItem = openItem === 2 ? null : 2" class="w-full py-6 flex justify-between items-center text-left group">
                        <span class="text-xs font-bold uppercase tracking-widest text-ink/60 dark:text-paper/60 group-hover:text-ink dark:group-hover:text-paper transition-colors">Shipping & Returns</span>
                        <i class="fas fa-plus text-xs transition-transform duration-300 text-ink/60 dark:text-paper/60" :class="openItem === 2 ? 'rotate-45' : ''"></i>
                    </button>
                    <div x-show="openItem === 2" x-collapse>
                        <div class="pb-6 text-sm text-gray-600 dark:text-gray-400 font-serif leading-relaxed max-w-md">
                            All archival pieces are shipped via express courier. Returns accepted within 14 days of receipt for store credit only.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Sticky Scroll trigger
        ScrollTrigger.create({
            trigger: ".lg\\:sticky",
            start: "top top",
            end: "bottom bottom",
            pin: false
        });
    });
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
