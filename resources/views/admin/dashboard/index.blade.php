@extends('admin.layouts.master')

@section('page-title', 'Dashboard')

@section('page-actions')
  <div class="btn-list">
    <a href="#" class="btn btn-primary d-none d-sm-inline-block">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
      Create new
    </a>
  </div>
@endsection

@section('content')
  {{-- Stats Cards --}}
  <div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="subheader">Total Users</div>
          </div>
          <div class="h1 mb-3">2,456</div>
          <div class="d-flex mb-2">
            <div>Active users</div>
            <div class="ms-auto">
              <span class="text-green d-inline-flex align-items-center lh-1">
                7%
                <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="subheader">Revenue</div>
          </div>
          <div class="h1 mb-3">$12,456</div>
          <div class="d-flex mb-2">
            <div>This month</div>
            <div class="ms-auto">
              <span class="text-green d-inline-flex align-items-center lh-1">
                12%
                <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="subheader">Orders</div>
          </div>
          <div class="h1 mb-3">456</div>
          <div class="d-flex mb-2">
            <div>Pending orders</div>
            <div class="ms-auto">
              <span class="text-red d-inline-flex align-items-center lh-1">
                -3%
                <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7l6 6l4 -4l8 8" /><path d="M21 10l0 7l-7 0" /></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <div class="subheader">Conversion Rate</div>
          </div>
          <div class="h1 mb-3">3.2%</div>
          <div class="d-flex mb-2">
            <div>From last month</div>
            <div class="ms-auto">
              <span class="text-green d-inline-flex align-items-center lh-1">
                0.5%
                <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Activity --}}
  <div class="row row-cards mt-3">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Recent Activity</h3>
        </div>
        <div class="card-body">
          <div class="divide-y">
            <div>
              <div class="row">
                <div class="col-auto">
                  <span class="avatar">JL</span>
                </div>
                <div class="col">
                  <div class="text-truncate">
                    <strong>John Doe</strong> created a new account
                  </div>
                  <div class="text-secondary">2 minutes ago</div>
                </div>
              </div>
            </div>
            <div>
              <div class="row">
                <div class="col-auto">
                  <span class="avatar" style="background-image: url({{ asset('assets/backend/img/default-avatar.png') }})"></span>
                </div>
                <div class="col">
                  <div class="text-truncate">
                    <strong>Jane Smith</strong> updated profile settings
                  </div>
                  <div class="text-secondary">15 minutes ago</div>
                </div>
              </div>
            </div>
            <div>
              <div class="row">
                <div class="col-auto">
                  <span class="avatar">AB</span>
                </div>
                <div class="col">
                  <div class="text-truncate">
                    <strong>Admin</strong> changed system settings
                  </div>
                  <div class="text-secondary">1 hour ago</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Page-specific JavaScript can go here
  console.log('Dashboard loaded successfully!');
</script>
@endpush
