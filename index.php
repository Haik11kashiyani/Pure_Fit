<?php
    ob_start();
?>
    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex flex-col justify-end pb-32 px-6 md:px-12 overflow-hidden bg-paper dark:bg-ink transition-colors duration-500">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-paper dark:to-ink opacity-20 pointer-events-none z-10"></div>
        
        <div class="max-w-[1800px] mx-auto w-full z-10 relative flex justify-between items-center">
            <h1 class="text-[14vw] md:text-[11vw] leading-[0.9] font-serif font-light tracking-tighter mix-blend-difference text-ink dark:text-paper reveal-hero opacity-0 pb-4 pr-12">
                TIMELESS <br>
                <span class="font-sans font-black italic tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-ink to-gray-600 dark:from-paper dark:to-gray-400 pr-12">FORM</span>
            </h1>

            <!-- Decorative Right Element (Circular Badge) -->
            <div class="hidden md:flex items-center justify-center w-32 h-32 rounded-full border border-ink/20 dark:border-white/20 animate-[spin_10s_linear_infinite] reveal-hero opacity-0 mr-12 bg-silver/10 backdrop-blur-sm">
                 <div class="relative w-full h-full flex items-center justify-center">
                    <svg viewBox="0 0 100 100" width="100" height="100" class="w-full h-full fill-current text-ink dark:text-paper">
                        <defs>
                            <path id="circle" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" />
                        </defs>
                        <text font-size="10" font-weight="bold" letter-spacing="2">
                            <textPath xlink:href="#circle" class="uppercase">
                                Pure Fit • Essentials • Est. 2025 •
                            </textPath>
                        </text>
                    </svg>
                    <i class="fas fa-asterisk absolute text-xl text-ink dark:text-paper"></i>
                 </div>
            </div>
        </div>
            
            <div class="mt-12 md:mt-24 flex flex-col md:flex-row justify-between items-start md:items-end gap-12 w-full border-t border-ink/10 dark:border-white/10 pt-8 reveal-hero opacity-0">
                <div class="max-w-md">
                    <p class="text-sm md:text-base text-ink/60 dark:text-paper/60 font-medium leading-relaxed">
                        Elevating the everyday through precision tailoring and minimal aesthetics. 
                        Designed for the modern creative.
                    </p>
                    <p class="text-[10px] uppercase tracking-widest text-ink/40 dark:text-paper/40 mt-4">Est. 2025 — Tokyo / New York</p>
                </div>
                
                <div class="flex gap-4">
                    <a href="products.php" class="px-10 py-4 bg-ink text-paper dark:bg-paper dark:text-ink text-xs font-bold uppercase tracking-widest rounded-sm hover:scale-105 transition-transform duration-300">
                        Shop Collection
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- MARQUEE (Clean) -->
    <div class="border-y border-ink/5 dark:border-white/5 bg-white dark:bg-ink overflow-hidden py-6">
        <div class="marquee-content flex whitespace-nowrap text-[120px] md:text-[180px] leading-none font-serif text-ink/30 dark:text-white/30 select-none">
            <span class="mx-12">NEW SEASON</span>
            <span class="mx-12">NEW SEASON</span>
            <span class="mx-12">NEW SEASON</span>
        </div>
    </div>

    <!-- FEATURED PRODUCT GRID (Masonry) -->
    <section id="featured" class="py-32 px-4 md:px-12 bg-paper dark:bg-ink relative z-20 transition-colors duration-500">
        <div class="max-w-[1800px] mx-auto mb-20 flex justify-between items-end">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-ink/40 dark:text-paper/40 block mb-2">Curated Selection</span>
                <h2 class="text-5xl md:text-6xl font-serif italic text-ink dark:text-paper">Essential Pieces</h2>
            </div>
            <a href="products.php" class="hidden md:inline-block text-xs font-bold uppercase tracking-widest border-b border-ink dark:border-paper pb-1 text-ink dark:text-paper hover:opacity-50 transition-opacity">
                View Full Archive
            </a>
        </div>

        <!-- GRID CONTAINER -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-4 auto-rows-[60vh]">
            <?php
            include 'connection.php';
            $feat_q = "SELECT * FROM products WHERE is_active = 1 ORDER BY RAND() LIMIT 5";
            $feat_r = mysqli_query($conn, $feat_q);
            $idx = 0;
            
            if ($feat_r && mysqli_num_rows($feat_r) > 0) {
                while ($prod = mysqli_fetch_assoc($feat_r)) {
                    $img = !empty($prod['image_path']) ? $prod['image_path'] : 'assets/products/1.png';
                    
                    // Fallback
                    if (!filter_var($img, FILTER_VALIDATE_URL) && !file_exists($img) && !file_exists(__DIR__ . '/' . $img)) {
                         $img = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=2070&auto=format&fit=crop';
                    }
                    
                    // Logic for Bento/Masonry Classes
                    $classes = "relative group overflow-hidden bg-silver dark:bg-charcoal";
                    if ($idx == 0) $classes .= " md:col-span-2 md:row-span-2"; // Hero Product
                    else $classes .= " md:col-span-1";
            ?>
            
            <!-- PRODUCT CARD -->
            <div class="<?php echo $classes; ?>">
                <a href="product-details.php?id=<?php echo $prod['product_id']; ?>" class="block w-full h-full relative z-0">
                    <!-- Image -->
                    <img src="<?php echo htmlspecialchars($img); ?>" 
                         alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                         class="w-full h-full object-cover transition-transform duration-[1.5s] ease-out group-hover:scale-105 filter grayscale group-hover:grayscale-0">
                    
                    <!-- Dark Gradient Overlay (Stronger for visibility) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                </a>

                <!-- Favorites Button (Top Right, Z-Index High) -->
                <a href="add_to_favorites.php?id=<?php echo $prod['product_id']; ?>" 
                   class="absolute top-6 right-6 z-20 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 duration-300 pointer-events-auto">
                    <i class="far fa-heart"></i>
                </a>
                
                <!-- Info (Bottom Aligned, Z-Index High) -->
                <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col justify-end z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                     <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <!-- Price Tag -->
                        <div class="inline-block backdrop-blur-md bg-white/20 border border-white/30 px-4 py-2 rounded-full mb-4">
                            <span class="text-white text-xs font-bold tracking-wider">₹<?php echo number_format($prod['price']); ?></span>
                        </div>

                        <h3 class="text-3xl font-serif text-white mb-2 drop-shadow-md"><?php echo htmlspecialchars($prod['name']); ?></h3>
                        
                        <div class="flex items-center text-white/90 text-[10px] font-bold uppercase tracking-widest gap-2">
                            <span>View Product</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                    $idx++;
                }
            } 
            ?>
            
            <!-- CTA CARD (Fills last slot) -->
            <div class="relative group overflow-hidden bg-ink dark:bg-charcoal text-white flex flex-col justify-center items-center text-center p-12 cursor-pointer h-full border border-white/5" onclick="location.href='products.php'">
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                
                <h3 class="text-5xl font-serif italic mb-6 relative z-10 group-hover:scale-110 transition-transform duration-700">The<br>Archive</h3>
                <p class="text-white/40 text-xs uppercase tracking-widest max-w-[150px] relative z-10">Explore the full FW2025 Collection</p>
                
                <div class="mt-12 w-16 h-16 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-black transition-all relative z-10">
                    <i class="fas fa-arrow-right transform -rotate-45 group-hover:rotate-0 transition-transform duration-500"></i>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        // Hero Animation
        document.addEventListener('DOMContentLoaded', () => {
            gsap.to('.reveal-hero', {
                opacity: 1,
                y: 0,
                duration: 1.5,
                stagger: 0.2,
                ease: 'power3.out',
                delay: 0.2
            });

            // Marquee Animation
            gsap.to(".marquee-content", {
                xPercent: -50,
                repeat: -1,
                duration: 20,
                ease: "linear"
            });
        });
    </script>
    
    <style>
        .reveal-hero { transform: translateY(40px); }
    </style>

<?php
    $contant = ob_get_clean();
    include_once 'master_layout.php';
?>