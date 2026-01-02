<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Views/partials/header.php';
require_once __DIR__ . '/../../app/Views/partials/sidebar.php';
require_once __DIR__ . '/../../app/Views/partials/navbar.php';

use App\Models\Question;

// Fetch questions data
$questions = Question::all();
?>

<!-- Content -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Questions Bank</h5>
        <button class="btn btn-primary create-new-btn" data-entity="questions">
            <i class="bx bx-plus me-1"></i> Add New
        </button>
    </div>
    <div class="card-body">
         <div class="table-responsive text-nowrap">
            <table id="questions-table" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Difficulty</th>
                        <th>Question</th>
                        <th>Correct Ans</th>
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
        const questionsData = <?php
            $questionsArray = array_map(function($question) {
                return $question->jsonSerialize();
            }, $questions);
            echo json_encode($questionsArray);
        ?>;

        initDataTable('questions-table', questionsData, [
            { data: 'id' },
            { data: 'difficulty' },
            {
                data: 'question_text',
                render: function(data) {
                    return data.length > 50 ? data.substr(0, 50) + '...' : data;
                }
            },
            { data: 'correct_answer' },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-icon btn-outline-primary edit-record me-2" data-id="${row.id}">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-icon btn-outline-danger delete-record" data-id="${row.id}" data-entity="questions">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]);
    });
</script>
