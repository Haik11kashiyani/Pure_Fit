<?php
ob_start();
include 'connection.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject'] ?? ''));
    $message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));
    
    if (empty($name) || empty($email) || empty($message)) {
        $error_msg = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please enter a valid email address';
    } else {
        $query = "INSERT INTO contact_messages (name, email, subject, message, is_read, created_at) 
                  VALUES (?, ?, ?, ?, 0, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = 'Message sent successfully! We will be in touch shortly.';
            $_POST = array();
        } else {
            $error_msg = 'Transmission failed. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="min-h-screen bg-dark py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ submitting: false }">
        
        <!-- Header -->
        <div class="text-center mb-16 relative">
            <h1 class="text-5xl md:text-7xl font-display font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-600 mb-6 uppercase tracking-tighter">
                Contact
            </h1>
            <p class="text-gray-400 font-mono text-sm max-w-lg mx-auto uppercase tracking-widest">
                Initiate communication. We are listening.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white/5 backdrop-blur-sm border border-white/5 p-10 relative">
                     <div class="absolute top-0 right-0 w-20 h-20 bg-primary/10 rounded-bl-full pointer-events-none"></div>
                    
                    <h3 class="text-2xl font-bold font-display text-light mb-8 uppercase tracking-wide">Send Message</h3>
                    
                    <?php if ($success_msg): ?>
                    <div class="mb-8 p-4 bg-primary/10 border border-primary/20 text-primary flex items-start">
                         <i class="fas fa-check-circle mt-1 mr-3 flex-shrink-0"></i>
                         <div class="text-xs font-bold uppercase tracking-widest"><?php echo $success_msg; ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($error_msg): ?>
                    <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 text-red-500 flex items-start">
                         <i class="fas fa-exclamation-circle mt-1 mr-3 flex-shrink-0"></i>
                         <div class="text-xs font-bold uppercase tracking-widest"><?php echo $error_msg; ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="space-y-8" @submit="submitting = true">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                                <input type="text" id="name" name="name" required
                                       class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors"
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="ENTER NAME">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="ENTER EMAIL">
                            </div>
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Subject</label>
                            <input type="text" id="subject" name="subject"
                                   class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors"
                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" placeholder="TOPIC">
                        </div>
                        
                        <div>
                            <label for="message" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 bg-transparent border border-white/20 text-light focus:border-primary focus:outline-none transition-colors resize-none"
                                      placeholder="YOUR MESSAGE..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="magnetic-btn px-10 py-4 bg-white text-dark font-bold font-display uppercase tracking-widest text-xs hover:bg-primary transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="submitting">
                            <span x-show="!submitting">Send Transmission</span>
                            <span x-show="submitting" x-cloak><i class="fas fa-spinner fa-spin mr-2"></i> Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Info Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Info Cards -->
                <div class="bg-white/5 border border-white/5 p-8 group hover:bg-white/10 transition-colors">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-light uppercase tracking-widest mb-2">Headquarters</h4>
                    <p class="text-gray-400 font-mono text-xs leading-relaxed">
                        123 Innovation Drive<br>
                        Tech District, NY 10001<br>
                        United States
                    </p>
                </div>
                
                <div class="bg-white/5 border border-white/5 p-8 group hover:bg-white/10 transition-colors">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-light uppercase tracking-widest mb-2">Email Us</h4>
                    <p class="text-gray-400 font-mono text-xs leading-relaxed">
                        support@purefit.com<br>
                        partnerships@purefit.com
                    </p>
                </div>
                
                <div class="bg-white/5 border border-white/5 p-8 group hover:bg-white/10 transition-colors">
                    <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-phone-alt text-xl"></i>
                    </div>
                    <h4 class="text-sm font-bold text-light uppercase tracking-widest mb-2">Call Us</h4>
                    <p class="text-gray-400 font-mono text-xs leading-relaxed">
                        +1 (555) 123-4567<br>
                        Mon-Fri, 9am - 6pm EST
                    </p>
                </div>

                <!-- Map Placeholder (Dark Mode Style) -->
                <div class="aspect-video bg-white/5 border border-white/5 relative overflow-hidden group">
                     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.183952996901!2d-73.98565508459419!3d40.74844377932764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sin!4v1629789396788!5m2!1sen!2sin" 
                        class="w-full h-full filter grayscale invert opacity-50 hover:opacity-80 transition-opacity" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
