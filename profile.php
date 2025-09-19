<?php
ob_start();
?>
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5 mt-5 pt-5">
                <h1 class="display-4 fw-bold mb-3" style="color: #3D4127;  letter-spacing: 2px;">
                    My Profile
                </h1>
                <p class="lead" style="color: #636B2F; ">
                    Manage your account settings and preferences
                </p>
            </div>

            <div class="row g-5">
                <!-- Profile Sidebar -->
                <div class="col-12 col-lg-3">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4 text-center">
                            <div class="profile-avatar mb-4" style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #BAC095, #D4DE95); display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 4px solid #D4DE95;">
                                <i class="fas fa-user fa-3x" style="color: #636B2F;"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #3D4127; ">
                                John Doe
                            </h5>
                            <p class="mb-3" style="color: #636B2F; font-size: 0.9rem;">
                                Premium Member
                            </p>
                            <div class="profile-stats d-flex justify-content-around mb-4">
                                <div class="text-center">
                                    <h6 class="fw-bold mb-1" style="color: #3D4127; ">12</h6>
                                    <small style="color: #636B2F; font-size: 0.8rem;">Orders</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="fw-bold mb-1" style="color: #3D4127; ">8</h6>
                                    <small style="color: #636B2F; font-size: 0.8rem;">Favorites</small>
                                </div>
                         
                            </div>
                            
                            <!-- Profile Navigation -->
                            <div class="profile-nav">
                                <button class="btn w-100 mb-2 profile-nav-btn active" data-target="personal-info" 
                                        style="background: #636B2F; color: white; border: none;  transition: all 0.3s ease;">
                                    <i class="fas fa-user me-2"></i>Personal Info
                                </button>
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="orders" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-shopping-bag me-2"></i>Orders
                                </button>
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="addresses" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-map-marker-alt me-2"></i>Addresses
                                </button>
                              
                                <button class="btn w-100 mb-2 profile-nav-btn" data-target="preferences" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;  transition: all 0.3s ease;">
                                    <i class="fas fa-cog me-2"></i>Preferences
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="col-12 col-lg-9">
                    <!-- Personal Info Section -->
                    <div class="profile-section active" id="personal-info">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Personal Information
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="firstName" class="form-label fw-semibold" style="color: #3D4127; ">
                                                First Name
                                            </label>
                                            <input type="text" class="form-control border-0 py-3" id="firstName" value="John" 
                                                   style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                        </div>
                                        
                                        <div class="col-md-6 mb-4">
                                            <label for="lastName" class="form-label fw-semibold" style="color: #3D4127; ">
                                                Last Name
                                            </label>
                                            <input type="text" class="form-control border-0 py-3" id="lastName" value="Doe" 
                                                   style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Email Address
                                        </label>
                                        <input type="email" class="form-control border-0 py-3" id="email" value="john.doe@example.com" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Phone Number
                                        </label>
                                        <input type="tel" class="form-control border-0 py-3" id="phone" value="+1 (234) 567-8901" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="birthdate" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Date of Birth
                                        </label>
                                        <input type="date" class="form-control border-0 py-3" id="birthdate" value="1990-01-01" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="bio" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Bio
                                        </label>
                                        <textarea class="form-control border-0 py-3" id="bio" rows="3" placeholder="Tell us about yourself..." 
                                                  style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease; resize: none;">Fitness enthusiast and wellness advocate. Love staying active and helping others achieve their fitness goals.</textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none;  letter-spacing: 1px;">
                                        Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="profile-section" id="orders" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Order History
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="order-item mb-4 p-3 rounded" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold" style="color: #3D4127; ">
                                            Order #12345
                                        </h6>
                                        <span class="badge rounded-pill px-3 py-2" style="background: #D4DE95; color: #636B2F;">
                                            Delivered
                                        </span>
                                    </div>
                                    <p class="mb-2" style="color: #636B2F; font-size: 0.9rem;">
                                        Premium Workout Tank, Performance Leggings, Sports Bra
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: #636B2F;">$169.97</span>
                                        <small style="color: #636B2F;">Delivered on Dec 15, 2024</small>
                                    </div>
                                </div>
                                
                                <div class="order-item mb-4 p-3 rounded" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold" style="color: #3D4127; ">
                                            Order #12344
                                        </h6>
                                        <span class="badge rounded-pill px-3 py-2" style="background: #D4DE95; color: #636B2F;">
                                            Delivered
                                        </span>
                                    </div>
                                    <p class="mb-2" style="color: #636B2F; font-size: 0.9rem;">
                                        Running Shorts, Performance Hoodie
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: #636B2F;">$104.98</span>
                                        <small style="color: #636B2F;">Delivered on Dec 10, 2024</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Section -->
                    <div class="profile-section" id="addresses" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Addresses
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="address-item mb-4 p-3 rounded" style="background: #f8f9fa; border-left: 4px solid #D4DE95;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 fw-bold" style="color: #3D4127; ">
                                            Home Address
                                        </h6>
                                        <button class="btn btn-sm" style="background: none; color: #636B2F; border: none;">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                    <p class="mb-0" style="color: #636B2F;  line-height: 1.6;">
                                        123 Main Street<br>
                                        Apt 4B<br>
                                        New York, NY 10001<br>
                                        United States
                                    </p>
                                </div>
                                
                                <button class="btn px-4 py-2 rounded-pill fw-bold" 
                                        style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95; ">
                                    <i class="fas fa-plus me-2"></i>Add New Address
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="profile-section" id="security" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Security Settings
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <form>
                                    <div class="mb-4">
                                        <label for="currentPassword" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Current Password
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="currentPassword" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="newPassword" class="form-label fw-semibold" style="color: #3D4127; ">
                                            New Password
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="newPassword" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="confirmPassword" class="form-label fw-semibold" style="color: #3D4127; ">
                                            Confirm New Password
                                        </label>
                                        <input type="password" class="form-control border-0 py-3" id="confirmPassword" 
                                               style="background: #f8f9fa; border-left: 4px solid #D4DE95 !important; transition: all 0.3s ease;">
                                    </div>
                                    
                                    <button type="submit" class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none;  letter-spacing: 1px;">
                                        Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Section -->
                    <div class="profile-section" id="preferences" style="display: none;">
                        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                            <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                                <h3 class="mb-0 fw-bold" style="color: #3D4127; ">
                                    Preferences
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3" style="color: #3D4127; ">
                                        Email Notifications
                                    </h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="orderUpdates" checked style="accent-color: #636B2F;">
                                        <label class="form-check-label" for="orderUpdates" style="color: #636B2F; ">
                                            Order updates and tracking
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="promotions" checked style="accent-color: #636B2F;">
                                        <label class="form-check-label" for="promotions" style="color: #636B2F; ">
                                            Promotions and offers
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="newsletter" style="accent-color: #636B2F;">
                                        <label class="form-check-label" for="newsletter" style="color: #636B2F; ">
                                            Newsletter and fitness tips
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3" style="color: #3D4127; ">
                                        Language Preference
                                    </h6>
                                    <select class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                        <option selected>English</option>
                                        <option>Spanish</option>
                                        <option>French</option>
                                        <option>German</option>
                                    </select>
                                </div>
                                
                                <button class="btn px-4 py-2 rounded-pill fw-bold text-white" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none;  letter-spacing: 1px;">
                                    Save Preferences
                                </button>
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

