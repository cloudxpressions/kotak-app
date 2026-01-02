<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Views/partials/header.php';
require_once __DIR__ . '/../../app/Views/partials/sidebar.php';
require_once __DIR__ . '/../../app/Views/partials/navbar.php';

use App\Models\User;

// Fetch users data
$users = User::all();
?>

<script>
    // Update breadcrumb for this page
    document.getElementById('pageBreadcrumb').innerHTML = '<span class="text-muted fw-light">Manage /</span> Users';
</script>

<!-- Content -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Users Management</h5>
        <button class="btn btn-primary create-new-btn" data-entity="users">
            <i class="bx bx-plus me-1"></i> Add New User
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table id="users-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Target</th>
                        <th>Lang</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- / Content -->

<?php require_once __DIR__ . '/../../app/Views/partials/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Convert PHP data to JavaScript - properly serialize model objects
        const usersData = <?php
            $usersArray = array_map(function($user) {
                return $user->jsonSerialize();
            }, $users);
            echo json_encode($usersArray);
        ?>;

        initDataTable('users-table', usersData, [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'mobile' },
            { data: 'exam_target' },
            { data: 'preferred_language' },
            {
                data: 'status',
                render: function(data) {
                    let badgeClass = data == 1 ? 'bg-label-success' : 'bg-label-secondary';
                    let label = data == 1 ? 'Active' : 'Inactive';
                    return `<span class="badge ${badgeClass}">${label}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-icon btn-outline-primary edit-record me-2" data-id="${row.id}">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-outline-danger delete-record" data-id="${row.id}" data-entity="users">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]);
    });
</script>
