@extends('admin.layouts.master')

@section('page-title', 'Account Deletion Requests')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Account Deletions</li>
</ol>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pending Deletion Requests</h3>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="requests-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Reason</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#requests-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.system.account-deletions.index') }}",
            columns: [
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'delete_request_reason', name: 'delete_request_reason' },
                { data: 'delete_request_at', name: 'delete_request_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
