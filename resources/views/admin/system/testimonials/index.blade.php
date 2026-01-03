@extends('admin.layouts.master')

@section('page-title', 'Testimonials')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Content</a></li>
    <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  @can('testimonials.delete')
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
  @endcan

  @can('testimonials.create')
  <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 5l0 14" />
      <path d="M5 12l14 0" />
    </svg>
    Add Testimonial
  </a>
  @endcan
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Testimonial Management</h3>
      <p class="card-subtitle">Manage customer testimonials and reviews</p>
    </div>
  </div>
  <div class="card-body border-bottom py-3">
    <div class="d-flex">
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
    <table class="table card-table table-vcenter text-nowrap datatable" id="testimonials-table">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Name</th>
          <th>Designation</th>
          <th>Message</th>
          <th>Rating</th>
          <th>Sort Order</th>
          <th>Status</th>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let selectedIds = [];

    // Initialize DataTable
    const table = $('#testimonials-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.testimonials.index") }}',
        columns: [
            { data: 'id', orderable: false, searchable: false, render: function(data) {
                return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
            }},
            { data: 'name', name: 'name', searchable: true },
            { data: 'designation', name: 'designation', searchable: true },
            { data: 'message', name: 'message', searchable: true, orderable: false },
            { data: 'rating_stars', name: 'rating', searchable: false, orderable: true },
            { data: 'sort_order', name: 'sort_order', searchable: false },
            { data: 'status_badge', name: 'is_visible', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[5, 'asc']], // Sort by sort_order by default
        pageLength: 10,
        dom: 'Brt',
        buttons: [],
        drawCallback: function(settings) {
            var api = this.api();
            var info = api.page.info();

            $('#start').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end').text(info.end);
            $('#total').text(info.recordsTotal);

            updatePagination(info);
        }
    });

    // Event listeners for checkboxes
    $(document).on('change', '.row-checkbox', function() {
        // Add row selection logic here if needed in the future
    });

    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
    });

    // Delete functionality
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
                    url: `/admin/testimonials/${id}`,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
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
                            text: xhr.responseJSON?.message || 'Failed to delete testimonial',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    // Custom search
    $('#search-input').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Custom page length
    $('#per-page').on('change', function() {
        table.page.len(this.value).draw();
    });

    // Custom pagination function
    function updatePagination(info) {
        var pagination = $('#pagination');
        pagination.empty();

        if (info.pages <= 1) return;

        var currentPage = info.page;
        var totalPages = info.pages;

        pagination.append(`
            <li class="page-item ${currentPage === 0 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                    prev
                </a>
            </li>
        `);

        for (var i = 0; i < totalPages; i++) {
            if (i === 0 || i === totalPages - 1 || (i >= currentPage - 1 && i <= currentPage + 1)) {
                pagination.append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i + 1}</a>
                    </li>
                `);
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">…</span></li>`);
            }
        }

        pagination.append(`
            <li class="page-item ${currentPage === totalPages - 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">
                    next
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                </a>
            </li>
        `);
    }

    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            table.page(parseInt($(this).data('page'))).draw('page');
        }
    });

    // Bulk delete functionality
    let selectedIds = [];

    // Update selected IDs
    function updateSelectedIds() {
        selectedIds = [];
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            $('#bulk-delete-btn').show();
            $('#selected-count').text(selectedIds.length);
        } else {
            $('#bulk-delete-btn').hide();
        }
    }

    // Event listeners for checkboxes
    $(document).on('change', '.row-checkbox', function() {
        updateSelectedIds();
    });

    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedIds();
    });

    // Bulk delete button click handler
    $('#bulk-delete-btn').on('click', function() {
        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Delete Multiple Testimonials?',
            text: `You are about to delete ${selectedIds.length} testimonial(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedIds.length} testimonial(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.testimonials.bulk-delete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
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

                            // Reset selections
                            selectedIds = [];
                            $('#select-all').prop('checked', false);
                            $('#bulk-delete-btn').hide();

                            // Reload the table
                            table.ajax.reload();
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
});
</script>
@endpush