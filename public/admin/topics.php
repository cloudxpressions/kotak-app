<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Views/partials/header.php';
require_once __DIR__ . '/../../app/Views/partials/sidebar.php';
require_once __DIR__ . '/../../app/Views/partials/navbar.php';

use App\Models\Topic;

// Fetch topics data
$topics = Topic::all();
?>

<!-- Content -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Topics Management</h5>
        <button class="btn btn-primary create-new-btn" data-entity="topics">
            <i class="bx bx-plus me-1"></i> Add New
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table id="topics-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
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
        const topicsData = <?php
            $topicsArray = array_map(function($topic) {
                return $topic->jsonSerialize();
            }, $topics);
            echo json_encode($topicsArray);
        ?>;

        initDataTable('topics-table', topicsData, [
            { data: 'id' },
            { data: 'title' },
            { data: 'type' },
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
                    return getActionButtons(row.id, 'topics');
                }
            }
        ]);
    });
</script>
