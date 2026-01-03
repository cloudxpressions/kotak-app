@extends('admin.layouts.master')

@section('page-title', 'Date Formats')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Date Formats</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <button type="button" class="btn btn-outline-secondary" id="import-btn">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
      <path d="M12 11v6" />
      <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
    </svg>
    Import
  </button>

  <div class="btn-group">
    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
        <path d="M12 17v-6" />
        <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
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
        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
        Print
      </a></li>
    </ul>
  </div>

  @can('dateformat.delete')
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

  @can('dateformat.create')
  <a href="{{ route('admin.system.date-formats.create') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 5l0 14" />
      <path d="M5 12l14 0" />
    </svg>
    Add Date Format
  </a>
  @endcan
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Date Format Management</h3>
      <p class="card-subtitle">Manage date formats for your application</p>
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
    <table class="table card-table table-vcenter text-nowrap datatable" id="date-formats-table">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Format</th>
          <th>Normal View</th>
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
    const table = $('#date-formats-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.system.date-formats.index") }}',
        columns: [
            { data: 'id', orderable: false, searchable: false, render: function(data) {
                return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
            }},
            { data: 'format', name: 'format', searchable: true },
            { data: 'normal_view', name: 'normal_view', searchable: true },
            { data: 'status_badge', name: 'is_active', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        dom: 'Brt',
        buttons: [
            { extend: 'excel', className: 'd-none', exportOptions: { columns: [1, 2, 3] } },
            { extend: 'pdf', className: 'd-none', exportOptions: { columns: [1, 2, 3] } },
            { extend: 'csv', className: 'd-none', exportOptions: { columns: [1, 2, 3] } },
            { extend: 'copy', className: 'd-none', exportOptions: { columns: [1, 2, 3] } },
            { extend: 'print', className: 'd-none', exportOptions: { columns: [1, 2, 3] } }
        ],
        drawCallback: function(settings) {
            var api = this.api();
            var info = api.page.info();

            $('#start').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
            $('#end').text(info.end);
            $('#total').text(info.recordsTotal);

            updatePagination(info);
        }
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

    // Import button
    $('#import-btn').on('click', function() {
        toastr.info('Import functionality coming soon', 'Info');
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

    $('#select-all').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectedIds();
    });

    $(document).on('change', '.row-checkbox', function() {
        updateSelectedIds();
    });

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
                    url: `/admin/system/date-formats/${id}`,
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
                            text: xhr.responseJSON?.message || 'Failed to delete date format',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

    $('#bulk-delete-btn').on('click', function() {
        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Delete Multiple Date Formats?',
            text: `You are about to delete ${selectedIds.length} date format(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedIds.length} date format(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.system.date-formats.bulk-delete") }}',
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
                            selectedIds = [];
                            $('#select-all').prop('checked', false);
                            $('#bulk-delete-btn').hide();
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