@extends('admin.layouts.master')

@section('page-title', 'FAQs')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">Content</a></li>
  <li class="breadcrumb-item active" aria-current="page">FAQs</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  @can('faq.delete')
  <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="display:none;">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
    Delete Selected (<span id="selected-count">0</span>)
  </button>
  @endcan

  @can('faq.create')
  <a href="{{ route('admin.system.faqs.create') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12h14" /></svg>
    Add FAQ
  </a>
  @endcan
</div>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h3 class="card-title">Frequently Asked Questions</h3>
      <p class="card-subtitle">Manage reusable answers for the marketing and support pages.</p>
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
          <input type="text" class="form-control form-control-sm" id="search-input" placeholder="Search question or category">
        </div>
      </div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap" id="faqs-table">
      <thead>
        <tr>
          <th class="w-1"><input type="checkbox" id="select-all" class="form-check-input"></th>
          <th>Question</th>
          <th>Category</th>
          <th>Featured</th>
          <th>Sort Order</th>
          <th>Status</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
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
$(function() {
  let selectedIds = [];
  const table = $('#faqs-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("admin.system.faqs.index") }}',
    columns: [
      { data: 'id', orderable: false, searchable: false, render: function(data) {
          return `<input type="checkbox" class="row-checkbox form-check-input" value="${data}">`;
        }
      },
      { data: 'question', name: 'question' },
      { data: 'category', name: 'category' },
      { data: 'featured_badge', orderable: false, searchable: false },
      { data: 'sort_order', name: 'sort_order' },
      { data: 'status_badge', orderable: false, searchable: false },
      { data: 'action', orderable: false, searchable: false }
    ],
    order: [[4, 'asc']],
    pageLength: 10,
    dom: 'Brt',
    drawCallback: function(settings) {
      const info = this.api().page.info();
      $('#start').text(info.recordsDisplay > 0 ? info.start + 1 : 0);
      $('#end').text(info.end);
      $('#total').text(info.recordsTotal);
      updatePagination(info);
    }
  });

  $('#search-input').on('keyup', function() {
    table.search(this.value).draw();
  });

  $('#per-page').on('change', function() {
    table.page.len(this.value).draw();
  });

  function updatePagination(info) {
    const pagination = $('#pagination');
    pagination.empty();
    if (info.pages <= 1) return;

    pagination.append(`<li class="page-item ${info.page === 0 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page - 1}">Prev</a></li>`);

    for (let i = 0; i < info.pages; i++) {
      if (i === 0 || i === info.pages - 1 || (i >= info.page - 1 && i <= info.page + 1)) {
        pagination.append(`<li class="page-item ${i === info.page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`);
      } else if (i === info.page - 2 || i === info.page + 2) {
        pagination.append('<li class="page-item disabled"><span class="page-link">…</span></li>');
      }
    }

    pagination.append(`<li class="page-item ${info.page === info.pages - 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${info.page + 1}">Next</a></li>`);
  }

  $(document).on('click', '#pagination a', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (typeof page !== 'undefined' && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
      table.page(page).draw('page');
    }
  });

  $('#select-all').on('change', function() {
    $('.row-checkbox').prop('checked', this.checked);
    updateSelected();
  });

  $(document).on('change', '.row-checkbox', updateSelected);

  function updateSelected() {
    selectedIds = [];
    $('.row-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (selectedIds.length) {
      $('#bulk-delete-btn').show();
      $('#selected-count').text(selectedIds.length);
    } else {
      $('#bulk-delete-btn').hide();
    }
  }

  @can('faq.delete')
  $('#bulk-delete-btn').on('click', function() {
    if (!selectedIds.length) { return; }
    Swal.fire({
      title: 'Delete FAQs?',
      text: `You are about to delete ${selectedIds.length} FAQ(s).`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d63939',
      confirmButtonText: 'Yes, delete',
    }).then(result => {
      if (result.isConfirmed) {
        $.post({
          url: '{{ route("admin.system.faqs.bulk-delete") }}',
          data: { ids: selectedIds, _token: '{{ csrf_token() }}' },
          success(response) {
            toastr.success(response.message);
            selectedIds = [];
            $('#select-all').prop('checked', false);
            $('#bulk-delete-btn').hide();
            table.ajax.reload();
          },
          error(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Bulk deletion failed');
          }
        });
      }
    });
  });
  @endcan

  @can('faq.delete')
  $(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Delete FAQ?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d63939',
      confirmButtonText: 'Yes, delete'
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: `/admin/system/faqs/${id}`,
          method: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success(response) {
            toastr.success(response.message);
            table.ajax.reload();
          },
          error(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to delete FAQ');
          }
        });
      }
    });
  });
  @endcan
});
</script>
@endpush
