<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pure Fit | Essentials</title>
    
    <!-- Fonts: Cormorant & Geist/Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300;0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Cormorant', 'serif'],
                    },
                    colors: {
                        ink: '#050505',
                        paper: '#FAFAFA',
                        silver: '#E5E5E5',
                        charcoal: '#1A1A1A',
                    }
                }
            }
        }
    </script>
    
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Inter', sans-serif; 
            antialiased; 
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Typography Tuning */
        h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant', serif; font-weight: 300; }
        
        /* Noise Overlay */
        #noise-canvas {
            position: fixed;
            top: 0; 
            left: 0;
            width: 100%;
            height: 100vh;
            pointer-events: none;
            z-index: 9998; /* Below header, above content */
            opacity: 0.04;
            mix-blend-mode: overlay;
        }

        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Smooth selection */
        ::selection { background: #050505; color: #FAFAFA; }
        .dark ::selection { background: #FAFAFA; color: #050505; }
        
        /* Google Translate Reset - AGGRESSIVE */
        .goog-te-banner-frame.skiptranslate { display: none !important; }
        .goog-te-gadget-icon { display: none !important; }
        #goog-gt-tt { display: none !important; visibility: hidden !important; }
        .goog-tooltip { display: none !important; visibility: hidden !important; }
        .goog-tooltip:hover { display: none !important; visibility: hidden !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
        
        /* Force Body Reset */
        body { top: 0px !important; position: static !important; }
        
        /* Hide the simple gadget if it tries to render */
        .goog-te-gadget-simple { background: transparent !important; border: none !important; padding: 0 !important; font-size: 10px !important; }
        .goog-te-gadget-simple img { display: none !important; }
        
        /* Ensure no margin pushing down */
        html, body { min-height: 100%; margin-top: 0 !important; }
    </style>
</head>

<body class="flex flex-col min-h-screen relative bg-paper text-ink dark:bg-ink dark:text-paper selection:bg-ink selection:text-paper dark:selection:bg-paper dark:selection:text-ink">
    
    <!-- WebGL Noise -->
    <canvas id="noise-canvas"></canvas>

    <!-- TOAST NOTIFICATION -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         @show-toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 4000)"
         class="fixed bottom-6 right-6 z-[100] pointer-events-none">
        
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="bg-ink text-paper dark:bg-paper dark:text-ink px-6 py-4 rounded-sm shadow-2xl flex items-center gap-4 min-w-[300px] border-l-4"
             :class="type === 'error' ? 'border-red-500' : 'border-green-500'">
            
            <div class="flex-1">
                <span class="block text-[10px] font-bold uppercase tracking-widest opacity-50" x-text="type === 'error' ? 'Error' : 'Notification'"></span>
                <p class="text-sm font-medium" x-text="message"></p>
            </div>
            <button @click="show = false" class="text-xs uppercase font-bold tracking-widest hover:opacity-50 pointer-events-auto">Dismiss</button>
        </div>
    </div>

    <!-- Sticky Hide Header -->
    <header 
        x-data="{ 
            visible: true, 
            lastScroll: 0, 
            mobileOpen: false, 
            atTop: true,
            init() {
                window.addEventListener('scroll', () => {
                    const current = window.pageYOffset;
                    this.atTop = current < 10;
                    this.visible = current < this.lastScroll || current < 50; 
                    this.lastScroll = current;
                });
            }
        }"
        :class="{ '-translate-y-full': !visible, 'bg-paper/80 dark:bg-ink/80 backdrop-blur-md shadow-sm border-b border-ink/5 dark:border-white/10': !atTop, 'bg-transparent': atTop }"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]"
    >
        <div class="max-w-[1800px] mx-auto px-6 h-20 flex justify-between items-center">
            
            <!-- Logo -->
             <a href="index.php" class="text-2xl font-serif italic tracking-tight relative z-50 flex items-center gap-2 group">
                <span class="text-ink dark:text-paper group-hover:scale-95 transition-transform">Pure</span>
                <span class="font-sans font-bold not-italic tracking-tighter text-xs opacity-40 text-ink dark:text-paper translate-y-1">FIT</span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-[0.15em] text-ink/70 dark:text-paper/70">
                <a href="products.php" class="hover:text-ink dark:hover:text-paper transition-colors relative group">
                    Shop
                    <span class="absolute -bottom-1 left-0 w-0 h-[1px] bg-ink dark:bg-paper transition-all group-hover:w-full"></span>
                </a>
                <a href="products.php?category=new" class="hover:text-ink dark:hover:text-paper transition-colors relative group">
                    New In
                    <span class="absolute -bottom-1 left-0 w-0 h-[1px] bg-ink dark:bg-paper transition-all group-hover:w-full"></span>
                </a>
                <a href="aboutus.php" class="hover:text-ink dark:hover:text-paper transition-colors relative group">
                    Editorial
                    <span class="absolute -bottom-1 left-0 w-0 h-[1px] bg-ink dark:bg-paper transition-all group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center space-x-6 text-ink dark:text-paper relative z-50">
                
                <!-- Dark Mode Toggle -->
                <button @click="toggleTheme()" class="hover:opacity-60 transition-opacity focus:outline-none">
                    <i class="fas fa-moon" x-show="!darkMode"></i>
                    <i class="fas fa-sun" x-show="darkMode" style="display: none;"></i>
                </button>

                <!-- Language Selector (Custom Global) -->
                <div class="relative group cursor-pointer hidden md:block" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 hover:opacity-60 transition-opacity">
                        <i class="fas fa-globe text-sm"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest" x-text="getCookie('googtrans') ? getCookie('googtrans').split('/')[2].toUpperCase() : 'EN'">EN</span>
                    </button>
                    
                    <div x-show="open" x-cloak class="absolute right-0 top-full mt-4 w-32 bg-paper dark:bg-ink border border-ink/10 dark:border-white/10 shadow-xl rounded-sm py-2 z-[60]">
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('en');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">English</a>
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('hi');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">Hindi</a>
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('gu');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">Gujarati</a>
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('es');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">Spanish</a>
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('fr');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">French</a>
                         <a href="javascript:void(0)" onclick="triggerGoogleTranslate('ja');" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest hover:bg-ink/5 dark:hover:bg-white/5 transition-colors">Japanese</a>
                    </div>
                </div>

                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="hover:opacity-60 transition-opacity"><i class="far fa-user text-sm"></i></a>
                <?php else: ?>
                    <a href="login.php" class="text-[10px] font-bold uppercase tracking-widest border border-ink/20 dark:border-paper/20 px-3 py-1 rounded-full hover:bg-ink hover:text-paper dark:hover:bg-paper dark:hover:text-ink transition-colors">Login</a>
                <?php endif; ?>
                
                <a href="cart.php" class="hover:opacity-60 transition-opacity relative">
                    <span class="text-[10px] uppercase font-bold tracking-widest mr-1 hidden md:inline">Bag</span>
                    <i class="fas fa-shopping-bag md:hidden"></i>
                    <span class="text-xs"><?php echo (isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'); ?></span>
                </a>

                 <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden focus:outline-none ml-2">
                    <div class="w-6 h-[1px] bg-ink dark:bg-paper mb-1.5 transition-all" :class="{'rotate-45 translate-y-2': mobileOpen}"></div>
                    <div class="w-6 h-[1px] bg-ink dark:bg-paper transition-all" :class="{'-rotate-45 -translate-y-0.5': mobileOpen}"></div>
                </button>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-40 bg-paper dark:bg-ink flex flex-col items-center justify-center space-y-8"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full">
            
            <a href="index.php" class="text-4xl font-serif italic text-ink dark:text-paper" @click="mobileOpen = false">Home</a>
            <a href="products.php" class="text-4xl font-serif italic text-ink dark:text-paper" @click="mobileOpen = false">Shop</a>
            <a href="aboutus.php" class="text-4xl font-serif italic text-ink dark:text-paper" @click="mobileOpen = false">Editorial</a>
            <a href="cart.php" class="text-4xl font-serif italic text-ink dark:text-paper" @click="mobileOpen = false">Cart</a>
            
            <!-- Mobile Lang & Theme -->
            <div class="flex flex-col gap-6 mt-8 items-center">
                 <button @click="toggleTheme()" class="text-ink dark:text-paper text-sm uppercase tracking-widest font-bold border border-ink/20 dark:border-paper/20 px-6 py-2 rounded-full">
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                 </button>
                 
                 <div class="flex gap-4 text-xs font-bold uppercase tracking-widest text-ink/60 dark:text-paper/60">
                     <span onclick="triggerGoogleTranslate('en')">EN</span>
                     <span onclick="triggerGoogleTranslate('hi')">HI</span>
                     <span onclick="triggerGoogleTranslate('gu')">GU</span>
                     <span onclick="triggerGoogleTranslate('es')">ES</span>
                 </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        <?php
            if(isset($contant)) {
                echo $contant;
            }
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-ink text-paper dark:bg-black dark:text-white/80 py-20 px-6 border-t border-white/10 dark:border-white/5 transition-colors duration-300">
        <div class="max-w-[1800px] mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
            <div>
                <h4 class="text-3xl font-serif italic mb-4 text-white">Pure Fit</h4>
                <p class="text-white/40 text-xs uppercase tracking-widest max-w-xs">
                    Redefining essentials for the modern minimalist.<br>
                    Crafted with precision. Worn with confidence.
                </p>
            </div>
            
            <div class="flex gap-12 text-xs uppercase tracking-widest text-white/60">
                <div class="flex flex-col gap-4">
                    <span class="text-white font-bold">Shop</span>
                    <a href="products.php" class="hover:text-white transition-colors">All Products</a>
                    <a href="products.php?category=new" class="hover:text-white transition-colors">New Arrivals</a>
                </div>
                <div class="flex flex-col gap-4">
                    <span class="text-white font-bold">Support</span>
                    <a href="contact.php" class="hover:text-white transition-colors">Contact</a>
                    <a href="aboutus.php" class="hover:text-white transition-colors">About</a>
                </div>
            </div>
        </div>
        <div class="max-w-[1800px] mx-auto mt-20 pt-8 border-t border-white/10 flex justify-between items-end text-[10px] text-white/30 uppercase tracking-widest">
            <div>&copy; 2025 Pure Fit Inc.</div>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white">Instagram</a>
                <a href="#" class="hover:text-white">Twitter</a>
            </div>
        </div>
    </footer>

    <!-- Global Logic -->
    <div id="google_translate_element" style="display:none"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en', 
                includedLanguages: 'en,hi,gu,es,fr,de,ar,zh-CN,ja,ko', 
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
        
        // Custom Translation Logic
        function triggerGoogleTranslate(lang) {
            // Set cookie manually if widget is stubborn
            document.cookie = "googtrans=/en/" + lang + "; path=/";
            document.cookie = "googtrans=/en/" + lang + "; path=/; domain=" + window.location.hostname;
            window.location.reload();
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        // NOISE GENERATOR
        const canvas = document.getElementById('noise-canvas');
        const ctx = canvas.getContext('2d');

        const resize = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            paintNoise();
        };

        const paintNoise = () => {
            const w = canvas.width;
            const h = canvas.height;
            const idata = ctx.createImageData(w, h);
            const buffer32 = new Uint32Array(idata.data.buffer);
            const len = buffer32.length;

            for (let i = 0; i < len; i++) {
                if (Math.random() < 0.5) {
                    buffer32[i] = 0xff000000; 
                }
            }
            ctx.putImageData(idata, 0, 0);
        };
        
        resize();
        window.addEventListener('resize', resize);
        
        // PHP Flash Messages to Toast
        document.addEventListener('DOMContentLoaded', () => {
             // GSAP Entrance
             gsap.from('main', { opacity: 0, y: 10, duration: 0.8, ease: 'power2.out' });

            <?php
            // Convert PHP sessions to JS Events
            if (!empty($_SESSION['success_message'])) {
                $msg = addslashes($_SESSION['success_message']);
                echo "window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: '$msg', type: 'success' } }));";
                unset($_SESSION['success_message']);
            }
            if (!empty($_SESSION['error_message'])) {
                $msg = addslashes($_SESSION['error_message']);
                echo "window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: '$msg', type: 'error' } }));";
                unset($_SESSION['error_message']);
            }
            ?>
        });
    </script>
</body>
</html>
