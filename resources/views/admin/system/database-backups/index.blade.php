@extends('admin.layouts.master')

@section('page-title', 'Database Backups')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Database Backups</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
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

  <button type="button" class="btn btn-primary" id="create-backup-btn">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 -1.82l-.28 -1.11l-.82 -3.24a2 2 0 0 0 -1.9 -1.48h-8.24a2 2 0 0 0 -2 1.48l-.82 3.24l-.28 1.11a1.65 1.65 0 0 0 .33 1.82l.5 1.5" />
      <path d="M4.5 15.5l.4 .5h14.2l.4 -.5" />
      <path d="M12 7v5" />
    </svg>
    Create Backup Now
  </button>
</div>
@endsection

@section('content')
<div class="alert alert-warning">
  <div class="d-flex">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
    <div>
      <strong>Warning!</strong> Backup ZIP files contain sensitive data. Do not share with unauthorized personnel.
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Database Backup Management</h3>
      <p class="card-subtitle">Manage your database backups and their lifecycle</p>
    </div>
  </div>
  <div class="card-body border-bottom py-3">
    <div class="d-flex">
      <div class="text-secondary">
        Database Backup Files
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table id="backup-table" class="table card-table table-vcenter text-nowrap datatable">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Backup File</th>
          <th>Size</th>
          <th>Created At</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Data will be loaded via AJAX DataTables -->
      </tbody>
    </table>
  </div>
</div>

<div class="modal modal-blur fade" id="createBackupModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Creating Backup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <p class="text-center mt-2">Creating database backup. This may take a few minutes...</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#backup-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.system.database-backups.index") }}',
        columns: [
            {
                data: 'name',
                name: 'name',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
                }
            },
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex py-1 align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                            <div class="flex-fill ps-3">
                                <div class="font-weight-medium">${data}</div>
                            </div>
                        </div>
                    `;
                }
            },
            { data: 'size', name: 'size' },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'name',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-list flex-nowrap">
                            <a href="{{ url('/admin/system/database-backups/download') }}/${data}"
                               class="btn btn-sm btn-icon btn-primary"
                               title="Download"
                               target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                    <path d="M7 11l5 5l5 -5" />
                                    <path d="M12 4l0 12" />
                                </svg>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-icon btn-danger delete-btn ms-1"
                                    data-filename="${data}"
                                    title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                    `;
                }
            }
        ]
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

    // Handle individual row checkbox change to update select-all state
    $(document).on('change', '.row-checkbox', function() {
        const allCheckboxes = $('.row-checkbox');
        const checkedCheckboxes = $('.row-checkbox:checked');

        $('#select-all').prop('checked', allCheckboxes.length === checkedCheckboxes.length);
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
        const selectedFiles = $('.row-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedFiles.length === 0) {
            toastr.warning('Please select at least one backup to delete.');
            return;
        }

        Swal.fire({
            title: 'Delete Multiple Backups?',
            text: `You are about to delete ${selectedFiles.length} backup(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedFiles.length} backup(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Process each selected file for deletion
                let deletedCount = 0;
                let errorCount = 0;

                // Process deletions sequentially to avoid conflicts
                function processDeletion(index) {
                    if (index >= selectedFiles.length) {
                        // All files processed
                        const message = `Successfully deleted ${deletedCount} backup(s).`;
                        toastr.success(message);

                        // Reload the table to reflect changes
                        table.ajax.reload();

                        return;
                    }

                    const filename = selectedFiles[index];

                    $.ajax({
                        url: `/admin/system/database-backups/${filename}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                deletedCount++;
                            } else {
                                errorCount++;
                                console.error(`Failed to delete ${filename}:`, response.message);
                            }
                        },
                        error: function(xhr) {
                            errorCount++;
                            console.error(`Error deleting ${filename}:`, xhr);
                        },
                        complete: function() {
                            processDeletion(index + 1); // Process next file
                        }
                    });
                }

                processDeletion(0); // Start processing
            }
        });
    });

    // Create backup button click event
    $('#create-backup-btn').on('click', function() {
        $('#createBackupModal').modal('show');

        $.ajax({
            url: '{{ route("admin.system.database-backups.create") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#createBackupModal').modal('hide');
                toastr.success(response.message || 'Backup created successfully');
                table.ajax.reload();
            },
            error: function(xhr) {
                $('#createBackupModal').modal('hide');
                toastr.error(xhr.responseJSON?.message || 'Failed to create backup');
            }
        });
    });

    // Delete backup button click event using event delegation
    $(document).on('click', '.delete-btn', function() {
        const filename = $(this).data('filename');

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
                    url: `/admin/system/database-backups/${filename}`,
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

                            // Hide bulk delete button after deletion
                            $('#bulk-delete-btn').hide();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to delete backup',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush