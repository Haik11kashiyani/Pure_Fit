<?php
ob_start();
include 'connection.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product from database
$query = "SELECT p.*, c.name as category_name, s.subcategory_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          LEFT JOIN subcategories s ON p.subcategory_id = s.subcategory_id 
          WHERE p.product_id = ? AND p.is_active = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Product not found, redirect to products page
    header('Location: products.php');
    exit;
}

$product = $result->fetch_assoc();

// Fetch product variants (sizes)
$variantsQuery = "SELECT * FROM product_variants WHERE product_id = ? AND is_active = 1 ORDER BY size";
$variantsStmt = $conn->prepare($variantsQuery);
$variantsStmt->bind_param("i", $productId);
$variantsStmt->execute();
$variantsResult = $variantsStmt->get_result();
$variants = [];
while ($variant = $variantsResult->fetch_assoc()) {
    $variants[] = $variant;
}

// Prepare product data
$currentProduct = [
    'id' => $product['product_id'],
    'name' => $product['name'],
    'price' => '₹' . number_format($product['price'], 2),
    'description' => $product['description'] ?? 'No description available.',
    'image' => !empty($product['image_path']) ? $product['image_path'] : 'assets/products/1.png',
    'stock' => $product['stock_quantity'],
    'category' => $product['category_name'] ?? 'Uncategorized',
    'subcategory' => $product['subcategory_name'] ?? ''
];

// If the product has variants, prefer checking variant stock quantities
$totalVariantStock = 0;
foreach ($variants as $v) {
    $totalVariantStock += (int)($v['stock_quantity'] ?? 0);
}

// If product has any variants with stock use that as the authoritative stock number
if (!empty($variants)) {
    $currentProduct['stock'] = $totalVariantStock;
}
?>

