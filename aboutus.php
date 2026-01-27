<?php
ob_start();
include 'connection.php';

$hero_query = "SELECT * FROM about_us WHERE section = 'hero' AND is_active = 1 LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

$story_query = "SELECT * FROM about_us WHERE section = 'story' AND is_active = 1 LIMIT 1";
$story_result = mysqli_query($conn, $story_query);
$story = mysqli_fetch_assoc($story_result);

$values_query = "SELECT * FROM about_us WHERE section LIKE 'values_%' AND is_active = 1 ORDER BY display_order ASC";
$values_result = mysqli_query($conn, $values_query);

$stats_query = "SELECT 
    (SELECT COUNT(*) FROM users WHERE is_active = 1) as customers,
    (SELECT COUNT(*) FROM products WHERE is_active = 1) as products";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$team_query = "SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC";
$team_result = mysqli_query($conn, $team_query);
?>

<div class="bg-dark min-h-screen">
    
    <!-- Hero Section -->
    <section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-dark z-0">
            <!-- Animated Background Mesh (Abstract) -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1000px] h-[1000px] bg-primary/5 blur-[120px] rounded-full animate-pulse-slow"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-7xl md:text-9xl font-display font-black mb-6 text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-800 uppercase tracking-tighter" data-reveal="text">
                <?php echo htmlspecialchars($hero['title'] ?? 'The Legacy'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-gray-400 font-light max-w-3xl mx-auto leading-relaxed" data-reveal="fade">
                <?php echo htmlspecialchars($hero['content'] ?? 'Forging elite fitness apparel for the uncompromising athlete.'); ?>
            </p>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
            <span class="text-[10px] uppercase tracking-widest text-white">Scroll</span>
            <div class="w-[1px] h-12 bg-gradient-to-b from-white to-transparent"></div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="py-32 relative">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="relative group" data-reveal="zoom">
                    <div class="absolute -inset-4 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 aspect-[4/5]">
                        <?php 
                        $img_path = !empty($story['image_path']) ? $story['image_path'] : 'assets/img/hero2.png';
                        // Determine if full path or relative
                        if (strpos($img_path, 'http') === false && !file_exists($img_path) && file_exists('assets/img/hero2.png')) {
                            $img_path = 'assets/img/hero2.png';
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($img_path); ?>" alt="Our Story" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 transform group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 to-transparent"></div>
                    </div>
                </div>
                
                <div class="text-left" data-reveal="fade">
                    <h2 class="text-4xl md:text-6xl font-display font-bold text-light mb-10 uppercase tracking-wide">
                        <?php echo htmlspecialchars($story['title'] ?? 'Genesis'); ?>
                    </h2>
                    
                    <div class="space-y-6 text-gray-400 font-light text-lg leading-relaxed">
                        <?php 
                        $story_content = $story['content'] ?? 'Born from the need for perfection.';
                        $paragraphs = explode("\n\n", $story_content);
                        foreach ($paragraphs as $paragraph): 
                        ?>
                        <p><?php echo nl2br(htmlspecialchars($paragraph)); ?></p>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-12 flex gap-12 border-t border-white/10 pt-12">
                         <div>
                             <h4 class="text-4xl font-display font-bold text-white mb-2"><?php echo number_format($stats['customers']); ?>+</h4>
                             <p class="text-xs uppercase tracking-widest text-primary">Athletes</p>
                         </div>
                         <div>
                             <h4 class="text-4xl font-display font-bold text-white mb-2"><?php echo number_format($stats['products']); ?>+</h4>
                             <p class="text-xs uppercase tracking-widest text-primary">Products</p>
                         </div>
                         <div>
                             <h4 class="text-4xl font-display font-bold text-white mb-2">Global</h4>
                             <p class="text-xs uppercase tracking-widest text-primary">Reach</p>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-32 bg-white/5 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
        
        <div class="container mx-auto px-4">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-6xl font-display font-bold text-light mb-4 uppercase tracking-wide" data-reveal="text">Core Philosophy</h2>
                <p class="text-gray-400 font-mono text-sm uppercase tracking-widest">The principles that drive our innovation</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php 
                if (mysqli_num_rows($values_result) > 0):
                    while ($value = mysqli_fetch_assoc($values_result)): 
                ?>
                <div class="group relative p-10 bg-dark border border-white/5 hover:border-primary/50 transition-colors duration-500 overflow-hidden" data-reveal="fade">
                    <div class="absolute top-0 right-0 p-6 opacity-20 text-6xl group-hover:opacity-10 transition-opacity">
                        <i class="<?php echo htmlspecialchars($value['image_path'] ?? 'fas fa-star'); ?>"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-primary text-2xl mb-8 group-hover:scale-110 transition-transform duration-500 border border-white/10 group-hover:border-primary">
                            <i class="<?php echo htmlspecialchars($value['image_path'] ?? 'fas fa-star'); ?>"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-light mb-4 uppercase tracking-wide group-hover:text-primary transition-colors">
                            <?php echo htmlspecialchars($value['title']); ?>
                        </h3>
                        <p class="text-gray-500 leading-relaxed font-light text-sm">
                            <?php echo htmlspecialchars($value['content']); ?>
                        </p>
                    </div>
                </div>
                <?php 
                    endwhile;
                else: 
                ?>
                <!-- Fallback content if no DB values -->
                <div class="col-span-3 text-center text-gray-500">
                    <p>Philosophy data loading...</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-32 relative">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div>
                    <h2 class="text-4xl md:text-6xl font-display font-bold text-light mb-4 uppercase tracking-wide" data-reveal="text">The Architects</h2>
                    <p class="text-gray-400 font-mono text-sm uppercase tracking-widest">Minds behind the movement</p>
                </div>
                <a href="careers.php" class="magnetic-btn px-8 py-3 rounded-full border border-white/20 text-light hover:bg-white hover:text-dark transition-all text-xs font-bold uppercase tracking-widest hidden md:inline-block">
                    Join the Team
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-16">
                <?php 
                if (mysqli_num_rows($team_result) > 0):
                    while ($member = mysqli_fetch_assoc($team_result)): 
                ?>
                <div class="group cursor-pointer" data-reveal="fade">
                    <div class="relative aspect-[3/4] overflow-hidden bg-white/5 mb-6 border border-white/5">
                        <div class="absolute inset-0 bg-primary/20 mix-blend-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>
                        <?php if (!empty($member['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($member['image_path']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-100 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-white/5 text-gray-600">
                                <i class="fas fa-user-astronaut text-4xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Social Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-20">
                            <div class="flex gap-4">
                                <a href="#" class="w-10 h-10 bg-white text-dark rounded-full flex items-center justify-center hover:bg-primary transition-colors"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="w-10 h-10 bg-white text-dark rounded-full flex items-center justify-center hover:bg-primary transition-colors"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-light uppercase tracking-wide group-hover:text-primary transition-colors">
                        <?php echo htmlspecialchars($member['name']); ?>
                    </h3>
                    <p class="text-xs text-secondary font-mono uppercase tracking-widest mb-3">
                        <?php echo htmlspecialchars($member['position']); ?>
                    </p>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-3 group-hover:line-clamp-none transition-all">
                        <?php echo htmlspecialchars($member['bio']); ?>
                    </p>
                </div>
                <?php 
                    endwhile; 
                endif;
                ?>
            </div>
             
             <div class="mt-16 text-center md:hidden">
                 <a href="careers.php" class="magnetic-btn px-8 py-3 border border-white/20 text-light text-xs font-bold uppercase tracking-widest">
                    Join the Team
                </a>
             </div>
        </div>
    </section>

</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>