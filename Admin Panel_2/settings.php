<?php include 'layout.php'; ?>

<div class="container-fluid p-4">
    <h2 class="mb-4 text-center text-md-start">Website Settings</h2>

    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">General Settings</h5>
                </div>
                <div class="card-body">
                    <form id="settingsForm" onsubmit="return validateSettings()">
                        <div class="mb-3">
                            <label for="siteName" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="siteName" placeholder="Enter Site Name">
                        </div>
                        <div class="mb-3">
                            <label for="adminEmail" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="adminEmail" placeholder="Enter Admin Email">
                        </div>
                        <div class="mb-3">
                            <label for="themeSelect" class="form-label">Theme</label>
                            <select class="form-select" id="themeSelect">
                                <option value="">Select Theme</option>
                                <option>Light</option>
                                <option>Dark</option>
                            </select>
                        </div>
                        <button class="btn btn-success w-100">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add New Admin -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Add New Admin</h5>
                </div>
                <div class="card-body">
                    <form id="addAdminForm" onsubmit="return validateAdmin()">
                        <div class="mb-3">
                            <label for="adminName" class="form-label">Admin Name</label>
                            <input type="text" class="form-control" id="adminName" placeholder="Enter Admin Name">
                        </div>
                        <div class="mb-3">
                            <label for="newAdminEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="newAdminEmail" placeholder="Enter Admin Email">
                        </div>
                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="adminPassword" placeholder="Enter Password">
                        </div>
                        <button class="btn btn-primary w-100">Add Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Reports Section -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Download Reports</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Download different reports for analysis:</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="#" class="btn btn-outline-primary flex-grow-1">Users List (CSV)</a>
                <a href="#" class="btn btn-outline-success flex-grow-1">Products List (CSV)</a>
                <a href="#" class="btn btn-outline-warning flex-grow-1">Orders Report (CSV)</a>
            </div>
        </div>
    </div>

    <!-- Other Important Settings -->
    <div class="card shadow-sm mt-4 mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Other Important Settings</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center">
                    Enable Maintenance Mode
                    <div class="d-flex gap-2 mt-2 mt-sm-0">
                        <button class="btn btn-sm btn-warning">Enable</button>
                        <button class="btn btn-sm btn-secondary">Disable</button>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Backup Database
                    <button class="btn btn-sm btn-danger">Backup Now</button>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>