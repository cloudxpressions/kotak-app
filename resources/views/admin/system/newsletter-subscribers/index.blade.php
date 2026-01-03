@extends('admin.layouts.master')

@section('page-title', 'Newsletter Subscribers')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Newsletter Subscribers</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubscriberModal">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 5l0 14" />
      <path d="M5 12l14 0" />
    </svg>
    Add Subscriber
  </button>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Newsletter Subscribers</h3>
      <p class="card-subtitle">Manage newsletter subscription list</p>
    </div>
  </div>
  <div class="card-body border-bottom py-3">
    <div class="d-flex flex-wrap gap-2">
      <div class="text-secondary">
        Show
        <div class="mx-2 d-inline-block">
          <select id="per-page" class="form-select form-select-sm">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
        entries
      </div>
      <div class="ms-auto text-secondary">
        Search:
        <div class="ms-2 d-inline-block">
          <input type="text" class="form-control form-control-sm" id="search-input" placeholder="Search...">
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table id="subscribers-table" class="table card-table table-vcenter text-nowrap datatable">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Email</th>
          <th>Name</th>
          <th>Status</th>
          <th>Source</th>
          <th>Subscribe Date</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="card-footer d-flex align-items-center">
    <p class="m-0 text-secondary" id="table-info">Showing <span id="start">0</span> to <span id="end">0</span> of <span id="total">0</span> entries</p>
    <ul class="pagination m-0 ms-auto" id="pagination"></ul>
  </div>
</div>

<!-- Create Subscriber Modal -->
<div class="modal modal-blur fade" id="createSubscriberModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Subscriber</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="create-subscriber-form">
        <div class="modal-body">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="email" class="form-label required">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label required">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="pending">Pending</option>
              <option value="subscribed">Subscribed</option>
              <option value="unsubscribed">Unsubscribed</option>
              <option value="bounced">Bounced</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Subscriber</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Subscriber Modal -->
