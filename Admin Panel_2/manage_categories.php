<?php include 'layout.php'; ?>
<div class="container-fluid">
    <h2 class="mb-4">Manage Categories</h2>

    <!-- Add Category Form -->
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Add New Category</h5>
        <form id="categoryForm" onsubmit="return validateCategory()">
            <div class="row g-3">
                <!-- Main Category Name -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Main Category</strong></label>
                    <input type="text" class="form-control" id="categoryName" placeholder="Enter Main Category Name">
                </div>

                <!-- Sub Category Name -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Sub Category</strong></label>
                    <input type="text" class="form-control" id="subCategoryName" placeholder="Enter Sub Category Name">
                </div>

                <!-- Category Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Status</strong></label>
                    <select class="form-select" id="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label"><strong>Description</strong></label>
                    <textarea class="form-control" id="description" rows="2" placeholder="Enter Category Description"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <button class="btn btn-success mt-2 w-100 w-md-auto">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Category List -->
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">All Categories</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Main Category</th>
                        <th>Sub Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Men's Wear</td>
                        <td>T-Shirts</td>
                        <td>Casual and formal t-shirts for men</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Main Category</label>
                            <input type="text" class="form-control" id="editCategoryName">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Sub Category</label>
                            <input type="text" class="form-control" id="editSubCategoryName">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editStatus">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Update Category</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function validateCategory() {
        let category = document.getElementById('categoryName').value.trim();
        if (category === "") {
            alert("Please enter a main category name.");
            return false;
        }
        return true;
    }
</script>