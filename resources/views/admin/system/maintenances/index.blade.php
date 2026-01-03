@extends('admin.layouts.master')

@section('page-title', 'Maintenance Windows')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Maintenance</li>
</ol>
@endsection

@section('page-actions')
@can('maintenance.create')
<a href="{{ route('admin.system.maintenances.create') }}" class="btn btn-primary">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12h14" /></svg>
  Create Entry
</a>
@endcan
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Maintenance Windows</h3>
      <p class="card-subtitle">Manage maintenance mode schedules and content.</p>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap datatable" id="maintenances-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Status</th>
          <th>Schedule</th>
          <th>Updated</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const table = $('#maintenances-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.system.maintenances.index') }}',
    columns: [
      { data: 'title', name: 'title', defaultContent: '--' },
      { data: 'status_badge', name: 'maintenance_mode', orderable: false, searchable: false },
      { data: 'schedule', orderable: false, searchable: false },
      { data: 'updated_at', name: 'updated_at', defaultContent: '--' },
      { data: 'action', orderable: false, searchable: false }
    ],
    order: [[3, 'desc']],
    drawCallback: function () {
      $('[data-bs-toggle="tooltip"]').tooltip();
    }
  });

  $(document).on('click', '.delete-maintenance', function () {
    const url = $(this).data('url');
    if (!url) return;

    if (!confirm('Delete this maintenance entry?')) {
      return;
    }

    $.ajax({
      url,
      method: 'POST',
      data: {
        _method: 'DELETE',
        _token: '{{ csrf_token() }}'
      },
      success: function (response) {
        toastr.success(response.message || 'Deleted');
        table.ajax.reload(null, false);
      },
      error: function (xhr) {
        toastr.error(xhr.responseJSON?.message || 'Unable to delete entry');
      }
    });
  });
});
</script>
@endpush
