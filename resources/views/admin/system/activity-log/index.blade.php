@extends('admin.layouts.master')

@section('page-title', 'Activity Logs')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">System</a></li>
    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 13.5l2.5 2.5l2.5 -2.5" />
            </svg>
            Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" id="export-excel">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M10 12l4 5" /><path d="M10 17l4 -5" /></svg>
                Export to Excel
            </a></li>
            <li><a class="dropdown-item" href="#" id="export-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" /><path d="M17 18h2" /><path d="M20 15h-3v6" /><path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" /></svg>
                Export to PDF
            </a></li>
            <li><a class="dropdown-item" href="#" id="export-csv">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 10v4h4" /><path d="M12 14l1.5 -1.5" /></svg>
                Export to CSV
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" id="export-copy">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                Copy to Clipboard
            </a></li>
            <li><a class="dropdown-item" href="#" id="export-print">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M7 17v-4h-2" /><path d="M17 7v.01" /></svg>
                Print
            </a></li>
        </ul>
    </div>

    <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 7l16 0" />
            <path d="M10 11l0 6" />
            <path d="M14 11l0 6" />
            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        </svg>
        Delete Selected (<span id="selected-count">0</span>)
    </button>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Activity Logs</h3>
            <p class="card-subtitle">Track all activities in the system</p>
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center">
                <label class="form-label me-2">Log Group:</label>
                <select id="filter-log-name" class="form-select form-select-sm me-2">
                    <option value="">All Groups</option>
                    @foreach($logNames as $logName)
                        <option value="{{ $logName }}">{{ ucfirst($logName) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-center">
                <label class="form-label me-2">Action:</label>
                <select id="filter-description" class="form-select form-select-sm me-2">
                    <option value="">All Actions</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="activity-log-table">
            <thead>
                <tr>
                    <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
                    <th>Action</th>
                    <th>Log Group</th>
                    <th>User</th>
                    <th>Record Details</th>
                    <th>Date</th>
                    <th class="w-1">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#activity-log-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.system.activity-log.index") }}',
            data: function(d) {
                d.log_name = $('#filter-log-name').val();
                d.description = $('#filter-description').val();
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
                }
            },
            {
                data: 'description',
                name: 'description',
                orderable: true,
                render: function(data, type, row) {
                    const actionMap = {
                        'created': {'text': 'Created', 'class': 'bg-success-lt'},
                        'updated': {'text': 'Updated', 'class': 'bg-warning-lt'},
                        'deleted': {'text': 'Deleted', 'class': 'bg-danger-lt'}
                    };
                    const action = actionMap[data.toLowerCase()] || {'text': data.charAt(0).toUpperCase() + data.slice(1), 'class': 'bg-primary-lt'};
                    return `<span class="badge ${action.class}">${action.text}</span>`;
                }
            },
            {
                data: 'log_name',
                name: 'log_name',
                orderable: true,
                render: function(data, type, row) {
                    return `<span class="badge bg-blue-lt">${data || 'default'}</span>`;
                }
            },
            {
                data: 'user',
                name: 'causer.name',
                orderable: false,
                render: function(data, type, row) {
                    if (row.causer) {
                        return `<div class="d-flex align-items-center">
                                    <span class="avatar avatar-xs me-2" style="background-image: url('${row.causer.avatar || '/assets/backend/img/default-avatar.png'}')"></span>
                                    <div>${row.causer.name}</div>
                                </div>`;
                    }
                    return 'System';
                }
            },
            {
                data: 'changes',
                name: 'properties',
                orderable: false,
                render: function(data, type, row) {
                    // Show record details and changes in a more compact way
                    let detailsHtml = '';

                    // Show changes if any
                    const oldValues = row.properties?.old || {};
                    const newValues = row.properties?.attributes || {};

                    if (Object.keys(oldValues).length > 0 || Object.keys(newValues).length > 0) {
                        // Limit number of changes shown for readability
                        const changesToShow = Object.keys(newValues).slice(0, 3); // Show first 3 changes
                        for (const key of changesToShow) {
                            const oldValue = oldValues[key] !== undefined ? oldValues[key] : 'N/A';
                            const newValue = newValues[key] !== undefined ? newValues[key] : 'N/A';

                            if (changesToShow.length === 1) {
                                // If only one change, show it inline
                                detailsHtml += `<div><span class="text-muted">${key}:</span> <span class="fw-medium">${oldValue}</span> → <span class="fw-medium">${newValue}</span></div>`;
                            } else {
                                // If multiple changes, show as list
                                detailsHtml += `<div><span class="text-muted">${key}:</span> ${oldValue} → ${newValue}</div>`;
                            }
                        }

                        // If there are more changes than we're showing, indicate it
                        if (Object.keys(newValues).length > 3) {
                            detailsHtml += `<div class="text-muted small">+${Object.keys(newValues).length - 3} more changes</div>`;
                        }
                    } else {
                        detailsHtml += '<span class="text-muted">No property changes</span>';
                    }

                    return detailsHtml;
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                orderable: true,
                render: function(data, type, row) {
                    return moment(row.created_at).format('YYYY-MM-DD<br>HH:mm:ss');
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        order: [[5, 'desc']], // Order by date (index 5) descending
        pageLength: 25,
        dom: 'rt',
        buttons: [
            { extend: 'excel', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'pdf', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'csv', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'copy', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'print', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } }
        ],
    });

    // Handle individual row checkbox change
    $(document).on('change', '.row-checkbox', function() {
        updateSelectedCount();
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });

    // Update selected count and show/hide bulk delete button
    function updateSelectedCount() {
        const selectedCount = $('.row-checkbox:checked').length;
        $('#selected-count').text(selectedCount);
        
        if (selectedCount > 0) {
            $('#bulk-delete-btn').show();
        } else {
            $('#bulk-delete-btn').hide();
        }
    }

    // Bulk delete button click event
    $('#bulk-delete-btn').on('click', function() {
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            toastr.warning('Please select at least one log to delete.');
            return;
        }

        Swal.fire({
            title: 'Delete Multiple Logs?',
            text: `You are about to delete ${selectedIds.length} log(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedIds.length} log(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.system.activity-log.bulk-delete") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        ids: selectedIds
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                            $('#select-all').prop('checked', false);
                            $('#bulk-delete-btn').hide();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Bulk deletion failed',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Delete activity log button click event using event delegation
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('admin.system.activity-log.destroy', ['activity' => '__id__']) }}`.replace('__id__', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to delete activity log',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Export functionality
    $('#export-excel').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-excel').trigger();
    });

    $('#export-pdf').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-pdf').trigger();
    });

    $('#export-csv').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-csv').trigger();
    });

    $('#export-copy').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-copy').trigger();
        toastr.success('Data copied to clipboard', 'Success');
    });

    $('#export-print').on('click', function(e) {
        e.preventDefault();
        table.button('.buttons-print').trigger();
    });

    // Filter change events
    $('#filter-log-name, #filter-description').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endpush