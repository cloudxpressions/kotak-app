@extends('admin.layouts.master')

@section('page-title', 'Chapters')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">Insurance</a></li>
  <li class="breadcrumb-item active" aria-current="page">Chapters</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  @can('chapter.delete')
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

  @can('chapter.create')
  <a href="{{ route('admin.insurance.chapters.create') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 5l0 14" />
      <path d="M5 12l14 0" />
    </svg>
    Add Chapter
  </a>
  @endcan
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Chapter Management</h3>
      <p class="card-subtitle">Manage chapters and their translations</p>
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
    <table class="table card-table table-vcenter text-nowrap datatable" id="chapters-table">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Title (All Languages)</th>
          <th>Exam</th>
          <th>Category</th>
          <th>Order</th>
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
    const table = $('#chapters-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.insurance.chapters.index") }}',
        columns: [
            { data: 'id', orderable: false, searchable: false, render: function(data) {
                return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
            }},
            { data: 'title', name: 'title', orderable: false, searchable: true },
            { data: 'exam', name: 'exam', orderable: true, searchable: true },
            { data: 'category', name: 'category', orderable: true, searchable: true },
            { data: 'order_no', name: 'order_no', orderable: true, searchable: false },
            { data: 'status_badge', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
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
                    url: `/admin/insurance/chapters/${id}`,
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
                            text: xhr.responseJSON?.message || 'Failed to delete chapter',
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
            title: 'Delete Multiple Chapters?',
            text: `You are about to delete ${selectedIds.length} chapter(s). This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, delete ${selectedIds.length} chapter(s)!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.insurance.chapters.bulk-delete") }}',
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