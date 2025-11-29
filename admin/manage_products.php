<?php
include ('../connection.php');

// Detect if products table has subcategory_id column
$has_subcategory = false;
$colRes = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'subcategory_id'");
if ($colRes && mysqli_num_rows($colRes) > 0) {
    $has_subcategory = true;
}

// --- Handle delete product and all its variants
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $product_id = (int)$_POST['product_id'];
    mysqli_query($conn, "DELETE FROM product_variants WHERE product_id = $product_id");
    mysqli_query($conn, "DELETE FROM products WHERE product_id = $product_id");
    header('Location: manage_products.php');
    exit;
}

// --- Handle add/edit product (with variants)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['action'], ['add', 'edit'])) {
    $isEdit = $_POST['action'] === 'edit';
    $product_id = $isEdit ? (int)$_POST['product_id'] : null;
    $name = trim($_POST['productName'] ?? '');
    $price = (float)($_POST['productPrice'] ?? 0);
    $category_id = (int)($_POST['productCategory'] ?? 0);
    $subcategory_id = isset($_POST['productSubCategory']) && $_POST['productSubCategory'] ? (int)$_POST['productSubCategory'] : null;
    $description = trim($_POST['productDescription'] ?? '');
    $is_active = 1;

    // Handle image upload (for add/edit)
    $image_path = null;
    if (!empty($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $tmp = $_FILES['productImage']['tmp_name'];
        $orig = basename($_FILES['productImage']['name']);
        $ext = pathinfo($orig, PATHINFO_EXTENSION);
        $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $uploadDir . $filename;
        if (move_uploaded_file($tmp, $dest)) {
            $image_path = 'assets/products/' . $filename;
        }
    }

    // Basic sanitizing  
    $name_sql = mysqli_real_escape_string($conn, $name);
    $desc_sql = $description ? "'" . mysqli_real_escape_string($conn, $description) . "'" : 'NULL';

    if ($isEdit) {
        $update_fields = [];
        $update_fields[] = "name = '$name_sql'";
        $update_fields[] = "description = $desc_sql";
        $update_fields[] = "price = $price";
        $update_fields[] = "category_id = $category_id";
        if ($has_subcategory) {
            $subcategory_sql = $subcategory_id ? $subcategory_id : 'NULL';
            $update_fields[] = "subcategory_id = $subcategory_sql";
        }
        if ($image_path) {
            $update_fields[] = "image_path = '" . mysqli_real_escape_string($conn, $image_path) . "'";
        }
        $update_fields[] = "updated_at = NOW()";
        $update_sql = "UPDATE products SET " . implode(', ', $update_fields) . " WHERE product_id = $product_id";
        if (!mysqli_query($conn, $update_sql)) {
            $update_error = 'Error updating product: ' . mysqli_error($conn);
        }
    } else {
        $image_sql = $image_path ? "'" . mysqli_real_escape_string($conn, $image_path) . "'" : 'NULL';
        $insert_sql = "INSERT INTO products (category_id, name, description, price, image_path, is_active" . ($has_subcategory ? ',subcategory_id' : '') . ") VALUES ($category_id, '$name_sql', $desc_sql, $price, $image_sql, $is_active" . ($has_subcategory ? ",$subcategory_id" : '') . ")";
        if (!mysqli_query($conn, $insert_sql)) {
            $add_error = 'Error adding product: ' . mysqli_error($conn);
        }
        $product_id = mysqli_insert_id($conn);
    }

    // Handle variants: remove deleted, update existing, add new
    // Remove all existing variants (simpler logic for demo)
    if ($isEdit) {
        mysqli_query($conn, "DELETE FROM product_variants WHERE product_id = $product_id");
    }
    // Insert all variants from POST
    if (!empty($_POST['variant_size'])) {
        foreach ($_POST['variant_size'] as $idx => $size) {
            $size = trim(mysqli_real_escape_string($conn, $size));
            $vstock = (int)($_POST['variant_stock'][$idx] ?? 0);
            $vis_active = isset($_POST['variant_active'][$idx]) ? 1 : 0;
            mysqli_query($conn,
                "INSERT INTO product_variants (product_id, size, stock_quantity, is_active) VALUES ($product_id, '$size', $vstock, $vis_active)"
            );
        }
    }
    header('Location: manage_products.php');
    exit;
}

