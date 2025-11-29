<?php
session_start();
ob_start();
include 'connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

$categories_query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC";
$categories_result = mysqli_query($conn, $categories_query);
?>
<div class="container-fluid py-4 py-md-5" style="padding-top: 80px !important;">
    <!-- Flash messages handled centrally in master_layout.php -->
    
    <div class="row justify-content-center mb-4 mb-md-5">
        <div class="col-12 col-lg-10 text-center px-3">
            <h1 class="display-5 display-md-4 fw-bold mb-3" style="color: #3D4127;">
                Our Products
            </h1>
            <p class="lead mb-0" style="color: #636B2F;">
                Discover our complete collection of high-quality fitness apparel
            </p>
        </div>
    </div>

    <div class="row justify-content-center mb-4 mb-md-5">
        <div class="col-12 col-lg-10 px-3">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                <div class="card-body p-3 p-md-4">
                    <form method="GET" action="products.php" id="filterForm" novalidate>
                        <div class="row g-2 g-md-3 align-items-end">
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold" style="color: #3D4127;">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0" style="background: #D4DE95; color: #636B2F;">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-0 py-2" placeholder="Search products..." 
                                           value="<?php echo htmlspecialchars($search); ?>"
                                           style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label small fw-bold" style="color: #3D4127;">Category</label>
                                <select name="category" class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                    <option value="0">All Categories</option>
                                    <?php while ($cat = mysqli_fetch_assoc($categories_result)): ?>
                                    <option value="<?php echo $cat['category_id']; ?>" <?php echo $category_id == $cat['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <label class="form-label small fw-bold" style="color: #3D4127;">Min Price</label>
                                <input type="number" name="min_price" class="form-control border-0 py-2" placeholder="Min" 
                                       value="<?php echo $min_price > 0 ? $min_price : ''; ?>"
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;" min="0" step="0.01">
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <label class="form-label small fw-bold" style="color: #3D4127;">Max Price</label>
                                <input type="number" name="max_price" class="form-control border-0 py-2" placeholder="Max" 
                                       value="<?php echo $max_price > 0 ? $max_price : ''; ?>"
                                       style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;" min="0" step="0.01">
                            </div>
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label small fw-bold" style="color: #3D4127;">Sort By</label>
                                <select name="sort" class="form-select border-0 py-2" style="background: #f8f9fa; border-left: 3px solid #D4DE95 !important;">
                                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                                    <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                                    <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                                    <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name: A-Z</option>
                                    <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name: Z-A</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-1">
                                <button type="submit" class="btn w-100 py-2 rounded-pill fw-bold text-white" 
                                        style="background: linear-gradient(135deg, #636B2F, #3D4127); border: none;">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php if ($search || $category_id || $min_price || $max_price): ?>
                    <div class="mt-3">
                        <a href="products.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    $products_per_page = 6;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $current_page = max(1, $current_page);
    $offset = ($current_page - 1) * $products_per_page;
    
    $where_conditions = ["p.is_active = 1"];
    
    if ($search) {
        $where_conditions[] = "(p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
    }
    
    if ($category_id > 0) {
        $where_conditions[] = "p.category_id = $category_id";
    }
    
    if ($min_price > 0) {
        $where_conditions[] = "p.price >= $min_price";
    }
    
    if ($max_price > 0) {
        $where_conditions[] = "p.price <= $max_price";
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    
    $order_by = "p.created_at DESC";
    switch ($sort) {
        case 'price_low':
            $order_by = "p.price ASC";
            break;
        case 'price_high':
            $order_by = "p.price DESC";
            break;
        case 'name_asc':
            $order_by = "p.name ASC";
            break;
        case 'name_desc':
            $order_by = "p.name DESC";
            break;
        default:
            $order_by = "p.created_at DESC";
    }
    
    $count_q = "SELECT COUNT(*) as total FROM products p WHERE $where_clause";
    $count_r = mysqli_query($conn, $count_q);
    $count_row = mysqli_fetch_assoc($count_r);
    $total_products = $count_row['total'];
    $total_pages = ceil($total_products / $products_per_page);
    ?>

    <div class="row justify-content-center mb-3">
        <div class="col-12 col-lg-10 px-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted">
                    <strong><?php echo $total_products; ?></strong> product<?php echo $total_products != 1 ? 's' : ''; ?> found
                    <?php if ($search): ?>
                        for "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 px-3">
            <div class="row g-3 g-md-4">
                <?php
                
                $prod_q = "SELECT p.*, c.name as category_name 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.category_id 
                          WHERE $where_clause 
                          ORDER BY $order_by 
                          LIMIT $products_per_page OFFSET $offset";
                $prod_r = mysqli_query($conn, $prod_q);
                if ($prod_r && mysqli_num_rows($prod_r) > 0) {
                    while ($prod = mysqli_fetch_assoc($prod_r)) {
                        $img = !empty($prod['image_path']) ? $prod['image_path'] : 'assets/products/1.png';
                        $title = htmlspecialchars($prod['name']);
                        $desc = htmlspecialchars(mb_strimwidth($prod['description'] ?? '', 0, 120, '...'));
                        $price = '₹' . number_format($prod['price'], 2);
                ?>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 d-flex justify-content-center">
                            <a href="product-details.php?id=<?php echo $prod['product_id']; ?>" class="text-decoration-none w-100" style="max-width: 400px;">
                                <div class="productcant rounded">
                                    <div class="position-relative overflow-hidden rounded-top">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $title; ?>" class="img-fluid w-100" style="aspect-ratio: 1/1; object-fit: cover;">
                                        <div class="product-actions position-absolute top-0 end-0 m-2 m-md-3" style="z-index: 10;">
                                            <form method="POST" action="add_to_favorites.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="redirect" value="products.php?page=<?php echo $current_page; ?>">
                                                <button type="submit" class="btn btn-sm rounded-circle me-1 mb-1" style="background: rgba(255, 255, 255, 0.95); color: #e74c3c; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="add_to_cart.php" style="display: inline-block;" onclick="event.stopPropagation();" novalidate>
                                                <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                                <input type="hidden" name="action" value="add">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="redirect" value="products.php?page=<?php echo $current_page; ?>">
                                                <button type="submit" class="btn btn-sm rounded-circle" style="background: rgba(255, 255, 255, 0.95); color: #636B2F; border: none; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="product-info p-3 text-center rounded-bottom bg-white">
                                        <h3 class="mb-2 fs-5 fs-md-4"><?php echo $title; ?></h3>
                                        <p class="mb-2 text-muted small"><?php echo $desc; ?></p>
                                        <p class="mb-0 fw-bold fs-5" style="color: #636B2F;"><?php echo $price; ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='col-12 text-center py-5'>";
                    echo "<i class='fas fa-box-open fa-4x mb-3' style='color: #D4DE95;'></i>";
                    echo "<h4 class='mb-2' style='color: #3D4127;'>No Products Found</h4>";
                    echo "<p class='text-muted'>Try adjusting your filters or search terms</p>";
                    if ($search || $category_id || $min_price || $max_price) {
                        echo "<a href='products.php' class='btn btn-primary mt-3' style='background: linear-gradient(135deg, #636B2F, #3D4127); border: none;'>";
                        echo "<i class='fas fa-redo me-2'></i>Clear All Filters";
                        echo "</a>";
                    }
                    echo "</div>";
                }
                ?>
            </div>

            <?php if ($total_pages > 1): 
                $query_params = $_GET;
                unset($query_params['page']);
                $query_string = http_build_query($query_params);
                $query_string = $query_string ? '&' . $query_string : '';
            ?>
            <div class="row justify-content-center mt-4 mt-md-5">
                <div class="col-12">
                    <nav aria-label="Products pagination">
                        <ul class="pagination justify-content-center flex-wrap">
                            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link rounded-start" href="<?php echo ($current_page > 1) ? '?page=' . ($current_page - 1) . $query_string : '#'; ?>" style="border-color: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            
                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);
                            
                            if ($start_page > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1' . $query_string . '" style="border-color: #D4DE95; color: #636B2F;">1</a></li>';
                                if ($start_page > 2) {
                                    echo '<li class="page-item disabled"><span class="page-link" style="border-color: #D4DE95; color: #636B2F;">...</span></li>';
                                }
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++) {
                                $active = ($i == $current_page) ? 'active' : '';
                                $style = ($i == $current_page) ? 'background: #636B2F; border-color: #636B2F; color: white;' : 'border-color: #D4DE95; color: #636B2F;';
                                echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . $query_string . '" style="' . $style . '">' . $i . '</a></li>';
                            }
                            
                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) {
                                    echo '<li class="page-item disabled"><span class="page-link" style="border-color: #D4DE95; color: #636B2F;">...</span></li>';
                                }
                                echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . $query_string . '" style="border-color: #D4DE95; color: #636B2F;">' . $total_pages . '</a></li>';
                            }
                            ?>
                            
                            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link rounded-end" href="<?php echo ($current_page < $total_pages) ? '?page=' . ($current_page + 1) . $query_string : '#'; ?>" style="border-color: #D4DE95; color: #636B2F;">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    
                    <!-- Page Info -->
                    <div class="text-center mt-3">
                        <p class="text-muted small mb-0">
                            Showing <?php echo (($current_page - 1) * $products_per_page) + 1; ?> 
                            to <?php echo min($current_page * $products_per_page, $total_products); ?> 
                            of <?php echo $total_products; ?> products
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(99, 107, 47, 0.2) !important;
}

.product-image-container {
    overflow: hidden;
}

.product-image-container img {
    transition: transform 0.5s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.1);
}

.product-overlay button {
    transition: all 0.3s ease;
}

.product-overlay button:hover {
    transform: scale(1.1);
}

.product-overlay button:first-child:hover {
    background: #e74c3c !important;
    color: white !important;
}

.product-overlay button:last-child:hover {
    background: #636B2F !important;
    color: white !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 107, 47, 0.3);
}

.form-control:focus, .form-select:focus {
    background: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(99, 107, 47, 0.25);
    transform: scale(1.01);
}

.rating i {
    transition: transform 0.2s ease;
}

.rating:hover i {
    transform: scale(1.2);
}

.pagination .page-link {
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: #D4DE95;
    border-color: #D4DE95;
    color: #636B2F;
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
}
</style>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$contant = ob_get_clean();
include_once 'master_layout.php';
?>