<div class="container-fluid py-5"><div class="row justify-content-center mb-5">
        <div class="col-12 col-lg-10 text-center">
            <h1 class="display-4 fw-bold mb-3" style="color: #3D4127; font-family: 'Montserrat', sans-serif; letter-spacing: 2px;">
                 Products Details
            </h1>
            
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            <div class="row g-5">
                <!-- Product Images -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-0">
                            <div class="main-image-container position-relative d-flex justify-content-center align-items-center" style="min-height: 500px;">
                                <img src="<?php echo htmlspecialchars($currentProduct['image']); ?>" alt="<?php echo htmlspecialchars($currentProduct['name']); ?>" class="img-fluid" id="mainImage" style="max-height: 500px; object-fit: contain;">
                                <div class="product-badge position-absolute top-0 start-0 m-3">
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-body p-4">
                            <h1 class="fw-bold mb-3" style="color: #3D4127;  letter-spacing: 1px;">
                                <?php echo htmlspecialchars($currentProduct['name']); ?>
                            </h1>
                            
                            <div class="d-flex align-items-center mb-3">
                                <?php if ($currentProduct['stock'] > 0): ?>
                                    <span class="badge rounded-pill px-3 py-2" style="background: #D4DE95; color: #636B2F; font-size: 0.8rem;">
                                        In Stock 
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-3 py-2" style="background: #dc3545; color: white; font-size: 0.8rem;">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="price-section mb-4">
                                <span class="h2 fw-bold" style="color: #636B2F;"><?php echo htmlspecialchars($currentProduct['price']); ?></span>
                                
                            </div>
                            
                            <p class="mb-4" style="color: #636B2F;  line-height: 1.6;">
                                <?php echo htmlspecialchars($currentProduct['description']); ?>
                            </p>
                            
                            <!-- Size Selection -->
                            <?php if (!empty($variants)): ?>
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127;">Select Size:</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <?php foreach ($variants as $index => $variant): ?>
                                        <button class="btn size-btn <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                data-variant-id="<?php echo $variant['variant_id']; ?>"
                                                data-stock="<?php echo $variant['stock_quantity']; ?>"
                                                <?php echo $variant['stock_quantity'] <= 0 ? 'disabled' : ''; ?>
                                                style="background: <?php echo $index === 0 ? '#636B2F' : '#f8f9fa'; ?>; 
                                                       color: <?php echo $index === 0 ? 'white' : '#636B2F'; ?>; 
                                                       border: 2px solid <?php echo $index === 0 ? '#636B2F' : '#D4DE95'; ?>; 
                                                       min-width: 50px; 
                                                       transition: all 0.3s ease;
                                                       <?php echo $variant['stock_quantity'] <= 0 ? 'opacity: 0.5; cursor: not-allowed;' : ''; ?>">
                                            <?php echo htmlspecialchars($variant['size']); ?>
                                            <?php if ($variant['stock_quantity'] <= 0): ?>
                                                <br><small>(Out of Stock)</small>
                                            <?php endif; ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            
                            <!-- Quantity -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #3D4127; ">Quantity:</h6>
                                <div class="d-flex align-items-center">
                                    <button id="qtyMinus" type="button" class="btn qty-btn rounded-circle me-3" style="background: #D4DE95; color: #636B2F; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span id="qtyDisplay" class="fw-bold mx-3" style="color: #3D4127; font-size: 1.2rem; min-width: 30px; text-align: center;">1</span>
                                    <button id="qtyPlus" type="button" class="btn qty-btn rounded-circle ms-3" style="background: #D4DE95; color: #636B2F; border: none; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-3 mb-4">
                                <?php if ($currentProduct['stock'] > 0): ?>
                                <form method="POST" action="add_to_cart.php" novalidate>
                                    <input type="hidden" name="product_id" value="<?php echo $currentProduct['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="quantity" id="cartQuantity" value="1">
                                    <input type="hidden" name="variant_id" id="selectedVariantId" value="<?php echo (!empty($variants) ? htmlspecialchars($variants[0]['variant_id']) : ''); ?>">
                                    <input type="hidden" name="redirect" value="product-details.php?id=<?php echo $currentProduct['id']; ?>">
                                    <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold text-white" 
                                            style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none; letter-spacing: 1px;">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Add to Cart
                                    </button>
                                </form>
                                <?php else: ?>
                                <button type="button" class="btn w-100 py-3 rounded-pill fw-bold text-white" disabled
                                        style="background: #cccccc; border: none; letter-spacing: 1px; cursor: not-allowed;">
                                    <i class="fas fa-ban me-2"></i>Out of Stock
                                </button>
                                <?php endif; ?>
                                <form method="POST" action="add_to_favorites.php" novalidate>
                                    <input type="hidden" name="product_id" value="<?php echo $currentProduct['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="redirect" value="product-details.php?id=<?php echo $currentProduct['id']; ?>">
                                    <button type="submit" class="btn w-100 py-3 rounded-pill fw-bold" 
                                            style="background: #f8f9fa; color: #636B2F; border: 2px solid #D4DE95;">
                                        <i class="fas fa-heart me-2"></i>
                                        Add to Wishlist
                                    </button>
                                </form>
                            </div>
                            
                                                 </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px);">
                        <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #BAC095, #D4DE95);">
                            <ul class="nav nav-tabs border-0" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active border-0 fw-bold" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" 
                                            style="color: #3D4127; background: none; ">
                                        Description
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link border-0 fw-bold" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" 
                                            style="color: #636B2F; background: none; ">
                                        Specifications
                                    </button>
                                </li>
                               
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content" id="productTabsContent">
                                <!-- Description Tab -->
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                    <h4 class="fw-bold mb-3" style="color: #3D4127; ">Product Description</h4>
                                    <p style="color: #636B2F;  line-height: 1.8;">
                                        <?php echo htmlspecialchars($currentProduct['description']); ?>
                                    </p>
                                    <p style="color: #636B2F;  line-height: 1.8;">
                                        This product is designed with the latest technology and materials to provide you with the best possible experience. Whether you're hitting the gym, going for a run, or practicing yoga, this item will help you perform at your best while looking great.
                                    </p>
                                </div>
                                
                                <!-- Specifications Tab -->
                                <div class="tab-pane fade" id="specifications" role="tabpanel">
                                    <h4 class="fw-bold mb-3" style="color: #3D4127; ">Technical Specifications</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Material:</strong> 88% Polyester, 12% Spandex
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Weight:</strong> 150 g/m²
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Fit:</strong> Athletic
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Care:</strong> Machine wash cold
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>UPF Rating:</strong> 50+
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Moisture Wicking:</strong> Yes
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Anti-Odor:</strong> Yes
                                                </li>
                                                <li class="mb-2" style="color: #636B2F; ">
                                                    <strong>Made in:</strong> Vietnam
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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

