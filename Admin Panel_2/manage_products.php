<?php include 'layout.php'; ?>
<div class="container-fluid py-4">
    <h2 class="mb-4 text-center text-primary">Manage Products</h2>

    <!-- Product Form Card -->
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Product</h5>
        </div>
        <div class="card-body">
            <form id="productForm" onsubmit="return validateProduct()" enctype="multipart/form-data" class="row g-3">
                <!-- Product Name -->
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" placeholder="Enter Product Name">
                </div>

                <!-- Price -->
                <div class="col-md-6">
                    <label class="form-label">Price ($)</label>
                    <input type="number" class="form-control" id="productPrice" placeholder="Enter Price">
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="productCategory">
                        <option value="">Select Category</option>
                        <option>Men's Wear</option>
                        <option>Women's Wear</option>
                        <option>Kids</option>
                    </select>
                </div>

                <!-- Sub Category -->
                <div class="col-md-6">
                    <label class="form-label">Sub Category</label>
                    <select class="form-select" id="productSubCategory">
                        <option value="">Select Sub Category</option>
                        <option>T-Shirt</option>
                        <option>Pants</option>
                        <option>Shorts</option>
                    </select>
                </div>

                <!-- Upload Image -->
                <div class="col-12">
                    <label class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="productImage">
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </button>
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
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Blue T-Shirt</td>
                            <td>$20</td>
                            <td>Men's Wear</td>
                            <td>T-Shirt</td>
                            <td>
                                <button class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <!-- More rows dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>