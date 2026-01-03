@extends('admin.layouts.master')

@section('page-title', 'Blocked IP Addresses')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Blocked IPs</li>
</ol>
@endsection

@section('page-actions')
@can('blocked-ip.create')
<a href="{{ route('admin.system.blocked-ips.create') }}" class="btn btn-primary">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12h14" /></svg>
  Block IP
</a>
@endcan
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Blocked IPs</h3>
      <p class="card-subtitle">Monitor and manage blocked addresses.</p>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap datatable" id="blocked-ips-table">
      <thead>
        <tr>
          <th>IP Address</th>
          <th>Status</th>
          <th>Reason</th>
          <th>Blocked Until</th>
          <th>Attempts</th>
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
  const table = $('#blocked-ips-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.system.blocked-ips.index') }}',
    columns: [
      { data: 'ip_address', name: 'ip_address' },
      { data: 'status_badge', orderable: false, searchable: false },
      { data: 'reason', name: 'reason', defaultContent: '--' },
      { data: 'blocked_until', name: 'blocked_until', defaultContent: '--' },
      { data: 'attempts_count', name: 'attempts_count' },
      { data: 'action', orderable: false, searchable: false }
    ],
    order: [[0, 'asc']],
    drawCallback: function () {
      $('[data-bs-toggle="tooltip"]').tooltip();
    }
  });

  $(document).on('click', '.delete-blocked-ip', function () {
    const url = $(this).data('url');
    if (!url) return;

    if (!confirm('Delete this blocked IP?')) {
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