.thumbnail-img {
    transition: all 0.3s ease;
}

.thumbnail-img:hover {
    transform: scale(1.1);
    border-color: #636B2F !important;
}

.thumbnail-img.active {
    border-color: #636B2F !important;
}

.size-btn {
    transition: all 0.3s ease;
}

.size-btn:hover {
    background: #D4DE95 !important;
    border-color: #D4DE95 !important;
    transform: scale(1.05);
}

.size-btn.active {
    background: #636B2F !important;
    border-color: #636B2F !important;
    color: white !important;
}

.color-option {
    transition: all 0.3s ease;
}

.color-option:hover {
    transform: scale(1.2);
}

.color-option.active {
    border-color: #636B2F !important;
    transform: scale(1.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.nav-link {
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: #3D4127 !important;
}

.nav-link.active {
    color: #3D4127 !important;
    background: none !important;
}

.review-item {
    transition: all 0.3s ease;
}

.review-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);
}

.rating i {
    transition: transform 0.2s ease;
}

.rating:hover i {
    transform: scale(1.2);
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
    
    .main-image-container img {
        height: 300px !important;
    }
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Thumbnail click functionality
document.querySelectorAll('.thumbnail-img').forEach(thumb => {
    thumb.addEventListener('click', function() {
        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail-img').forEach(t => t.style.borderColor = 'transparent');
        // Add active class to clicked thumbnail
        this.style.borderColor = '#636B2F';
        // Update main image
        document.getElementById('mainImage').src = this.src;
    });
});

// Size button functionality
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active class from all size buttons
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = '#f8f9fa';
            b.style.color = '#636B2F';
            b.style.borderColor = '#D4DE95';
        });
        // Add active class to clicked button
        this.classList.add('active');
        this.style.background = '#636B2F';
        this.style.color = 'white';
        this.style.borderColor = '#636B2F';
        // Set selected variant id and update quantity max
        const selectedVariantId = this.getAttribute('data-variant-id');
        const variantStock = parseInt(this.getAttribute('data-stock') || '0', 10);
        document.getElementById('selectedVariantId').value = selectedVariantId;
        // adjust quantity display and hidden value if necessary
        const qtyDisplay = document.getElementById('qtyDisplay');
        const qtyInput = document.getElementById('cartQuantity');
        let current = parseInt(qtyInput.value, 10) || 1;
        if (current > variantStock && variantStock > 0) {
            current = variantStock;
            qtyInput.value = current;
            qtyDisplay.textContent = current;
        }
        // if no stock, set to 0 and disable plus/minus accordingly
        if (variantStock <= 0) {
            qtyInput.value = 0;
            qtyDisplay.textContent = 0;
        }
    });
});

// Initialize selected variant / quantity on page load
document.addEventListener('DOMContentLoaded', function() {
    const active = document.querySelector('.size-btn.active') || document.querySelector('.size-btn');
    if (active) {
        const selectedVariantId = active.getAttribute('data-variant-id');
        const variantStock = parseInt(active.getAttribute('data-stock') || '0', 10);
        document.getElementById('selectedVariantId').value = selectedVariantId || '';
        const qtyInput = document.getElementById('cartQuantity');
        const qtyDisplay = document.getElementById('qtyDisplay');
        if (variantStock <= 0) {
            qtyInput.value = 0;
            qtyDisplay.textContent = 0;
        } else {
            // ensure not greater than stock
            let curr = parseInt(qtyInput.value, 10) || 1;
            if (curr > variantStock) curr = variantStock;
            qtyInput.value = curr;
            qtyDisplay.textContent = curr;
        }
    }
});

// Quantity increment/decrement logic bound to buttons
document.getElementById('qtyMinus').addEventListener('click', function() {
    const qtyInput = document.getElementById('cartQuantity');
    let val = parseInt(qtyInput.value, 10) || 1;
    if (val > 1) {
        val = val - 1;
        qtyInput.value = val;
        document.getElementById('qtyDisplay').textContent = val;
    }
});

