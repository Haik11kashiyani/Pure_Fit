
<?php
        ob_start();
        ?>

<div class="container-fluid" style="padding-top: 15vh; min-height: 100vh; ">
    <div class="row justify-content-center" style="min-height: 80vh; padding-top: 4vh;">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); margin: 2rem 0;">
                <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                    <h2 class="mb-0 fw-bold" style="color: #3D4127;  letter-spacing: 1px;">
                        Welcome Back
                    </h2>
                    <p class="mb-0 mt-2" style="color: #636B2F; font-size: 0.9rem;">
                        Sign in to your Pure Fit account
                    </p>
                </div>
                <div class="card-body p-4">
                    <form>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #3D4127; ">
                                Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control border-0 py-3" id="email" placeholder="Enter your email" 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important; transition: all 0.3s ease;">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #3D4127; ">
                                Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control border-0 py-3" id="password" placeholder="Enter your password" 
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important; transition: all 0.3s ease;">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" style="accent-color: #636B2F;">
                                <label class="form-check-label" for="remember" style="color: #636B2F; font-size: 0.9rem;">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none" style="color: #636B2F; font-size: 0.9rem; transition: color 0.3s ease;">
                                Forgot password?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold text-white mb-3" 
                                style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; transition: all 0.3s ease;  letter-spacing: 1px;">
                            Sign In
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0" style="color: #636B2F; font-size: 0.9rem;">
                                Don't have an account? 
                                <a href="register.php" class="text-decoration-none fw-bold" style="color: #3D4127; transition: color 0.3s ease;">
                                    Sign up here
                                </a>
                            </p>
                        </div>
                    </form>
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
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.2) !important;
}

.input-group .form-control:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.02);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-check-input:checked {
    background-color: #636B2F;
    border-color: #636B2F;
}

a:hover {
    color: #BAC095 !important;
}

/* Animation for form elements */
.card-body form > * {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

.card-body form > *:nth-child(1) { animation-delay: 0.1s; }
.card-body form > *:nth-child(2) { animation-delay: 0.2s; }
.card-body form > *:nth-child(3) { animation-delay: 0.3s; }
.card-body form > *:nth-child(4) { animation-delay: 0.4s; }
.card-body form > *:nth-child(5) { animation-delay: 0.5s; }

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
    
    .container-fluid {
        padding: 1rem;
    }
    
    .row {
        min-height: 90vh !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php
        $contant = ob_get_clean();
        include_once 'master_layout.php';
    ?>