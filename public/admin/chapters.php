<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Views/partials/header.php';
require_once __DIR__ . '/../../app/Views/partials/sidebar.php';
require_once __DIR__ . '/../../app/Views/partials/navbar.php';

use App\Models\Chapter;

// Fetch chapters data
$chapters = Chapter::all();
?>

<script>
    // Update breadcrumb for this page
    document.getElementById('pageBreadcrumb').innerHTML = '<span class="text-muted fw-light">Manage /</span> Chapters';
</script>

<!-- Content -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chapters Management</h5>
        <button class="btn btn-primary create-new-btn" data-entity="chapters">
            <i class="bx bx-plus me-1"></i> Add New Chapter
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table id="chapters-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Title</th>
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
        const chaptersData = <?php
            $chaptersArray = array_map(function($chapter) {
                return $chapter->jsonSerialize();
            }, $chapters);
            echo json_encode($chaptersArray);
        ?>;

        initDataTable('chapters-table', chaptersData, [
            { data: 'id' },
            { data: 'order_no' },
            { data: 'title' },
            { data: 'description' },
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
                            <button class="btn btn-sm btn-icon btn-outline-danger delete-record" data-id="${row.id}" data-entity="chapters">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]);
    });
</script>