document.getElementById('qtyPlus').addEventListener('click', function() {
    const qtyInput = document.getElementById('cartQuantity');
    const selectedBtn = document.querySelector('.size-btn.active');
    const variantStock = selectedBtn ? parseInt(selectedBtn.getAttribute('data-stock') || '0', 10) : <?php echo (int)$currentProduct['stock']; ?>;
    let val = parseInt(qtyInput.value, 10) || 1;
    // Prevent increasing beyond available stock
    if (variantStock > 0 && val < variantStock) {
        val = val + 1;
        qtyInput.value = val;
        document.getElementById('qtyDisplay').textContent = val;
    } else if (variantStock === 0) {
        // no-op, out of stock
    } else if (variantStock <= 0) {
        // if overall product stock used and is 0, do nothing
    } else {
        // if variantStock not set (0) and product stock > val, allow increment up to product stock
        // fallback product stock handled elsewhere
    }
});

// Color option functionality
document.querySelectorAll('.color-option').forEach(color => {
    color.addEventListener('click', function() {
        // Remove active class from all color options
        document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
        // Add active class to clicked color
        this.classList.add('active');
    });
});
</script>

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>

                                                </li>


                                            </ul>

                                        </div>

                                    </div>

                                </div>
                                
                                    </div>

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



.thumbnail-img {

    transition: all 0.3s ease;

}



.thumbnail-img:hover {

    transform: scale(1.1);

    border-color: #636B2F !important;

}



.thumbnail-img.active {

    border-color: #636B2F !important;

}



.size-btn {

    transition: all 0.3s ease;

}



.size-btn:hover {

    background: #D4DE95 !important;

    border-color: #D4DE95 !important;

    transform: scale(1.05);

}



.size-btn.active {

    background: #636B2F !important;

    border-color: #636B2F !important;

    color: white !important;

}



.color-option {

    transition: all 0.3s ease;

}



.color-option:hover {

    transform: scale(1.2);

}



.color-option.active {

    border-color: #636B2F !important;

    transform: scale(1.1);

}



.btn:hover {

    transform: translateY(-2px);

    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);

}



.nav-link {

    transition: all 0.3s ease;

}



.nav-link:hover {

    color: #3D4127 !important;

}



.nav-link.active {

    color: #3D4127 !important;

    background: none !important;

}



.review-item {

    transition: all 0.3s ease;

}



.review-item:hover {

    transform: translateX(5px);

    box-shadow: 0 5px 15px rgba(99, 107, 47, 0.1);

}



.rating i {

    transition: transform 0.2s ease;

}



.rating:hover i {

    transform: scale(1.2);

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

    

    .main-image-container img {

        height: 300px !important;

    }

}

</style>



<!-- Font Awesome for icons -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



<script>

// Thumbnail click functionality

document.querySelectorAll('.thumbnail-img').forEach(thumb => {

    thumb.addEventListener('click', function() {

        // Remove active class from all thumbnails

        document.querySelectorAll('.thumbnail-img').forEach(t => t.style.borderColor = 'transparent');

        // Add active class to clicked thumbnail

        this.style.borderColor = '#636B2F';

        // Update main image

        document.getElementById('mainImage').src = this.src;

    });

});



// Size button functionality

document.querySelectorAll('.size-btn').forEach(btn => {

    btn.addEventListener('click', function() {

        // Remove active class from all size buttons

        document.querySelectorAll('.size-btn').forEach(b => {

            b.classList.remove('active');

            b.style.background = '#f8f9fa';

            b.style.color = '#636B2F';

            b.style.borderColor = '#D4DE95';

        });

        // Add active class to clicked button

        this.classList.add('active');

        this.style.background = '#636B2F';

        this.style.color = 'white';

        this.style.borderColor = '#636B2F';

    });

});



// Color option functionality

document.querySelectorAll('.color-option').forEach(color => {

    color.addEventListener('click', function() {

        // Remove active class from all color options

        document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));

        // Add active class to clicked color

        this.classList.add('active');

    });

});

</script>



<?php

$contant = ob_get_clean();

include_once 'master_layout.php';

?>


