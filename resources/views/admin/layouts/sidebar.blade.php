{{-- BEGIN SIDEBAR --}}
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
  <div class="container-fluid">
    {{-- BEGIN NAVBAR TOGGLER --}}
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#sidebar-menu"
      aria-controls="sidebar-menu"
      aria-expanded="false"
      aria-label="Toggle sidebar navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    {{-- END NAVBAR TOGGLER --}}

    {{-- BEGIN NAVBAR LOGO --}}
    <div class="navbar-brand navbar-brand-autodark">
      <a href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="110" height="32" viewBox="0 0 232 68" class="navbar-brand-image">
          <path
            d="M64.6 16.2C63 9.9 58.1 5 51.8 3.4 40 1.5 28 1.5 16.2 3.4 9.9 5 5 9.9 3.4 16.2 1.5 28 1.5 40 3.4 51.8 5 58.1 9.9 63 16.2 64.6c11.8 1.9 23.8 1.9 35.6 0C58.1 63 63 58.1 64.6 51.8c1.9-11.8 1.9-23.8 0-35.6zM33.3 36.3c-2.8 4.4-6.6 8.2-11.1 11-1.5.9-3.3.9-4.8.1s-2.4-2.3-2.5-4c0-1.7.9-3.3 2.4-4.1 2.3-1.4 4.4-3.2 6.1-5.3-1.8-2.1-3.8-3.8-6.1-5.3-2.3-1.3-3-4.2-1.7-6.4s4.3-2.9 6.5-1.6c4.5 2.8 8.2 6.5 11.1 10.9 1 1.4 1 3.3.1 4.7zM49.2 46H37.8c-2.1 0-3.8-1-3.8-3s1.7-3 3.8-3h11.4c2.1 0 3.8 1 3.8 3s-1.7 3-3.8 3z"
            fill="#066fd1"
            style="fill: var(--tblr-primary, #066fd1)"
          />
          <path
            d="M105.8 46.1c.4 0 .9.2 1.2.6s.6 1 .6 1.7c0 .9-.5 1.6-1.4 2.2s-2 .9-3.2.9c-2 0-3.7-.4-5-1.3s-2-2.6-2-5.4V31.6h-2.2c-.8 0-1.4-.3-1.9-.8s-.9-1.1-.9-1.9c0-.7.3-1.4.8-1.8s1.2-.7 1.9-.7h2.2v-3.1c0-.8.3-1.5.8-2.1s1.3-.8 2.1-.8 1.5.3 2 .8.8 1.3.8 2.1v3.1h3.4c.8 0 1.4.3 1.9.8s.8 1.2.8 1.9-.3 1.4-.8 1.8-1.2.7-1.9.7h-3.4v13c0 .7.2 1.2.5 1.5s.8.5 1.4.5c.3 0 .6-.1 1.1-.2.5-.2.8-.3 1.2-.3zm28-20.7c.8 0 1.5.3 2.1.8.5.5.8 1.2.8 2.1v20.3c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2-.8-.8-1.2-.8-2.1c-.8.9-1.9 1.7-3.2 2.4-1.3.7-2.8 1-4.3 1-2.2 0-4.2-.6-6-1.7-1.8-1.1-3.2-2.7-4.2-4.7s-1.6-4.3-1.6-6.9c0-2.6.5-4.9 1.5-6.9s2.4-3.6 4.2-4.8c1.8-1.1 3.7-1.7 5.9-1.7 1.5 0 3 .3 4.3.8 1.3.6 2.5 1.3 3.4 2.1 0-.8.3-1.5.8-2.1.5-.5 1.2-.7 2-.7zm-9.7 21.3c2.1 0 3.8-.8 5.1-2.3s2-3.4 2-5.7-.7-4.2-2-5.8c-1.3-1.5-3-2.3-5.1-2.3-2 0-3.7.8-5 2.3-1.3 1.5-2 3.5-2 5.8s.6 4.2 1.9 5.7 3 2.3 5.1 2.3zm32.1-21.3c2.2 0 4.2.6 6 1.7 1.8 1.1 3.2 2.7 4.2 4.7s1.6 4.3 1.6 6.9-.5 4.9-1.5 6.9-2.4 3.6-4.2 4.8c-1.8 1.1-3.7 1.7-5.9 1.7-1.5 0-3-.3-4.3-.9s-2.5-1.4-3.4-2.3v.3c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2.1-.8c-.5-.5-.8-1.2-.8-2.1V18.9c0-.8.3-1.5.8-2.1.5-.6 1.2-.8 2.1-.8s1.5.3 2.1.8c.5.6.8 1.3.8 2.1v10c.8-1 1.8-1.8 3.2-2.5 1.3-.7 2.8-1 4.3-1zm-.7 21.3c2 0 3.7-.8 5-2.3s2-3.5 2-5.8-.6-4.2-1.9-5.7-3-2.3-5.1-2.3-3.8.8-5.1 2.3-2 3.4-2 5.7.7 4.2 2 5.8c1.3 1.6 3 2.3 5.1 2.3zm23.6 1.9c0 .8-.3 1.5-.8 2.1s-1.3.8-2.1.8-1.5-.3-2-.8-.8-1.3-.8-2.1V18.9c0-.8.3-1.5.8-2.1s1.3-.8 2.1-.8 1.5.3 2 .8.8 1.3.8 2.1v29.7zm29.3-10.5c0 .8-.3 1.4-.9 1.9-.6.5-1.2.7-2 .7h-15.8c.4 1.9 1.3 3.4 2.6 4.4 1.4 1.1 2.9 1.6 4.7 1.6 1.3 0 2.3-.1 3.1-.4.7-.2 1.3-.5 1.8-.8.4-.3.7-.5.9-.6.6-.3 1.1-.4 1.6-.4.7 0 1.2.2 1.7.7s.7 1 .7 1.7c0 .9-.4 1.6-1.3 2.4-.9.7-2.1 1.4-3.6 1.9s-3 .8-4.6.8c-2.7 0-5-.6-7-1.7s-3.5-2.7-4.6-4.6-1.6-4.2-1.6-6.6c0-2.8.6-5.2 1.7-7.2s2.7-3.7 4.6-4.8 3.9-1.7 6-1.7 4.1.6 6 1.7 3.4 2.7 4.5 4.7c.9 1.9 1.5 4.1 1.5 6.3zm-12.2-7.5c-3.7 0-5.9 1.7-6.6 5.2h12.6v-.3c-.1-1.3-.8-2.5-2-3.5s-2.5-1.4-4-1.4zm30.3-5.2c1 0 1.8.3 2.4.8.7.5 1 1.2 1 1.9 0 1-.3 1.7-.8 2.2-.5.5-1.1.8-1.8.7-.5 0-1-.1-1.6-.3-.2-.1-.4-.1-.6-.2-.4-.1-.7-.1-1.1-.1-.8 0-1.6.3-2.4.8s-1.4 1.3-1.9 2.3-.7 2.3-.7 3.7v11.4c0 .8-.3 1.5-.8 2.1-.5.6-1.2.8-2.1.8s-1.5-.3-2.1-.8c-.5-.6-.8-1.3-.8-2.1V28.8c0-.8.3-1.5.8-2.1.5-.6 1.2-.8 2.1-.8s1.5.3 2.1.8c.5.6.8 1.3.8 2.1v.6c.7-1.3 1.8-2.3 3.2-3 1.3-.7 2.8-1 4.3-1z"
            fill-rule="evenodd"
            clip-rule="evenodd"
            fill="#4a4a4a"
          />
        </svg>
      </a>
    </div>
    {{-- END NAVBAR LOGO --}}

    {{-- BEGIN MOBILE USER DROPDOWN --}}
    <div class="navbar-nav flex-row d-lg-none">
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url({{ asset('assets/backend/img/default-avatar.png') }})"></span>
          <div class="d-none d-xl-block ps-2">
            <div>{{ auth('admin')->user()->name ?? 'Admin' }}</div>
            <div class="mt-1 small text-secondary">{{ auth('admin')->user()->email ?? 'admin@example.com' }}</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <a href="#" class="dropdown-item">Profile</a>
          <a href="#" class="dropdown-item">Settings</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">Logout</button>
          </form>
        </div>
      </div>
    </div>
    {{-- END MOBILE USER DROPDOWN --}}

    {{-- BEGIN SIDEBAR MENU --}}
    <div class="collapse navbar-collapse" id="sidebar-menu">
      <ul class="navbar-nav pt-lg-3">
        {{-- Dashboard --}}
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📊</span>
            <span class="nav-link-title">Dashboard</span>
          </a>
        </li>

        {{-- My Profile --}}
        <li class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.profile.index') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">👤</span>
            <span class="nav-link-title">My Profile</span>
          </a>
        </li>

                {{-- User Management --}}
        <li class="nav-item dropdown {{ request()->is('admin/users*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-users" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/users*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
            </span>
            <span class="nav-link-title">User Management</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/users*') ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
              👤 User List
            </a>
            <a class="dropdown-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}" href="{{ route('admin.users.create') }}">
              ➕ Create User
            </a>
            <a class="dropdown-item {{ request()->routeIs('admin.users.deletion-requests') ? 'active' : '' }}" href="{{ route('admin.users.deletion-requests') }}">
              🗑️ Deletion Requests
            </a>
          </div>
        </li>

        {{-- Admin Management --}}
        <li class="nav-item dropdown {{ request()->is('admin/admins*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-admins" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/admins*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>
            </span>
            <span class="nav-link-title">Admin Management</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/admins*') ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.admins.index') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">
              🛡️ Admin List
            </a>
            <a class="dropdown-item {{ request()->routeIs('admin.admins.create') ? 'active' : '' }}" href="{{ route('admin.admins.create') }}">
              ➕ Create Admin
            </a>
            <a class="dropdown-item {{ request()->routeIs('admin.admins.deletion-requests') ? 'active' : '' }}" href="{{ route('admin.admins.deletion-requests') }}">
              🗑️ Deletion Requests
            </a>
          </div>
        </li>


        {{-- LMS Bank --}}
        {{-- <li class="nav-item dropdown {{ request()->is('admin/lms*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-lms-bank" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/lms*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">🎓</span>
            <span class="nav-link-title">LMS Bank</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/lms*') ? 'show' : '' }}">
            <a class="dropdown-item" href="#">📘 Subjects</a>
            <a class="dropdown-item" href="#">📑 Topics</a>
            <a class="dropdown-item" href="#">📝 Headings</a>
            <a class="dropdown-item" href="#">📄 Content</a>
            <a class="dropdown-item" href="#">❓ Questions</a>
          </div>
        </li> --}}

        {{-- Tests --}}
        {{-- <li class="nav-item dropdown {{ request()->is('admin/tests*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-tests" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/tests*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📋</span>
            <span class="nav-link-title">Tests</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/tests*') ? 'show' : '' }}">
            <a class="dropdown-item" href="#">🗂️ Test Series</a>
            <a class="dropdown-item" href="#">📌 Test Instructions</a>
          </div>
        </li> --}}

        {{-- Daily Activity --}}
        {{-- <li class="nav-item dropdown {{ request()->is('admin/daily*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-daily-activity" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/daily*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📆</span>
            <span class="nav-link-title">Daily Activity</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/daily*') ? 'show' : '' }}">
            <a class="dropdown-item" href="#">📖 Thirukkural Daily</a>
            <a class="dropdown-item" href="#">🔤 Word Daily</a>
            <a class="dropdown-item" href="#">🌐 Translation Daily</a>
            <a class="dropdown-item" href="#">💡 Tip of Day Daily</a>
            <a class="dropdown-item" href="#">🗨️ Quote of Day</a>
          </div>
        </li> --}}

        {{-- Exams --}}
        {{-- <li class="nav-item dropdown {{ request()->is('admin/exams*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-exams" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/exams*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">🗂️</span>
            <span class="nav-link-title">Exams</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/exams*') ? 'show' : '' }}">
            <a class="dropdown-item" href="#">🗃️ Exam Category</a>
            <a class="dropdown-item" href="#">📁 Exams and Details</a>
          </div>
        </li> --}}

        {{-- Create Course --}}
        {{-- <li class="nav-item {{ request()->is('admin/course*') ? 'active' : '' }}">
          <a class="nav-link" href="#">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📘</span>
            <span class="nav-link-title">Create Course</span>
          </a>
        </li> --}}

        {{-- Insurance --}}
        @php
            $isInsuranceActive = request()->routeIs(
                'admin.insurance.categories*',
                'admin.insurance.exams*',
                'admin.insurance.chapters*',
                'admin.insurance.concepts*',
                'admin.insurance.one_liners*',
                'admin.insurance.short_simples*',
                'admin.insurance.terminologies*',
                'admin.insurance.materials*',
                'admin.insurance.tests*',
                'admin.insurance.questions*',
                'admin.insurance.test_attempts*',
                'admin.insurance.performance_stats*',
                'admin.insurance.user_saved_items*',
                'admin.insurance.ad_events*'
            );
        @endphp
        <li class="nav-item dropdown {{ $isInsuranceActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-insurance" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isInsuranceActive ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">🛡️</span>
            <span class="nav-link-title">Insurance</span>
          </a>
          <div class="dropdown-menu {{ $isInsuranceActive ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.categories*') ? 'active' : '' }}" href="{{ route('admin.insurance.categories.index') }}">🏷️ Insurance Categories</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.exams*') ? 'active' : '' }}" href="{{ route('admin.insurance.exams.index') }}">📋 Exams</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.chapters*') ? 'active' : '' }}" href="{{ route('admin.insurance.chapters.index') }}">📚 Chapters</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.concepts*') ? 'active' : '' }}" href="{{ route('admin.insurance.concepts.index') }}">📖 Concepts</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.one_liners*') ? 'active' : '' }}" href="{{ route('admin.insurance.one_liners.index') }}">💬 One Liners</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.short_simples*') ? 'active' : '' }}" href="{{ route('admin.insurance.short_simples.index') }}">📝 Short & Simple</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.terminologies*') ? 'active' : '' }}" href="{{ route('admin.insurance.terminologies.index') }}">🔤 Terminologies</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.materials*') ? 'active' : '' }}" href="{{ route('admin.insurance.materials.index') }}">📁 Study Materials</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.tests*') ? 'active' : '' }}" href="{{ route('admin.insurance.tests.index') }}">📝 Tests</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.questions*') ? 'active' : '' }}" href="{{ route('admin.insurance.questions.index') }}">❓ Questions</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.test_attempts*') ? 'active' : '' }}" href="{{ route('admin.insurance.test_attempts.index') }}">📊 Test Attempts</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.performance_stats*') ? 'active' : '' }}" href="{{ route('admin.insurance.performance_stats.index') }}">📈 Performance Stats</a>
            <a class="dropdown-item {{ request()->routeIs('admin.insurance.user_saved_items*') ? 'active' : '' }}" href="{{ route('admin.insurance.user_saved_items.index') }}">🔖 User Saved Items</a>
          </div>
        </li>

        {{-- Blog --}}
        <li class="nav-item dropdown {{ request()->is('admin/blog*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-blog" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/blog*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📄</span>
            <span class="nav-link-title">Blog</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/blog*') ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.blog.posts*') ? 'active' : '' }}" href="{{ route('admin.blog.posts.index') }}">📝 Posts</a>
            <a class="dropdown-item {{ request()->routeIs('admin.blog.categories*') ? 'active' : '' }}" href="{{ route('admin.blog.categories.index') }}">🗂️ Categories</a>
            <a class="dropdown-item {{ request()->routeIs('admin.blog.tags*') ? 'active' : '' }}" href="{{ route('admin.blog.tags.index') }}">🏷️ Tags</a>
            <a class="dropdown-item {{ request()->routeIs('admin.blog.comments*') ? 'active' : '' }}" href="{{ route('admin.blog.comments.index') }}">💬 Comments</a>
          </div>
        </li>

        {{-- Events Management --}}
        {{-- <li class="nav-item dropdown {{ request()->is('admin/events*') ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-events" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/events*') ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">📅</span>
            <span class="nav-link-title">Events Management</span>
          </a>
          <div class="dropdown-menu {{ request()->is('admin/events*') ? 'show' : '' }}">
            <a class="dropdown-item" href="#">📅 Events</a>
            <a class="dropdown-item" href="#">🗓️ Calendar Sources</a>
            <a class="dropdown-item" href="#">📆 View Calendar</a>
          </div>
        </li> --}}






        {{-- Master Data --}}
        @php
            $isMasterDataActive = request()->routeIs(
                'admin.system.countries*',
                'admin.system.states*',
                'admin.system.cities*',
                'admin.system.currencies*',
                'admin.system.date-formats*',
                'admin.system.communities*',
                'admin.system.da-categories*',
                'admin.system.user-classifications*',
                'admin.system.time-zones*',
                'admin.system.religions*',
                'admin.system.special-categories*'
            );
        @endphp
        <li class="nav-item dropdown {{ $isMasterDataActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-master" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isMasterDataActive ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">🗄️</span>
            <span class="nav-link-title">Master Data</span>
          </a>
          <div class="dropdown-menu {{ $isMasterDataActive ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.system.countries*') ? 'active' : '' }}" href="{{ route('admin.system.countries.index') }}">🌎 Country</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.states*') ? 'active' : '' }}" href="{{ route('admin.system.states.index') }}">🗺️ State</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.cities*') ? 'active' : '' }}" href="{{ route('admin.system.cities.index') }}">🏙️ City</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.time-zones*') ? 'active' : '' }}" href="{{ route('admin.system.time-zones.index') }}">⏰ TimeZone</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.currencies*') ? 'active' : '' }}" href="{{ route('admin.system.currencies.index') }}">💱 Currency</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.date-formats*') ? 'active' : '' }}" href="{{ route('admin.system.date-formats.index') }}">📅 Date Format</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.user-classifications*') ? 'active' : '' }}" href="{{ route('admin.system.user-classifications.index') }}">👥 User Classification</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.da-categories*') ? 'active' : '' }}" href="{{ route('admin.system.da-categories.index') }}">♿ DA Category</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.communities*') ? 'active' : '' }}" href="{{ route('admin.system.communities.index') }}">👥 Community</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.religions*') ? 'active' : '' }}" href="{{ route('admin.system.religions.index') }}">🧭 Religion</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.special-categories*') ? 'active' : '' }}" href="{{ route('admin.system.special-categories.index') }}">⭐ Special Category</a>
          </div>
        </li>

        {{-- Language --}}
        @php
            $isLanguageActive = request()->routeIs('admin.system.languages*', 'admin.system.translations*');
        @endphp
        <li class="nav-item dropdown {{ $isLanguageActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-language" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isLanguageActive ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">🌍</span>
            <span class="nav-link-title">Language</span>
          </a>
          <div class="dropdown-menu {{ $isLanguageActive ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.system.languages*') ? 'active' : '' }}" href="{{ route('admin.system.languages.index') }}">🌍 Languages</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.translations*') ? 'active' : '' }}" href="{{ route('admin.system.translations.index') }}">💻 Language Translate</a>
          </div>
        </li>




        {{-- System Settings --}}
        @php
            $isSystemSettingsActive = request()->is('admin/system-settings*') || request()->routeIs(
                'admin.system.maintenances*',
                'admin.system.blocked-ips*',
                'admin.system.user-sessions*'
            );
        @endphp
        <li class="nav-item dropdown {{ $isSystemSettingsActive ? 'active' : '' }}">
          <a class="nav-link dropdown-toggle" href="#navbar-system-settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ $isSystemSettingsActive ? 'true' : 'false' }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block" aria-hidden="true">⚙️</span>
            <span class="nav-link-title">System</span>
          </a>
          <div class="dropdown-menu {{ $isSystemSettingsActive ? 'show' : '' }}">
            <a class="dropdown-item {{ request()->routeIs('admin.system.recaptcha-setting*') ? 'active' : '' }}" href="{{ route('admin.system.recaptcha-setting.index') }}">🤖 reCaptcha</a>
            <a class="dropdown-item" href="#">💳 Payment Method Setting</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.user-sessions*') ? 'active' : '' }}" href="{{ route('admin.system.user-sessions.index') }}">💻 User Sessions</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.activity-log*') ? 'active' : '' }}" href="{{ route('admin.system.activity-log.index') }}">📜 Audit Logs</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.blocked-ips*') ? 'active' : '' }}" href="{{ route('admin.system.blocked-ips.index') }}">🚫 Blocked IPs</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.maintenances*') ? 'active' : '' }}" href="{{ route('admin.system.maintenances.index') }}">🛠️ Maintenance</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.email-settings*') ? 'active' : '' }}" href="{{ route('admin.system.email-settings.index') }}">📧 Email Settings</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.settings*') ? 'active' : '' }}" href="{{ route('admin.system.settings.index') }}">⚙️ General Settings</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.newsletter-subscribers*') ? 'active' : '' }}" href="{{ route('admin.system.newsletter-subscribers.index') }}">📧 Newsletter Subscribers</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.newsletters*') ? 'active' : '' }}" href="{{ route('admin.system.newsletters.index') }}">📜 Newsletter Campaigns</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.database-backups*') ? 'active' : '' }}" href="{{ route('admin.system.database-backups.index') }}">💾 Database Backups</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.ad-mob-settings*') ? 'active' : '' }}" href="{{ route('admin.system.ad-mob-settings.index') }}">📱 AdMob Settings</a>
            <a class="dropdown-item {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">💬 Testimonials</a>
            <a class="dropdown-item {{ request()->routeIs('admin.system.faqs*') ? 'active' : '' }}" href="{{ route('admin.system.faqs.index') }}">❓ FAQs</a>
            <a class="dropdown-item {{ request()->routeIs('admin.legal.pages*') ? 'active' : '' }}" href="{{ route('admin.legal.pages.index') }}">⚖️ Legal Pages</a>
          </div>
        </li>
      </ul>
    </div>
    {{-- END SIDEBAR MENU --}}
  </div>
</aside>
{{-- END SIDEBAR --}}
