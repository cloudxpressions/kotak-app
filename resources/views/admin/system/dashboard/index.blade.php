@extends('admin.layouts.master')

@section('page-title', 'Dashboard')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  </li>
  <li class="breadcrumb-item active" aria-current="page">System Overview</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
         fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M10 5a2 2 0 1 1 4 0" />
      <path d="M5 10a7 7 0 0 1 14 0" />
      <path d="M5 10v4l-1 2h16l-1 -2v-4" />
      <path d="M10 19a2 2 0 0 0 4 0" />
    </svg>
    Notifications
  </a>

  <a href="{{ route('admin.system.activity-log.index') }}" class="btn btn-outline-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
         fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M4 19l4 -10l4 6l4 -14l4 18" />
      <path d="M3 19h18" />
    </svg>
    Activity Logs
  </a>
</div>
@endsection

@section('content')
<div class="row row-cards">

  {{-- Hero / Welcome --}}
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row gy-3 align-items-center">
          <div class="col-auto">
            <span class="avatar avatar-lg bg-primary text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" width="24" height="24"
                   viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                   fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 4h6v6h-6z" />
                <path d="M14 4h6v6h-6z" />
                <path d="M4 14h6v6h-6z" />
                <path d="M14 14h6v6h-6z" />
              </svg>
            </span>
          </div>
          <div class="col">
            <h2 class="mb-1">
              Welcome back,
              {{ optional(auth('admin')->user())->name ?? 'Admin' }}
            </h2>
            <p class="text-secondary mb-0">
              Here’s a quick snapshot of your users, geography, content and system activity.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- KPI cards row 1 --}}
  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.users.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-primary-lt text-primary me-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
              <path d="M3 21v-2a4 4 0 0 1 4 -4h4" />
              <path d="M16 11l2 2l4 -4" />
            </svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Total Users</div>
            <div class="h2 mb-0">{{ number_format($stats['total_users']) }}</div>
            <div class="text-secondary small">Manage users</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.admins.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-green-lt text-green me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-shield"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 21v-2a4 4 0 0 1 4 -4h2" /><path d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z" /><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /></svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Admins</div>
            <div class="h2 mb-0">{{ number_format($stats['total_admins']) }}</div>
            <div class="text-secondary small">Manage admin accounts</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.system.countries.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-azure-lt text-azure me-3">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-discount"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 19l-4 -2l-6 3v-13l6 -3l6 3l6 -3v8.5" /><path d="M9 4v13" /><path d="M15 7v5.5" /><path d="M16 21l5 -5" /><path d="M21 21v.01" /><path d="M16 16v.01" /></svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Countries</div>
            <div class="h2 mb-0">{{ number_format($stats['total_countries']) }}</div>
            <div class="text-secondary small">Country master</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.system.states.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-orange-lt text-orange me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-estate"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h18" /><path d="M19 21v-4" /><path d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" /><path d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" /><path d="M9 17v4" /><path d="M8 13h2" /><path d="M8 9h2" /></svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">States</div>
            <div class="h2 mb-0">{{ number_format($stats['total_states']) }}</div>
            <div class="text-secondary small">State master</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  {{-- KPI cards row 2 --}}
  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.system.cities.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-purple-lt text-purple me-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M3 21l18 0" />
              <path d="M5 21v-10l7 -4l7 4v10" />
              <path d="M9 17h6" />
            </svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Cities</div>
            <div class="h2 mb-0">{{ number_format($stats['total_cities']) }}</div>
            <div class="text-secondary small">City master</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.blog.posts.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-cyan-lt text-cyan me-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M3 4a2 2 0 0 1 2 -2h9l7 7v11a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
              <path d="M12 4v4a2 2 0 0 0 2 2h4" />
            </svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Blog Posts</div>
            <div class="h2 mb-0">{{ number_format($stats['total_blog_posts']) }}</div>
            <div class="text-secondary small">Manage posts</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.blog.categories.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-teal-lt text-teal me-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                 fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M3 6h18" />
              <path d="M3 12h18" />
              <path d="M3 18h18" />
            </svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Blog Categories</div>
            <div class="h2 mb-0">{{ number_format($stats['total_blog_categories']) }}</div>
            <div class="text-secondary small">Manage categories</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-lg-3">
    <a href="{{ route('admin.blog.tags.index') }}" class="card card-sm card-link">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <span class="avatar bg-red-lt text-red me-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tag"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" /></svg>
          </span>
          <div class="flex-fill">
            <div class="text-secondary text-uppercase fw-bold lh-1 mb-1 small">Blog Tags</div>
            <div class="h2 mb-0">{{ number_format($stats['total_blog_tags']) }}</div>
            <div class="text-secondary small">Manage tags</div>
          </div>
        </div>
      </div>
    </a>
  </div>

  {{-- Activity + Users row --}}
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Recent Activity</h3>
        <a href="{{ route('admin.system.activity-log.index') }}" class="ms-auto small text-secondary">
          View all
        </a>
      </div>
      <div class="card-body card-body-scrollable card-body-scrollable-shadow" style="max-height: 22rem">
        @forelse($stats['recent_activities'] as $activity)
          <div class="mb-3 pb-3 border-bottom">
            <div class="row">
              <div class="col-auto">
                @php
                  $name = optional($activity->causer)->name ?? 'System';
                  $initial = mb_substr($name, 0, 1);
                @endphp
                <span class="avatar avatar-sm bg-primary-lt text-primary">
                  {{ $initial }}
                </span>
              </div>
              <div class="col">
                <div class="text-truncate">
                  <strong>{{ $name }}</strong>
                  <span class="text-secondary">
                    {{ $activity->description }}
                  </span>
                </div>
                <div class="text-secondary small">
                  {{ $activity->created_at->format('d M Y, H:i') }}
                  · {{ $activity->created_at->diffForHumans() }}
                </div>
              </div>
            </div>
          </div>
        @empty
          <p class="text-secondary mb-0">No activity logged yet.</p>
        @endforelse
      </div>
      <div class="card-footer">
        <span class="text-secondary small">
          Today: <strong>{{ number_format($stats['today_activities']) }}</strong> events
        </span>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    {{-- Recent users --}}
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">New Users</h3>
        <a href="{{ route('admin.users.index') }}" class="ms-auto small text-secondary">
          View all users
        </a>
      </div>
      <div class="card-body">
        @forelse($stats['recent_users'] as $user)
          <div class="row align-items-center mb-3">
            <div class="col-auto">
              @php
                $name = $user->name ?? $user->email;
                $initial = mb_substr($name, 0, 1);
              @endphp
              <span class="avatar avatar-sm bg-azure-lt text-azure">
                {{ $initial }}
              </span>
            </div>
            <div class="col">
              <div class="text-truncate">
                <strong>{{ $name }}</strong>
              </div>
              <div class="text-secondary small">
                {{ $user->email }} · Joined {{ $user->created_at->diffForHumans() }}
              </div>
            </div>
          </div>
        @empty
          <p class="text-secondary mb-0">No users yet.</p>
        @endforelse
      </div>
    </div>

    {{-- System snapshot / quick links --}}
    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title">System Snapshot</h3>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-secondary small text-uppercase fw-bold">Languages</div>
              <div class="h3 mb-0">{{ number_format($stats['total_languages']) }}</div>
            </div>
            <a href="{{ route('admin.system.languages.index') }}" class="btn btn-sm btn-outline-secondary">
              Manage
            </a>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-secondary small text-uppercase fw-bold">Currencies</div>
              <div class="h3 mb-0">{{ number_format($stats['total_currencies']) }}</div>
            </div>
            <a href="{{ route('admin.system.currencies.index') }}" class="btn btn-sm btn-outline-secondary">
              Manage
            </a>
          </div>
        </div>

        <div class="row g-2 mt-2">
          <div class="col-6">
            <a href="{{ route('admin.system.database-backups.index') }}" class="btn w-100 btn-outline-primary btn-sm">
              DB Backups
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.system.email-settings.index') }}" class="btn w-100 btn-outline-primary btn-sm">
              Email Settings
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.system.newsletter-subscribers.index') }}" class="btn w-100 btn-outline-primary btn-sm mt-2">
              Subscribers
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.system.newsletters.index') }}" class="btn w-100 btn-outline-primary btn-sm mt-2">
              Newsletters
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.system.user-sessions.index') }}" class="btn w-100 btn-outline-primary btn-sm mt-2">
              User Sessions
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.system.blocked-ips.index') }}" class="btn w-100 btn-outline-primary btn-sm mt-2">
              Blocked IPs
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection
