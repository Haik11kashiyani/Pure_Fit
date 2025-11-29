<?php 
include 'layout.php';
include ('../connection.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addCategory($conn);
                break;
            case 'edit':
                updateCategory($conn);
                break;
            case 'delete':
                deleteCategory($conn);
                break;
        }
    }
}

// Function to add new category
function addCategory($conn) {
    $category_name = filter_input(INPUT_POST, 'categoryName', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $status = $_POST['status'] === 'Active' ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO categories (name, description, is_active, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("ssi", $category_name, $description, $status);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Category added successfully!';
        header('Location: manage_categories.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Error adding category: ' . $stmt->error;
        header('Location: manage_categories.php');
        exit;
    }
    $stmt->close();
}

// Function to update category
function updateCategory($conn) {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $category_name = filter_input(INPUT_POST, 'categoryName', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $status = $_POST['status'] === 'Active' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, is_active = ?, updated_at = NOW() WHERE category_id = ?");
    $stmt->bind_param("ssii", $category_name, $description, $status, $category_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Category updated successfully!';
        header('Location: manage_categories.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Error updating category: ' . $stmt->error;
        header('Location: manage_categories.php');
        exit;
    }
    $stmt->close();
}

// Function to delete category
function deleteCategory($conn) {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    // First check if there are any subcategories
    $stmt = $conn->prepare("SELECT COUNT(*) FROM subcategories WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error_message'] = 'Cannot delete category: It has associated subcategories.';
        header('Location: manage_categories.php');
        exit;
        return;
    }

    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Category deleted successfully!';
        header('Location: manage_categories.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Error deleting category: ' . $stmt->error;
        header('Location: manage_categories.php');
        exit;
    }
    $stmt->close();
}
?>
<div class="container-fluid">
    <h2 class="mb-4">Manage Categories</h2>
    <!-- Add Category Form -->
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Add New Category</h5>
        <form id="categoryForm" method="POST" action="" onsubmit="return validateCategory()" novalidate>
            <input type="hidden" name="action" value="add">
            <div class="row g-3">
                <!-- Category Name -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Category Name</strong></label>
                    <input type="text" class="form-control" name="categoryName" placeholder="Enter Category Name" required>
                </div>

                <!-- Category Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Status</strong></label>
                    <select class="form-select" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label"><strong>Description</strong></label>
                    <textarea class="form-control" name="description" rows="2" placeholder="Enter Category Description"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <button type="submit" class="btn btn-success mt-2 w-100 w-md-auto">
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
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Subcategories</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT c.*, 
                             (SELECT COUNT(*) FROM subcategories s WHERE s.category_id = c.category_id) as subcategory_count 
                             FROM categories c ORDER BY c.name";
                    $result = mysqli_query($conn, $query);
                    $count = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $count++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td><span class='badge " . ($row['is_active'] ? 'bg-success' : 'bg-danger') . "'>" . 
                             ($row['is_active'] ? 'Active' : 'Inactive') . "</span></td>";
                        echo "<td>" . $row['subcategory_count'] . " subcategories</td>";
                        echo "<td class='text-center'>
                                <button class='btn btn-warning btn-sm me-2' onclick='editCategory(" . json_encode($row) . ")'>
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <button class='btn btn-danger btn-sm' onclick='deleteCategory(" . $row['category_id'] . ")'>
                                    <i class='fas fa-trash'></i> Delete
                                </button>
                             </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editCategoryForm" novalidate>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="category_id" id="edit_category_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="categoryName" id="edit_category_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function validateCategory() {
    let category = document.querySelector('input[name="categoryName"]').value.trim();
    if (category === "") {
        try { showNotification('Please enter a category name.', 'danger'); } catch(e){ alert('Please enter a category name.'); }
        return false;
    }
    return true;
}

function editCategory(data) {
    document.getElementById('edit_category_id').value = data.category_id;
    document.getElementById('edit_category_name').value = data.name;
    document.getElementById('edit_description').value = data.description;
    document.getElementById('edit_status').value = data.is_active ? 'Active' : 'Inactive';
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function deleteCategory(category_id) {
    if (confirm('Are you sure you want to delete this category?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete';

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'category_id';
        idInput.value = category_id;

        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>