.profile-avatar {
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
    border-color: #636B2F !important;
}

.profile-nav-btn {
    transition: all 0.3s ease;
}

.profile-nav-btn:hover {
    transform: translateX(5px);
}

.profile-nav-btn.active {
    background: #636B2F !important;
    color: white !important;
    border-color: #636B2F !important;
}

.form-control:focus, .form-select:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.order-item, .address-item {
    transition: all 0.3s ease;
}

.order-item:hover, .address-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
}

.form-check-input:checked {
    background-color: #636B2F;
    border-color: #636B2F;
}

/* Animation for page elements */
.container-fluid > * {
    animation: fadeInUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

.container-fluid > *:nth-child(1) { animation-delay: 0.1s; }
.container-fluid > *:nth-child(2) { animation-delay: 0.2s; }

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
    
    .profile-avatar {
        width: 100px !important;
        height: 100px !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Profile navigation functionality
document.querySelectorAll('.profile-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = this.getAttribute('data-target');
        
        // Hide all profile sections
        document.querySelectorAll('.profile-section').forEach(section => {
            section.style.display = 'none';
        });
        
        // Show target section
        document.getElementById(target).style.display = 'block';
        
        // Update active button
        document.querySelectorAll('.profile-nav-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = '#f8f9fa';
            b.style.color = '#636B2F';
            b.style.borderColor = '#D4DE95';
        });
        
        this.classList.add('active');
        this.style.background = '#636B2F';
        this.style.color = 'white';
        this.style.borderColor = '#636B2F';
    });
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>
