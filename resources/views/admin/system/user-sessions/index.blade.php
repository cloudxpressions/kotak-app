@extends('admin.layouts.master')

@section('page-title', 'User Sessions')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">User Sessions</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">User Sessions</h3>
      <p class="card-subtitle">Monitor active and historical login sessions.</p>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap datatable" id="user-sessions-table">
      <thead>
        <tr>
          <th>Actor</th>
          <th>IP</th>
          <th>Device</th>
          <th>Login</th>
          <th>Last Seen</th>
          <th>Status</th>
          <th class="w-1">Action</th>
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
  const table = $('#user-sessions-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin.system.user-sessions.index') }}',
    columns: [
      { data: 'user_info', orderable: false, searchable: false },
      { data: 'ip_address', name: 'ip_address', defaultContent: '--' },
      { data: 'device', name: 'device', defaultContent: '--',
        render: function (data, type, row) {
          let parts = [];
          if (row.device) parts.push(row.device);
          if (row.browser) parts.push(row.browser);
          if (row.platform) parts.push(row.platform);
          return parts.length ? parts.join(' / ') : '--';
        }
      },
      { data: 'login_at', name: 'login_at', defaultContent: '--' },
      { data: 'last_seen_at', name: 'last_seen_at', defaultContent: '--' },
      { data: 'status_badge', orderable: false, searchable: false },
      { data: 'action', orderable: false, searchable: false }
    ],
    order: [[3, 'desc']]
  });

  $(document).on('click', '.revoke-session', function () {
    const url = $(this).data('url');
    if (!url) return;

    if (!confirm('Force logout this session?')) {
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
        toastr.success(response.message || 'Session revoked');
        table.ajax.reload(null, false);
      },
      error: function (xhr) {
        toastr.error(xhr.responseJSON?.message || 'Unable to revoke session');
      }
    });
  });
});
</script>
@endpush
