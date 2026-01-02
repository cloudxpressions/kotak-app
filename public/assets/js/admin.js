
// Initialize when page loads
$(document).ready(function () {
    checkAuth();

    // Setup Global Events
    setupGlobalEvents();
    setupLayoutEvents();
});

function setupLayoutEvents() {
    // Sidebar Toggle
    $('#layout-menu-toggle').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('layout-menu-expanded');
    });

    // Close on overlay click
    if ($('.layout-overlay').length === 0) {
        $('.layout-wrapper').append('<div class="layout-overlay"></div>');
    }

    $(document).on('click', '.layout-overlay', function () {
        $('body').removeClass('layout-menu-expanded');
    });
}

function checkAuth() {
    const token = localStorage.getItem('admin_token');
    if (!token && !window.location.pathname.includes('login.php')) {
        window.location.href = '/login.php';
    }
}

function setupGlobalEvents() {
    // Logout
    $(document).on('click', '#logoutLink, #logoutBtn', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Logout?',
            text: "Are you sure you want to log out?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('admin_token');
                window.location.href = '/login.php';
            }
        });
    });
}

// Global CRUD Helpers
const entityConfig = {
    users: {
        title: 'User',
        api: '/admin/users',
        fields: [
            { name: 'name', label: 'Full Name', type: 'text', placeholder: 'John Doe' },
            { name: 'email', label: 'Email Address', type: 'email', placeholder: 'john@example.com' },
            { name: 'mobile', label: 'Mobile Number', type: 'text', placeholder: '9876543210' },
            { name: 'exam_target', label: 'Exam Target', type: 'select', options: ['IC-38', 'IC-39', 'General'] },
            { name: 'preferred_language', label: 'Preferred Language', type: 'select', options: ['English', 'Hindi', 'Tamil', 'Malayalam'] }
        ]
    },
    chapters: {
        title: 'Chapter',
        api: '/admin/chapters',
        fields: [
            { name: 'title', label: 'Chapter Title', type: 'text', placeholder: 'Intro to Insurance' },
            { name: 'description', label: 'Description', type: 'textarea', placeholder: 'Short summary...' },
            { name: 'order_no', label: 'Sort Order', type: 'number', placeholder: '1' },
            { name: 'is_active', label: 'Status', type: 'select', options: ['1', '0'] }
        ]
    },
    topics: {
        title: 'Topic',
        api: '/admin/topics',
        fields: [
            { name: 'chapter_id', label: 'Chapter ID', type: 'text' },
            { name: 'title', label: 'Topic Title', type: 'text' },
            { name: 'type', label: 'Type', type: 'select', options: ['Theory', 'Calculation', 'Practical'] },
            { name: 'order_no', label: 'Sort Order', type: 'number', placeholder: '1' },
            { name: 'is_active', label: 'Status', type: 'select', options: ['1', '0'] }
        ]
    },
    questions: {
        title: 'Question',
        api: '/admin/questions',
        fields: [
            { name: 'difficulty', label: 'Difficulty', type: 'text', placeholder: 'Easy/Medium/Hard' },
            { name: 'correct_option', label: 'Correct Answer', type: 'text', placeholder: 'A/B/C/D' },
            { name: 'is_active', label: 'Status', type: 'select', options: ['1', '0'] }
        ]
    },
    terminologies: {
        title: 'Terminology',
        api: '/admin/terminologies',
        fields: [
            { name: 'category', label: 'Category', type: 'text', placeholder: 'Insurance Terms' },
            { name: 'is_active', label: 'Status', type: 'select', options: ['1', '0'] }
        ]
    },
    tests: {
        title: 'Test',
        api: '/admin/tests',
        fields: [
            { name: 'name', label: 'Test Name', type: 'text', placeholder: 'Mock Test 1' },
            { name: 'description', label: 'Description', type: 'textarea', placeholder: 'Test description...' },
            { name: 'is_active', label: 'Status', type: 'select', options: ['1', '0'] }
        ]
    }
};

// Initialize DataTable - can work with AJAX or pre-loaded data
function initDataTable(tableId, source, columns) {
    let config = {
        columns: columns,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"></div>',
            search: "_INPUT_",
            searchPlaceholder: "Search records..."
        }
    };

    // Check if source is a URL (contains http or starts with /) or data array
    if (typeof source === 'string' && (source.startsWith('/') || source.startsWith('http'))) {
        // It's an API endpoint
        config.ajax = {
            url: source,
            dataSrc: 'data'
        };
    } else {
        // It's pre-loaded data
        config.data = source;
    }

    return $(`#${tableId}`).DataTable(config);
}

