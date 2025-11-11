<?php 
include 'layout.php';
include ('../connection.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                addSubcategory($conn);
                break;
            case 'edit':
                updateSubcategory($conn);
                break;
            case 'delete':
                deleteSubcategory($conn);
                break;
        }
    }
}

// Function to add new subcategory
function addSubcategory($conn) {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $subcategory_name = filter_input(INPUT_POST, 'subcategory_name', FILTER_SANITIZE_STRING);
    $status = $_POST['status'] === 'Active' ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO subcategories (category_id, subcategory_name, is_active, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("isi", $category_id, $subcategory_name, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Subcategory added successfully!'); window.location.href='manage_subcategories.php';</script>";
    } else {
        echo "<script>alert('Error adding subcategory: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Function to update subcategory
function updateSubcategory($conn) {
    $subcategory_id = filter_input(INPUT_POST, 'subcategory_id', FILTER_SANITIZE_NUMBER_INT);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $subcategory_name = filter_input(INPUT_POST, 'subcategory_name', FILTER_SANITIZE_STRING);
    
    $status = $_POST['status'] === 'Active' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE subcategories SET category_id = ?, subcategory_name = ?, is_active = ?, updated_at = NOW() WHERE subcategory_id = ?");
    $stmt->bind_param("isii", $category_id, $subcategory_name, $status, $subcategory_id);

    if ($stmt->execute()) {
        echo "<script>alert('Subcategory updated successfully!'); window.location.href='manage_subcategories.php';</script>";
    } else {
        echo "<script>alert('Error updating subcategory: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Function to delete subcategory
function deleteSubcategory($conn) {
    $subcategory_id = filter_input(INPUT_POST, 'subcategory_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $conn->prepare("DELETE FROM subcategories WHERE subcategory_id = ?");
    $stmt->bind_param("i", $subcategory_id);

    if ($stmt->execute()) {
        echo "<script>alert('Subcategory deleted successfully!'); window.location.href='manage_subcategories.php';</script>";
    } else {
        echo "<script>alert('Error deleting subcategory: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<div class="container-fluid">
    <h2 class="mb-4">Manage Subcategories</h2>

    <!-- Add Subcategory Form -->
    <div class="card p-4 mb-4 shadow-sm">
        <h5 class="mb-3">Add New Subcategory</h5>
        <form method="POST" action="" id="subcategoryForm" onsubmit="return validateSubcategory()">
            <input type="hidden" name="action" value="add">
            <div class="row g-3">
                <!-- Category Selection -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Select Category</strong></label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php
                        $query = "SELECT category_id, name FROM categories WHERE is_active = 1";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['category_id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Subcategory Name -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Subcategory Name</strong></label>
                    <input type="text" class="form-control" name="subcategory_name" placeholder="Enter Subcategory Name" required>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-12">
                    <label class="form-label"><strong>Status</strong></label>
                    <select class="form-select" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <button type="submit" class="btn btn-success mt-2 w-100 w-md-auto">
                        <i class="fas fa-plus"></i> Add Subcategory
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Subcategories List -->
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">All Subcategories</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Subcategory Name</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $query = "SELECT s.*, c.name as category_name 
                             FROM subcategories s 
                             JOIN categories c ON s.category_id = c.category_id 
                             ORDER BY c.name, s.subcategory_name";
                    $result = mysqli_query($conn, $query);
                    $count = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $count++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subcategory_name']) . "</td>";
                        echo "<td><span class='badge " . ($row['is_active'] ? 'bg-success' : 'bg-danger') . "'>" . 
                             ($row['is_active'] ? 'Active' : 'Inactive') . "</span></td>";
                        echo "<td class='text-center'>
                                <button class='btn btn-warning btn-sm me-2' onclick='editSubcategory(" . json_encode($row) . ")'>
                                    <i class='fas fa-edit'></i> Edit
                                </button>
                                <button class='btn btn-danger btn-sm' onclick='deleteSubcategory(" . $row['subcategory_id'] . ")'>
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
                <h5 class="modal-title">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editSubcategoryForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="subcategory_id" id="edit_subcategory_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <?php
                                // Fetch categories separately for the edit modal select to avoid reusing the subcategories result
                                $cats_query = "SELECT category_id, name FROM categories WHERE is_active = 1";
                                $cats_result = mysqli_query($conn, $cats_query);
                                while($cat = mysqli_fetch_assoc($cats_result)) {
                                    echo "<option value='" . htmlspecialchars($cat['category_id']) . "'>" . 
                                         htmlspecialchars($cat['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" name="subcategory_name" id="edit_subcategory_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateSubcategory() {
    let category = document.querySelector('select[name="category_id"]').value;
    let subcategory = document.querySelector('input[name="subcategory_name"]').value.trim();
    
    if (category === "") {
        alert("Please select a category.");
        return false;
    }
    if (subcategory === "") {
        alert("Please enter a subcategory name.");
        return false;
    }
    return true;
}

function editSubcategory(data) {
    document.getElementById('edit_subcategory_id').value = data.subcategory_id;
    document.getElementById('edit_category_id').value = data.category_id;
    document.getElementById('edit_subcategory_name').value = data.subcategory_name;
    document.getElementById('edit_status').value = data.is_active ? 'Active' : 'Inactive';
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function deleteSubcategory(subcategory_id) {
    if (confirm('Are you sure you want to delete this subcategory?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete';

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'subcategory_id';
        idInput.value = subcategory_id;

        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include 'footer.php'; ?>