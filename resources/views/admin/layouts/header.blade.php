{{-- BEGIN NAVBAR --}}
<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
  <div class="container-xl">
    {{-- Unified Toggle Button (Mobile Menu + Desktop Sidebar) --}}
    <button class="navbar-toggler" type="button" id="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Search Field (Left Side) --}}
    <div class="navbar-nav flex-fill">
      <form action="./" method="get" autocomplete="off" novalidate>
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
              <path d="M21 21l-6 -6" />
            </svg>
          </span>
          <input type="text" value="" class="form-control" placeholder="Search…" aria-label="Search in website">
        </div>
      </form>
    </div>

    {{-- Right Side Actions --}}
    <div class="navbar-nav flex-row order-md-last">
      <div class="d-none d-md-flex">
        {{-- Language Dropdown --}}
        <div class="nav-item dropdown me-3">
          <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Select language">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M4 5h7" />
              <path d="M9 3v2c0 4.418 -2.239 8 -5 8" />
              <path d="M5 9c0 2.144 2.952 3.908 6.7 4" />
              <path d="M12 20l4 -9l4 9" />
              <path d="M19.1 18h-6.2" />
            </svg>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            @foreach($languages as $language)
              <a href="#" class="dropdown-item language-switch {{ session('language', 'en') == $language->code ? 'active' : '' }}" data-lang="{{ $language->code }}">
                <span class="flag flag-country-{{ $language->code == 'en' ? 'us' : ($language->code == 'ta' ? 'in' : 'us') }} me-2"></span>
                {{ $language->native_name ?? $language->name }}
              </a>
            @endforeach
          </div>
        </div>

        {{-- Theme Toggle --}}
        <div class="nav-item me-3">
          <a href="#" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-theme-toggle="dark">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
              <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
            </svg>
          </a>
          <a href="#" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-theme-toggle="light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
              <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
              <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
            </svg>
          </a>
        </div>

        {{-- Notifications --}}
        <div class="nav-item dropdown me-3">
          <a href="#" class="nav-link px-0 position-relative" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications" id="notifications-dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
              <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
              <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
            </svg>
            <span class="badge bg-red text-red-fg badge-notification badge-pill position-absolute top-0 start-100 translate-middle" id="notifications-badge">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Notifications</h3>
                <div class="card-actions">
                  <a href="#" id="mark-all-read" class="btn btn-ghost-secondary btn-sm">
                    Mark all as read
                  </a>
                </div>
              </div>
              <div class="list-group list-group-flush list-group-hoverable" id="notifications-list">
                <div class="list-group-item text-center py-4">
                  <div class="text-secondary">No notifications</div>
                </div>
              </div>
              <div class="card-footer">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-ghost-secondary w-100">View all</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- User Dropdown --}}
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url({{ asset('assets/backend/img/default-avatar.png') }})"></span>
          <div class="d-none d-xl-block ps-2">
            <div>{{ auth('admin')->user()->name ?? 'Admin User' }}</div>
            <div class="mt-1 small text-secondary">{{ auth('admin')->user()->email ?? 'admin@example.com' }}</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="{{ route('admin.profile.index') }}" class="dropdown-item">Profile Settings</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
{{-- END NAVBAR --}}
