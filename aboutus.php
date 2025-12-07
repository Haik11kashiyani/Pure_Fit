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
<div class="container-fluid py-5">
    <!-- Hero Section -->
    <div class="row justify-content-center mb-5 mt-5 pt-5">
        <div class="col-12 col-lg-10 text-center">
            <h1 class="display-3 fw-bold mb-4" style="color: #713600; font-family: 'Montserrat', sans-serif; letter-spacing: 3px;">
                <?php echo htmlspecialchars($hero['title'] ?? 'About Pure Fit'); ?>
            </h1>
            <p class="lead mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-size: 1.25rem; line-height: 1.8;">
                <?php echo htmlspecialchars($hero['content'] ?? 'We\'re passionate about creating high-quality fitness apparel'); ?>
            </p>
        </div>
    </div>

    <!-- Story Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-4 mb-md-0">
                            <h2 class="fw-bold mb-4" style="color: #713600; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                                <?php echo htmlspecialchars($story['title'] ?? 'Our Story'); ?>
                            </h2>
                            <?php 
                            $story_content = $story['content'] ?? 'Our story content';
                            $paragraphs = explode("\n\n", $story_content);
                            foreach ($paragraphs as $index => $paragraph): 
                                $class = ($index == count($paragraphs) - 1) ? 'mb-0' : 'mb-3';
                            ?>
                            <p class="<?php echo $class; ?>" style="color: #713600; font-family: 'Montserrat', sans-serif; line-height: 1.8;">
                                <?php echo nl2br(htmlspecialchars($paragraph)); ?>
                            </p>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-12 col-md-6 text-center">
                            <div class="about-image-container" style="position: relative; overflow: hidden; border-radius: 20px;">
                                <img src="<?php echo htmlspecialchars($story['image_path'] ?? 'assets/img/hero2.png'); ?>" alt="<?php echo htmlspecialchars($story['title'] ?? 'Our Story'); ?>" class="img-fluid rounded" style="transition: transform 0.5s ease;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10">
            <h2 class="text-center fw-bold mb-5" style="color: #713600; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                Our Core Values
            </h2>
            <div class="row g-4">
                <?php while ($value = mysqli_fetch_assoc($values_result)): ?>
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden text-center" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4">
                            <div class="value-icon mb-4" style="background: linear-gradient(135deg, #713600, #C05800); color: #713600; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="<?php echo htmlspecialchars($value['image_path'] ?? 'fas fa-star'); ?> fa-2x"></i>
                            </div>
                            <h4 class="fw-bold mb-3" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                <?php echo htmlspecialchars($value['title']); ?>
                            </h4>
                            <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; line-height: 1.6;">
                                <?php echo htmlspecialchars($value['content']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10">
            <h2 class="text-center fw-bold mb-5" style="color: #713600; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                Meet Our Team
            </h2>
            <div class="row g-4">
                <?php while ($team_member = mysqli_fetch_assoc($team_result)): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden text-center" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4">
                            <div class="team-avatar mb-3" style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #713600, #C05800); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="<?php echo htmlspecialchars($team_member['image_path'] ?? 'fas fa-user'); ?> fa-2x" style="color: #713600;"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                <?php echo htmlspecialchars($team_member['name']); ?>
                            </h5>
                            <p class="mb-1" style="color: #713600; font-family: 'Montserrat', sans-serif; font-size: 0.9rem;">
                                <?php echo htmlspecialchars($team_member['position']); ?>
                            </p>
                            <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-size: 0.8rem; line-height: 1.4;">
                                <?php echo htmlspecialchars($team_member['bio']); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #713600, #C05800);">
                <div class="card-body p-5">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-4 mb-md-0">
                            <div class="stat-item">
                                <h2 class="display-4 fw-bold mb-2" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                    <?php echo number_format($stats['customers']); ?>+
                                </h2>
                                <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-weight: 600;">
                                    Happy Customers
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3 mb-4 mb-md-0">
                            <div class="stat-item">
                                <h2 class="display-4 fw-bold mb-2" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                    <?php echo number_format($stats['products']); ?>+
                                </h2>
                                <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-weight: 600;">
                                    Products
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3 mb-4 mb-md-0">
                            <div class="stat-item">
                                <h2 class="display-4 fw-bold mb-2" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                    2+
                                </h2>
                                <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-weight: 600;">
                                    Countries
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-6 col-md-3">
                            <div class="stat-item">
                                <h2 class="display-4 fw-bold mb-2" style="color: #713600; font-family: 'Montserrat', sans-serif;">
                                    4.9★
                                </h2>
                                <p class="mb-0" style="color: #713600; font-family: 'Montserrat', sans-serif; font-weight: 600;">
                                    Rating
                                </p>
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
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.15) !important;
}

.about-image-container:hover img {
    transform: scale(1.05);
}

.value-icon {
    transition: all 0.3s ease;
}

.value-icon:hover {
    transform: scale(1.1) rotate(5deg);
    background: linear-gradient(135deg, #713600, #713600) !important;
    color: #ffffff !important;
}

.team-avatar {
    transition: all 0.3s ease;
}

.team-avatar:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #713600, #713600) !important;
}

.team-avatar:hover i {
    color: #ffffff !important;
}

.stat-item {
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: scale(1.05);
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
.container-fluid > *:nth-child(4) { animation-delay: 0.4s; }

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
    
    .team-avatar {
        width: 80px !important;
        height: 80px !important;
    }
    
    .value-icon {
        width: 60px !important;
        height: 60px !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
        $contant = ob_get_clean();
        include_once 'master_layout.php';
    ?>
