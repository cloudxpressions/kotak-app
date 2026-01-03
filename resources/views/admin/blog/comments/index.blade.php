@extends('admin.layouts.master')

@section('page-title', 'Blog Comments')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Blog Comments</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Blog Comments</h3>
  </div>
  <div class="card-body border-bottom py-3">
    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable" id="comments-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Post</th>
            <th>Author</th>
            <th>Content</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    var table = $('#comments-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.blog.comments.index') }}",
      columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'post_title', name: 'post.translations.title'},
        {data: 'author', name: 'author', orderable: false, searchable: false},
        {data: 'content', name: 'content', render: function(data) {
            return data.length > 50 ? data.substr(0, 50) + '...' : data;
        }},
        {data: 'status', name: 'is_approved'},
        {data: 'created_at', name: 'created_at'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
      ]
    });

    // Handle Status Change
    $(document).on('click', '.status-btn', function() {
      var id = $(this).data('id');
      var status = $(this).data('status');
      var url = "{{ route('admin.blog.comments.status', ':id') }}".replace(':id', id);

      $.ajax({
        url: url,
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          status: status
        },
        success: function(response) {
          if (response.success) {
            toastr.success(response.message);
            table.ajax.reload();
          } else {
            toastr.error('Failed to update status.');
          }
        },
        error: function() {
          toastr.error('An error occurred.');
        }
      });
    });

    // Handle Delete
    $(document).on('click', '.delete-btn', function() {
      var id = $(this).data('id');
      var url = "{{ route('admin.blog.comments.destroy', ':id') }}".replace(':id', id);

      if (confirm('Are you sure you want to delete this comment?')) {
        $.ajax({
          url: url,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message);
              table.ajax.reload();
            } else {
              toastr.error('Failed to delete comment.');
            }
          },
          error: function() {
            toastr.error('An error occurred.');
          }
        });
      }
    });
  });
</script>
@endpush
