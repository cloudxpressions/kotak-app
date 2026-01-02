<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Views/partials/header.php';
require_once __DIR__ . '/../../app/Views/partials/sidebar.php';
require_once __DIR__ . '/../../app/Views/partials/navbar.php';

use App\Models\Test;

// Fetch tests data
$tests = Test::all();
?>

<!-- Content -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Test Management</h5>
        <button class="btn btn-primary create-new-btn" data-entity="tests">
            <i class="bx bx-plus me-1"></i> Add New
        </button>
    </div>
    <div class="card-body">
         <div class="table-responsive text-nowrap">
            <table id="tests-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
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
        const testsData = <?php
            $testsArray = array_map(function($test) {
                return $test->jsonSerialize();
            }, $tests);
            echo json_encode($testsArray);
        ?>;

        initDataTable('tests-table', testsData, [
            { data: 'id' },
            { data: 'name' },
            { data: 'description' },
             {
                data: 'status',
                render: function(data) {
                    return data == 1 ? '<span class="badge bg-label-success">Active</span>' : '<span class="badge bg-label-secondary">Inactive</span>';
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
                            <button class="btn btn-sm btn-icon btn-outline-danger delete-record" data-id="${row.id}" data-entity="tests">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]);
    });
</script>
