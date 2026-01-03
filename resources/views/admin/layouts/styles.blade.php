{{-- BEGIN GLOBAL MANDATORY STYLES --}}
<link href="{{ asset('assets/backend/css/tabler.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/backend/css/tabler-flags.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/backend/css/tabler-payments.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/backend/css/tabler-vendors.min.css') }}" rel="stylesheet" />

<!-- DataTables CSS (Local) -->
<link href="{{ asset('assets/backend/libs/DataTables/datatables.min.css') }}" rel="stylesheet">

<!-- Toastr CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* Select2 Bootstrap 5 Theme Fixes */
    .select2-container--default .select2-selection--single {
        border: 1px solid #dce1e7;
        border-radius: 4px;
        height: 38px; /* Match Bootstrap standard input height */
        padding: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        color: #1d273b;
    }
    .select2-dropdown {
        border: 1px solid #dce1e7;
        border-radius: 4px;
    }
    .select2-search__field {
        border: 1px solid #dce1e7 !important;
        border-radius: 4px !important;
    }
    /* Dark mode support for Select2 */
    /* Dark mode support for Select2 (Default & Bootstrap 5 Theme) */
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single,
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered,
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection__rendered {
        color: #f3f4f6;
    }
    [data-bs-theme="dark"] .select2-dropdown,
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected],
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
        background-color: #206bc4;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-results__option[aria-selected=true],
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
        background-color: #374151;
        color: #f3f4f6;
    }
    [data-bs-theme="dark"] .select2-search__field {
        background-color: #111827;
        border-color: #374151 !important;
        color: #f3f4f6;
    }
    /* Fix for Bootstrap 5 theme input height in dark mode if needed */
    [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single {
        background-image: none; /* Remove default gradient if any */
    }
</style>
{{-- END GLOBAL MANDATORY STYLES --}}

{{-- BEGIN CUSTOM FONT --}}
<style>
  @import url("https://rsms.me/inter/inter.css");
  
  /* Sidebar Toggle Styles */
  .navbar-vertical {
    transition: transform 0.3s ease, width 0.3s ease;
  }
  
  .navbar-vertical.collapsed {
    transform: translateX(-100%);
    width: 0;
  }
  
  .page-wrapper {
    transition: margin-left 0.3s ease;
  }
  
  /* Responsive Button List - Stack on Mobile */
  @media (max-width: 767.98px) {
    .btn-list {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
    }
    
    .btn-list > * {
      width: 100%;
    }
    
    .btn-list .btn-group {
      width: 100%;
    }
    
    .btn-list .btn-group .btn {
      width: 100%;
    }
  }
</style>
{{-- END CUSTOM FONT --}}

{{-- BEGIN PAGE LEVEL STYLES --}}
@stack('page-styles')
{{-- END PAGE LEVEL STYLES --}}