include 'layout.php';
?>
<div class="container-fluid py-4">
    <h2 class="mb-4 text-center text-primary">Manage Products</h2>

    <!-- Product Form Card -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Product</h5>
        </div>
        <div class="card-body">
            <form id="productForm" method="POST" action="" onsubmit="return validateProduct()" enctype="multipart/form-data" class="row g-3" novalidate>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="">
                <!-- Product Name -->
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter Product Name" required>
                </div>
                <!-- Price -->
                <div class="col-md-6">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice" placeholder="Enter Price in INR" required>
                </div>
                <!-- Description -->
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="productDescription" name="productDescription" placeholder="Enter product description"></textarea>
                </div>
                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="productCategory" name="productCategory" required>
                        <option value="">Select Category</option>
                        <?php
                        $cats_q = "SELECT category_id, name FROM categories WHERE is_active = 1 ORDER BY name";
                        $cats_r = mysqli_query($conn, $cats_q);
                        while ($cat = mysqli_fetch_assoc($cats_r)) {
                            echo "<option value='" . htmlspecialchars($cat['category_id']) . "'>" . htmlspecialchars($cat['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Sub Category -->
                <div class="col-md-6">
                    <label class="form-label">Sub Category</label>
                    <select class="form-select" id="productSubCategory" name="productSubCategory">
                        <option value="">Select Sub Category (optional)</option>
                        <?php
                        $sub_q = "SELECT subcategory_id, subcategory_name FROM subcategories WHERE is_active = 1 ORDER BY subcategory_name";
                        $sub_r = mysqli_query($conn, $sub_q);
                        while ($sc = mysqli_fetch_assoc($sub_r)) {
                            echo "<option value='" . htmlspecialchars($sc['subcategory_id']) . "'>" . htmlspecialchars($sc['subcategory_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Upload Image -->
                <div class="col-12">
                    <label class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="productImage" name="productImage">
                    <div class="mt-2">
                        <img id="productImagePreview" src="" alt="" style="max-height:120px;display:none;border:1px solid #ddd;padding:4px;border-radius:4px;" />
                    </div>
                </div>
                <!-- Product Variants Table -->
                <div class="col-12">
                    <label class="form-label">Product Variants (Size/Stock)</label>
                    <table class="table table-bordered mb-2" id="variantsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Size</th>
                                <th>Stock</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- JS will populate this -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addVariantRow()">Add Variant</button>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" id="productSubmitBtn" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> <span id="productSubmitText">Add Product</span>
                    </button>
                    <button type="button" id="cancelEditBtn" class="btn btn-secondary ms-2" style="display:none;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Product Table -->
    <div class="card shadow-lg">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Product List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock (sum)</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pquery = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC";
                        $pres = mysqli_query($conn, $pquery);
                        while ($p = mysqli_fetch_assoc($pres)) {
                            // sum stock from product_variants
                            $vid = (int)$p['product_id'];
                            $vstock = 0;
                            $vres = mysqli_query($conn, "SELECT SUM(stock_quantity) as total_stock FROM product_variants WHERE product_id = $vid AND is_active = 1");
                            if ($vres) {
                                $vrow = mysqli_fetch_assoc($vres);
                                $vstock = (int)($vrow['total_stock'] ?? 0);
                            }
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($p['product_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($p['name']) . '</td>';
                            echo '<td>₹' . number_format($p['price'], 2) . '</td>';
                            echo '<td>' . htmlspecialchars($vstock) . '</td>';
                            echo '<td>' . htmlspecialchars($p['category_name'] ?? '—') . '</td>';
                            echo '<td>';
                            echo '<button onclick="editProduct(' . $p['product_id'] . ')" class="btn btn-warning btn-sm me-2"><i class="bi bi-pencil"></i> Edit</button>';
                            echo '<button onclick="deleteProduct(' . $p['product_id'] . ')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>

<script>
// --- Variant Table Logic (JS) ---
function addVariantRow(size='', stock='', active=true) {
    var tbody = document.querySelector('#variantsTable tbody');
    var row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" class="form-control" name="variant_size[]" required value="${size}"></td>
        <td><input type="number" class="form-control" name="variant_stock[]" min="0" required value="${stock}"></td>
        <td><input type="checkbox" name="variant_active[${tbody.rows.length}]" value="1" ${active ? 'checked' : ''}></td>
        <td><button type="button" onclick="this.closest('tr').remove();" class="btn btn-danger btn-sm">Remove</button></td>
    `;
    tbody.appendChild(row);
}
// On page load, add 1 empty row
if (!document.querySelector('#productForm input[name="product_id"]').value) { addVariantRow(); }

function clearVariantRows() {
    var tbody = document.querySelector('#variantsTable tbody');
    tbody.innerHTML = '';
}

function fillVariantRows(variants) {
    clearVariantRows();
    if (variants.length === 0) addVariantRow();
    variants.forEach(function(v) {
        addVariantRow(v.size, v.stock_quantity, v.is_active == 1);
    });
}

function validateProduct() {
    var name = document.getElementById('productName').value.trim();
    var price = document.getElementById('productPrice').value.trim();
    if (!name) { try { showNotification('Please enter a product name.', 'danger'); } catch(e){ alert('Please enter a product name.'); } return false; }
    if (!price || isNaN(price) || Number(price) <= 0) { try { showNotification('Please enter a valid price.', 'danger'); } catch(e){ alert('Please enter a valid price.'); } return false; }
    // Variant stock/size validated via required html
    return true;
}

function editProduct(productId) {
    fetch(`get_product.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('productName').value = product.name;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productDescription').value = product.description || '';
            document.getElementById('productCategory').value = product.category_id;
            if (product.subcategory_id) { document.getElementById('productSubCategory').value = product.subcategory_id; }
            document.querySelector('input[name="action"]').value = 'edit';
            document.querySelector('input[name="product_id"]').value = productId;
            document.querySelector('.card-header h5').textContent = 'Edit Product';
            document.getElementById('productSubmitText').textContent = 'Update Product';
            document.getElementById('cancelEditBtn').style.display = 'inline-block';
            var preview = document.getElementById('productImagePreview');
            if (product.image_path) { preview.src = '../' + product.image_path; preview.style.display = 'inline-block'; }
            else { preview.style.display = 'none'; }
            // Fetch variants for this product
            fetch(`get_product_variants.php?product_id=${productId}`)
            .then(response => response.json())
            .then(variants => { fillVariantRows(variants); });
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
            console.log('subcategory_id from product:', product.subcategory_id);

        });
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="product_id" value="${productId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('cancelEditBtn').addEventListener('click', function() {
    document.querySelector('input[name="action"]').value = 'add';
    document.querySelector('input[name="product_id"]').value = '';
    document.querySelector('.card-header h5').textContent = 'Add New Product';
    document.getElementById('productSubmitText').textContent = 'Add Product';
    document.getElementById('productForm').reset();
    document.getElementById('productImagePreview').style.display = 'none';
    this.style.display = 'none';
    clearVariantRows();
    addVariantRow();
});

document.getElementById('productImage').addEventListener('change', function(e) {
    var preview = document.getElementById('productImagePreview');
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            preview.style.display = 'inline-block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