<div class="modal modal-blur fade" id="editSubscriberModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Subscriber</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="edit-subscriber-form">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-email" class="form-label required">Email</label>
                <input type="email" class="form-control" id="edit-email" name="email" required>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="edit-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="edit-name" name="name">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="edit-status" class="form-label required">Status</label>
            <select class="form-select" id="edit-status" name="status" required>
              <option value="pending">Pending</option>
              <option value="subscribed">Subscribed</option>
              <option value="unsubscribed">Unsubscribed</option>
              <option value="bounced">Bounced</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Subscriber</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Datatables initialization
    const table = $('#subscribers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.system.newsletter-subscribers.index") }}',
            data: function(d) {
                d.per_page = $('#per-page').val();
            }
        },
        columns: [
            {
                data: 'id',
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<input type="checkbox" class="row-checkbox form-check-input m-0 align-middle" value="${data}">`;
                }
            },
            { data: 'email', name: 'email', searchable: true },
            { data: 'name', name: 'name', searchable: true },
            {
                data: 'status_badge',
                name: 'status',
                orderable: false,
                render: function(data) {
                    return data;
                }
            },
            { data: 'source', name: 'source', searchable: true },
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data, type, row) {
                    return moment(row.created_at).format('YYYY-MM-DD HH:mm:ss');
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return data;
                }
            }
        ],
        order: [[5, 'desc']], // Order by date (index 5) descending
        pageLength: 10,
        dom: 'rt',
        buttons: [
            { extend: 'excel', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'pdf', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'csv', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'copy', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } },
            { extend: 'print', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5] } }
        ],
        drawCallback: function(settings) {
            var api = this.api();
            var info = api.page.info();

            $('#start').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end').text(info.end);
            $('#total').text(info.recordsTotal);

            // Update pagination
            updatePagination(info);
        }
    });

    // Handle page length change
    $('#per-page').on('change', function() {
        table.page.len(this.value).draw();
        updateURLParams({ per_page: this.value });
    });

    // Handle search
    $('#search-input').on('keyup', function() {
        table.search(this.value).draw();
        updateURLParams({ search: this.value });
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });

    // Handle individual row checkbox change
    $(document).on('change', '.row-checkbox', function() {
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

    // Update pagination controls
    function updatePagination(info) {
        const pagination = $('#pagination');
        pagination.empty();

        if (info.pages <= 1) return;

        const currentPage = info.page;
        const totalPages = info.pages;

        // Previous button
        pagination.append(`
            <li class="page-item ${currentPage === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${Math.max(currentPage - 1, 0)}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                    prev
                </a>
            </li>
        `);

        // Page numbers with ellipsis for large page sets
        const startPage = Math.max(0, currentPage - 2);
        const endPage = Math.min(totalPages - 1, currentPage + 2);

        if (startPage > 0) {
            pagination.append(`
                <li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>
            `);
            if (startPage > 1) {
                pagination.append(`
                    <li class="page-item disabled"><a class="page-link" href="#">…</a></li>
                `);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination.append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                </li>
            `);
        }

        if (endPage < totalPages - 1) {
            if (endPage < totalPages - 2) {
                pagination.append(`
                    <li class="page-item disabled"><a class="page-link" href="#">…</a></li>
                `);
            }
            pagination.append(`
                <li class="page-item"><a class="page-link" href="#" data-page="${totalPages - 1}">${totalPages}</a></li>
            `);
        }

        // Next button
        pagination.append(`
            <li class="page-item ${currentPage === totalPages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${Math.min(currentPage + 1, totalPages - 1)}">
                    next
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                </a>
            </li>
        `);

        // Handle pagination clicks
        $('#pagination a').off('click').on('click', function(e) {
            e.preventDefault();
            const page = parseInt($(this).data('page'));
            table.page(page).draw('page');
        });
    }

    // Function to update URL parameters
    function updateURLParams(params) {
        const newURL = new URL(window.location);
        for (const [key, value] of Object.entries(params)) {
            if (value) {
                newURL.searchParams.set(key, value);
            } else {
                newURL.searchParams.delete(key);
            }
        }
        window.history.replaceState({}, '', newURL);
    }

    // Create subscriber form submission
    $('#create-subscriber-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("admin.system.newsletter-subscribers.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#createSubscriberModal').modal('hide');
                toastr.success('Subscriber created successfully');
                table.ajax.reload();
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(field, messages) {
                        const inputField = $('#' + field);
                        inputField.addClass('is-invalid');
                        inputField.next('.invalid-feedback').text(messages[0]);
                    });
                } else {
                    toastr.error('Failed to create subscriber: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    // Edit subscriber modal
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `/admin/system/newsletter-subscribers/${id}/edit`,
            method: 'GET',
            success: function(response) {
                $('#edit-subscriber-form input[name="id"]').val(response.id);
                $('#edit-email').val(response.email);
                $('#edit-name').val(response.name);
                $('#edit-status').val(response.status);
                
                $('#editSubscriberModal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Failed to load subscriber data: ' + xhr.responseJSON.message);
            }
        });
    });

    // Update subscriber form submission
    $('#edit-subscriber-form').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit-subscriber-form input[name="id"]').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: `/admin/system/newsletter-subscribers/${id}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#editSubscriberModal').modal('hide');
                toastr.success('Subscriber updated successfully');
                table.ajax.reload();
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    $.each(errors, function(field, messages) {
                        const inputField = $('#' + field.replace('edit-', ''));
                        inputField.addClass('is-invalid');
                        inputField.next('.invalid-feedback').text(messages[0]);
                    });
                } else {
                    toastr.error('Failed to update subscriber: ' + xhr.responseJSON.message);
                }
            }
        });
    });

    // Delete subscriber
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
                    url: `/admin/system/newsletter-subscribers/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to delete subscriber',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Bulk delete functionality
    let selectedRows = [];
    
    // Select all checkboxes
    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedRows();
    });
    
    // Individual checkbox change
    $(document).on('change', '.row-checkbox', function() {
        updateSelectedRows();
    });
    
    // Update selected rows array
    function updateSelectedRows() {
        selectedRows = [];
        $('.row-checkbox:checked').each(function() {
            selectedRows.push($(this).val());
        });
        
        if (selectedRows.length > 0) {
            $('#bulk-actions').show();
            $('#selected-count').text(selectedRows.length);
        } else {
            $('#bulk-actions').hide();
        }
    }
    
    // Bulk delete button click
    $('#bulk-delete-btn').on('click', function() {
        if (selectedRows.length === 0) return;
        
        Swal.fire({
            title: 'Delete Multiple Subscribers?',
            text: `You are about to delete ${selectedRows.length} subscriber(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedRows.length} subscriber(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.system.newsletter-subscribers.bulk-delete") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        ids: selectedRows
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                        $('#select-all').prop('checked', false);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'Failed to delete subscribers',
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