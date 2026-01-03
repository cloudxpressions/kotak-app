<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('page-title', 'Dashboard') - {{ config('app.name') }}</title>

  {{-- BEGIN GLOBAL THEME SCRIPT --}}
  <script src="{{ asset('assets/backend/js/tabler-theme.min.js') }}"></script>
  {{-- END GLOBAL THEME SCRIPT --}}

  @include('admin.layouts.styles')
</head>
<body>
  <a href="#content" class="visually-hidden skip-link">Skip to main content</a>

  <div class="page">
    {{-- BEGIN SIDEBAR --}}
    @include('admin.layouts.sidebar')
    {{-- END SIDEBAR --}}

    {{-- BEGIN PAGE WRAPPER --}}
    <div class="page-wrapper">
      {{-- BEGIN NAVBAR --}}
      @include('admin.layouts.header')
      {{-- END NAVBAR --}}

      {{-- BEGIN PAGE HEADER --}}
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col-12 col-md">
              {{-- Page Title --}}
              <h2 class="page-title">
                @yield('page-title', 'Dashboard')
              </h2>
              {{-- Breadcrumbs --}}
              @hasSection('breadcrumbs')
                @yield('breadcrumbs')
              @endif
            </div>
            {{-- Page Actions --}}
            @hasSection('page-actions')
              <div class="col-12 col-md-auto ms-md-auto d-print-none">
                @yield('page-actions')
              </div>
            @endif
          </div>
        </div>
      </div>
      {{-- END PAGE HEADER --}}

      {{-- BEGIN PAGE BODY --}}
      <div class="page-body" id="content">
        <div class="container-xl">
          {{-- Main Content --}}
          @yield('content')
        </div>
      </div>
      {{-- END PAGE BODY --}}

      {{-- BEGIN FOOTER --}}
      @include('admin.layouts.footer')
      {{-- END FOOTER --}}
    </div>
    {{-- END PAGE WRAPPER --}}
  </div>


  {{-- BEGIN SCRIPTS --}}
  @include('admin.layouts.scripts')
  {{-- END SCRIPTS --}}
</body>
</html>