// Show CRUD modal for create/update
function showCrudModal(entity, record = null) {
    const config = entityConfig[entity];
    if (!config) {
        console.error(`Entity ${entity} not configured`);
        return;
    }

    // Set modal title
    const title = record ? `Edit ${config.title}` : `Add New ${config.title}`;
    $('#modalTitle').text(title);

    // Clear form
    $('#crudForm')[0].reset();
    $('#id').val(record ? record.id : '');

    // Generate form fields
    let fieldsHtml = '';
    config.fields.forEach(field => {
        const value = record ? (record[field.name] || '') : '';
        let fieldHtml = '';

        if (field.type === 'select') {
            fieldHtml = `
                <div class="mb-3">
                    <label class="form-label">${field.label}</label>
                    <select name="${field.name}" class="form-control" ${record && field.name === 'id' ? 'disabled' : ''}>
                        ${field.options.map(option =>
                            `<option value="${option}" ${value == option ? 'selected' : ''}>${option}</option>`
                        ).join('')}
                    </select>
                </div>
            `;
        } else if (field.type === 'textarea') {
            fieldHtml = `
                <div class="mb-3">
                    <label class="form-label">${field.label}</label>
                    <textarea name="${field.name}" class="form-control" placeholder="${field.placeholder || ''}"
                        ${record && field.name === 'id' ? 'disabled' : ''}>${value}</textarea>
                </div>
            `;
        } else {
            fieldHtml = `
                <div class="mb-3">
                    <label class="form-label">${field.label}</label>
                    <input type="${field.type}" name="${field.name}" class="form-control"
                        placeholder="${field.placeholder || ''}" value="${value}"
                        ${record && field.name === 'id' ? 'disabled' : ''}>
                </div>
            `;
        }

        fieldsHtml += fieldHtml;
    });

    $('.dynamic-fields').html(fieldsHtml);

    // Show modal
    $('#crudModal').modal('show');

    // Set up save button handler
    $('#saveBtn').off('click').on('click', function() {
        saveRecord(entity, config.api);
    });
}

// Save record (create/update)
function saveRecord(entity, apiEndpoint) {
    const formData = new FormData($('#crudForm')[0]);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    // Determine if it's create or update
    const id = $('#id').val();
    const method = id ? 'PUT' : 'POST';
    const url = id ? `${apiEndpoint}/${id}` : apiEndpoint;

    $.ajax({
        url: url,
        method: method,
        data: JSON.stringify(data),
        contentType: 'application/json',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`
        },
        success: function(response) {
            if (response.success) {
                $('#crudModal').modal('hide');
                toastr.success(response.message || 'Record saved successfully');

                // Refresh the DataTable
                const tableId = `#${entity}-table`;
                if ($(tableId).length && $.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().ajax.reload();
                }
            } else {
                toastr.error(response.message || 'Error saving record');
            }
        },
        error: function(xhr) {
            const response = JSON.parse(xhr.responseText);
            toastr.error(response.message || 'Error saving record');
        }
    });
}

// Setup CRUD event handlers
function setupCrudEvents() {
    // Handle create new button clicks
    $(document).off('click', '.create-new-btn').on('click', '.create-new-btn', function() {
        const entity = $(this).data('entity');
        showCrudModal(entity);
    });

    // Handle edit button clicks
    $(document).off('click', '.edit-record').on('click', '.edit-record', function() {
        const id = $(this).data('id');
        const entity = $(this).closest('button').data('entity') ||
                      $(this).closest('tr').closest('table').attr('id').replace('-table', '');

        // Fetch record data
        const config = entityConfig[entity];
        if (config) {
            $.get(`${config.api}/${id}`, function(response) {
                if (response.success) {
                    showCrudModal(entity, response.data);
                }
            });
        }
    });

    // Handle delete button clicks
    $(document).off('click', '.delete-record').on('click', '.delete-record', function() {
        const id = $(this).data('id');
        const entity = $(this).data('entity');
        const config = entityConfig[entity];

        if (config) {
            Swal.fire({
                title: 'Delete Record?',
                text: `Are you sure you want to delete this ${config.title}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${config.api}/${id}`,
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message || 'Record deleted successfully.', 'success');

                                // Refresh the DataTable
                                const tableId = `#${entity}-table`;
                                if ($(tableId).length && $.fn.DataTable.isDataTable(tableId)) {
                                    $(tableId).DataTable().ajax.reload();
                                }
                            } else {
                                Swal.fire('Error!', response.message || 'Error deleting record.', 'error');
                            }
                        },
                        error: function(xhr) {
                            const response = JSON.parse(xhr.responseText);
                            Swal.fire('Error!', response.message || 'Error deleting record.', 'error');
                        }
                    });
                }
            });
        }
    });
}

// Helper function to generate action buttons
function getActionButtons(id, entity) {
    return `
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-icon btn-outline-primary edit-record me-2" data-id="${id}">
                <i class="bx bx-edit-alt"></i>
            </button>
            <button class="btn btn-sm btn-icon btn-outline-danger delete-record" data-id="${id}" data-entity="${entity}">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    `;
}

// Initialize CRUD events when document is ready
$(document).ready(function() {
    setupCrudEvents();
});
