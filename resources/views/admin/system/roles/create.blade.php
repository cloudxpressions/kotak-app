@extends('admin.layouts.master')

@section('page-title', 'Create Role')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.roles.index') }}">Roles & Permissions</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create Role</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <a href="{{ route('admin.system.roles.index') }}" class="btn">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
    </svg>
    Back to Roles
  </a>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-md-10">
    <form action="{{ route('admin.system.roles.store') }}" method="POST" novalidate>
      @csrf
      
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Role Information</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Role Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" placeholder="Enter role name" required autofocus>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">A unique name for this role (e.g., Content Manager, Editor)</small>
          </div>
        </div>
      </div>

      <div class="card mt-3">
        <div class="card-header">
          <h3 class="card-title">Assign Permissions</h3>
          <p class="card-subtitle">Select permissions for this role</p>
        </div>
        <div class="card-body">
          <div class="row" id="permissions-container">
            @foreach($permissionGroups as $groupName => $permissions)
              <div class="col-md-6 mb-3">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">{{ $groupName }}</h4>
                    <div class="card-actions">
                      <label class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input select-all-group">
                        <span class="form-check-label">Select All</span>
                      </label>
                    </div>
                  </div>
                  <div class="card-body">
                    @foreach($permissions as $permission)
                      <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="permissions[]" 
                               value="{{ $permission->name }}" 
                               {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $permission->name }}</span>
                      </label>
                    @endforeach
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        <div class="card-footer text-end">
          <a href="{{ route('admin.system.roles.index') }}" class="btn">Cancel</a>
          @can('role.create')
          <button type="submit" class="btn btn-primary ms-2">Create Role</button>
          @endcan
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all group
    $('.select-all-group').on('change', function() {
        $(this).closest('.card').find('input[name="permissions[]"]').prop('checked', this.checked);
    });
});
</script>
@endpush
