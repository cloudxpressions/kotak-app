@extends('admin.layouts.master')

@section('page-title', 'Newsletters')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Newsletters</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <a href="{{ route('admin.system.newsletters.create') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 5l0 14" />
      <path d="M5 12l14 0" />
    </svg>
    Create Newsletter
  </a>
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Newsletter Campaigns</h3>
      <p class="card-subtitle">Manage your newsletter campaigns and view analytics</p>
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
    <table id="newsletters-table" class="table card-table table-vcenter text-nowrap datatable">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input m-0 align-middle"></th>
          <th>Subject</th>
          <th>Status</th>
          <th>Recipients</th>
          <th>Sent</th>
          <th>Stats</th>
          <th>Scheduled For</th>
          <th>Created</th>
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

<!-- Send Newsletter Confirmation Modal -->
<div class="modal modal-blur fade" id="sendNewsletterModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Send</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <p>Are you sure you want to send this newsletter?</p>
          <p class="text-secondary mb-0"><strong id="modal-subject">Subject</strong></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary me-auto" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirm-send-btn">Send Now</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#newsletters-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.system.newsletters.index") }}',
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
            { data: 'subject', name: 'subject', searchable: true },
            {
                data: 'status_badge',
                name: 'status',
                orderable: false,
                render: function(data) {
                    return data;
                }
            },
            { data: 'total_recipients', name: 'total_recipients', searchable: true },
            { data: 'total_sent', name: 'total_sent', searchable: true },
            {
                data: 'stats',
                name: 'stats',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return data;
                }
            },
            {
                data: 'scheduled_for',
                name: 'scheduled_for',
                render: function(data, type, row) {
                    return row.scheduled_for_formatted || 'Immediate';
                }
            },
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
        order: [[7, 'desc']], // Order by created_at (index 7) descending
        pageLength: 10,
        dom: 'rt',
        buttons: [
            { extend: 'excel', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } },
            { extend: 'pdf', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } },
            { extend: 'csv', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } },
            { extend: 'copy', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } },
            { extend: 'print', className: 'd-none', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }
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

    // Send newsletter button click event
    $(document).on('click', '.send-btn', function() {
        const id = $(this).data('id');
        const subject = $(this).closest('tr').find('td:first').next().text();
        
        $('#modal-subject').text(subject);
        $('#confirm-send-btn').data('id', id);
        $('#sendNewsletterModal').modal('show');
    });

    // Confirm send button click event
    $('#confirm-send-btn').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: `/admin/system/newsletters/${id}/send`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#sendNewsletterModal').modal('hide');
                
                if (response.success) {
                    toastr.success(response.message);
                    table.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('#sendNewsletterModal').modal('hide');
                const errorMsg = xhr.responseJSON?.message || 'Failed to send newsletter';
                toastr.error(errorMsg);
            }
        });
    });

    // Delete newsletter button click event
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const subject = $(this).closest('tr').find('td:first').next().text();
        
        Swal.fire({
            title: 'Delete Newsletter?',
            text: `You are about to delete "${subject}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/system/newsletters/${id}`,
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
                            text: xhr.responseJSON?.message || 'Failed to delete newsletter',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Handle page length change
    $('#per-page').on('change', function() {
        table.page.len(this.value).draw();
    });

    // Handle search input
    $('#search-input').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Handle select all checkbox
    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    // Handle individual checkbox change
    $(document).on('change', '.row-checkbox', function() {
        if (!this.checked) {
            $('#select-all').prop('checked', false);
        } else if ($('.row-checkbox:checked').length === $('.row-checkbox').length) {
            $('#select-all').prop('checked', true);
        }
    });
});
</script>
@endpush