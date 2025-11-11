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
            $success_msg = 'Thank you for contacting us! We will get back to you soon.';
            $_POST = array();
        } else {
            $error_msg = 'Sorry, there was an error sending your message. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5 mt-5 pt-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                    Get In Touch
                </h1>
                <p class="lead" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                    We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>

            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <h3 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Send us a Message
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($success_msg): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i><?php echo $success_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>
                            <?php if ($error_msg): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>
                            <div class="alert-container"></div>
                            <form method="POST" action="" id="contactForm" novalidate>
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control border-0 py-3" id="name" name="name" placeholder="Your full name" 
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                           style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control border-0 py-3" id="email" name="email" placeholder="your.email@example.com" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                           style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="subject" class="form-label fw-semibold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                        Subject
                                    </label>
                                    <input type="text" class="form-control border-0 py-3" id="subject" name="subject" placeholder="What's this about?" 
                                           value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                                           style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label fw-semibold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                        Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control border-0 py-3" id="message" name="message" rows="5" placeholder="Tell us more about your inquiry..." 
                                              style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease; resize: none;"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold text-white" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease; font-family: 'Montserrat', sans-serif; letter-spacing: 1px;">
                                    Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-12 col-lg-4">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-100" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <h3 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Contact Info
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon me-3" style="background: #D4DE95; color: #636B2F; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-marker-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Address</h6>
                                    <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">R K University<br>Rajkot</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon me-3" style="background: #D4DE95; color: #636B2F; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-phone fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Phone</h6>
                                    <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">+91 234 567 8901</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="contact-icon me-3" style="background: #D4DE95; color: #636B2F; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Email</h6>
                                    <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">info@purefitcloths.com</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-3" style="background: #D4DE95; color: #636B2F; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">Business Hours</h6>
                                    <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">Mon-Fri: 9AM-6PM<br>Sat: 10AM-4PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <h3 class="mb-0 fw-bold" style="color: #3D4127; font-family: 'Montserrat', sans-serif;">
                                Find Us
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="map-placeholder d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                <div class="text-center">
                                    <i class="fas fa-map fa-3x mb-3" style="color: #D4DE95;"></i>
                                    <p class="mb-0" style="color: #636B2F; font-family: 'Montserrat', sans-serif;">
                                        Interactive Map Coming Soon
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(99, 107, 47, 0.15) !important;
}

.form-control:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.contact-icon {
    transition: all 0.3s ease;
}

.contact-icon:hover {
    transform: scale(1.1);
    background: #636B2F !important;
    color: #ffffff !important;
}

/* Animation for page elements */
.container-fluid > * {
    animation: fadeInUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

.container-fluid > *:nth-child(1) { animation-delay: 0.1s; }
.container-fluid > *:nth-child(2) { animation-delay: 0.2s; }
.container-fluid > *:nth-child(3) { animation-delay: 0.3s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card {
        margin: 1rem;
    }
    
    .contact-icon {
        width: 40px !important;
        height: 40px !important;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="js/validation.js"></script>
<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        const isValid = Validation.validateForm('#contactForm', {
            name: { required: true },
            email: { required: true, email: true },
            message: { required: true }
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>


