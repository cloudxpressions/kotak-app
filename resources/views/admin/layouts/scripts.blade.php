<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- DataTables (Local) -->
<script src="{{ asset('assets/backend/libs/DataTables/datatables.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- HugeRTE -->
<script src="https://cdn.jsdelivr.net/npm/hugerte@1/hugerte.min.js"></script>

<!-- CompressorJS -->
<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.2.1/dist/compressor.min.js"></script>

<script>
  toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
  };

  @if(session('success'))
    toastr.success("{{ session('success') }}", "Success");
  @endif

  @if(session('error'))
    toastr.error("{{ session('error') }}", "Error");
  @endif

  @if(session('warning'))
    toastr.warning("{{ session('warning') }}", "Warning");
  @endif

  @if(session('info'))
    toastr.info("{{ session('info') }}", "Info");
  @endif

  @if($errors->any())
    @foreach($errors->all() as $error)
      toastr.error("{{ $error }}", "Validation Error");
    @endforeach
  @endif
</script>

<script src="{{ asset('assets/backend/js/tabler.min.js') }}"></script>

{{-- BEGIN CUSTOM SCRIPTS --}}
<script>
  // Password Toggle Functionality
  document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    document.querySelectorAll('[data-password-toggle]').forEach(function(toggle) {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const input = this.closest('.input-group').querySelector('input');
        const icon = this.querySelector('svg');
        
        if (input.type === 'password') {
          input.type = 'text';
          icon.innerHTML = '<path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" /><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" /><path d="M3 3l18 18" />';
        } else {
          input.type = 'password';
          icon.innerHTML = '<path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />';
        }
      });
    });

    // Theme Toggle Functionality
    document.querySelectorAll('[data-theme-toggle]').forEach(function(toggle) {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const theme = this.getAttribute('data-theme-toggle');
        
        // Set theme attribute
        document.documentElement.setAttribute('data-bs-theme', theme);
        
        // Save to localStorage
        localStorage.setItem('tabler-theme', theme);
        
        // Update visibility of toggle buttons using classes
        updateThemeToggles();
      });
    });

    // Update theme toggle button visibility
    function updateThemeToggles() {
      const currentTheme = localStorage.getItem('tabler-theme') || 
                          document.documentElement.getAttribute('data-bs-theme') || 
                          'light';
      
      document.querySelectorAll('.hide-theme-dark').forEach(el => {
        if (currentTheme === 'dark') {
          el.classList.add('d-none');
        } else {
          el.classList.remove('d-none');
        }
      });
      
      document.querySelectorAll('.hide-theme-light').forEach(el => {
        if (currentTheme === 'light') {
          el.classList.add('d-none');
        } else {
          el.classList.remove('d-none');
        }
      });
    }

    // Initialize theme toggles
    updateThemeToggles();

    // Sidebar Toggle Functionality (Desktop only)
    const navbarToggler = document.getElementById('navbar-toggler');
    const sidebar = document.querySelector('.navbar-vertical');
    const pageWrapper = document.querySelector('.page-wrapper');
    
    if (navbarToggler && sidebar) {
      // Check localStorage for sidebar state
      const sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
      
      // Apply initial state on desktop
      if (sidebarCollapsed && window.innerWidth >= 992) {
        sidebar.classList.add('collapsed');
        if (pageWrapper) {
          pageWrapper.style.marginLeft = '0';
        }
      }
      
      // Toggle sidebar on button click (desktop only)
      navbarToggler.addEventListener('click', function(e) {
        // Only toggle sidebar on desktop (lg and above)
        if (window.innerWidth >= 992) {
          e.preventDefault();
          e.stopPropagation();
          
          sidebar.classList.toggle('collapsed');
          
          // Update page wrapper margin
          if (pageWrapper) {
            if (sidebar.classList.contains('collapsed')) {
              pageWrapper.style.marginLeft = '0';
              localStorage.setItem('sidebar-collapsed', 'true');
            } else {
              pageWrapper.style.marginLeft = '';
              localStorage.setItem('sidebar-collapsed', 'false');
            }
          }
        }
        // On mobile, let Bootstrap handle the collapse behavior
      });
    }

    // Language Switching
    document.querySelectorAll('.language-switch').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const langCode = this.getAttribute('data-lang');
        
        fetch('{{ route("admin.system.languages.change") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            language_code: langCode
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Reload page to apply language changes
            window.location.reload();
          }
        })
        .catch(error => {
          console.error('Language change failed:', error);
        });
      });
    });
  });

  // Notification System
  $(document).ready(function() {
    // Function to load notifications
    function loadNotifications() {
      $.ajax({
        url: '{{ route("admin.notifications.unread") }}',
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          // Update badge count
          const count = response.count;
          $('#notifications-badge').text(count > 0 ? count : '');

          // Update notifications list
          const $list = $('#notifications-list');
          $list.empty();

          if (response.notifications.length > 0) {
            response.notifications.forEach(function(notification) {
              const $item = $(`
                <div class="list-group-item notification-item" data-id="${notification.id}">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="status-dot status-dot-animated bg-red d-block"></span>
                    </div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block notification-link" data-id="${notification.id}">
                        ${notification.data.message}
                      </a>
                      <div class="d-block text-secondary text-truncate mt-n1">
                        ${moment(notification.created_at).fromNow()}
                      </div>
                    </div>
                    <div class="col-auto">
                      <button class="btn btn-ghost-secondary btn-sm mark-read-btn" data-id="${notification.id}" title="Mark as read">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                          <path d="M12 8v4" />
                          <path d="M12 16h.01" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              `);
              $list.append($item);
            });
          } else {
            $list.append(`
              <div class="list-group-item text-center py-4">
                <div class="text-secondary">No notifications</div>
              </div>
            `);
          }
        },
        error: function(xhr) {
          console.error('Failed to load notifications:', xhr);
        }
      });
    }

    // Load notifications initially
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);

    // Mark all as read
    $(document).on('click', '#mark-all-read', function(e) {
      e.preventDefault();

      $.ajax({
        url: '{{ route("admin.notifications.mark-all-as-read") }}',
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          loadNotifications(); // Reload notifications
          toastr.success(response.message);
        },
        error: function(xhr) {
          toastr.error('Failed to mark notifications as read');
        }
      });
    });

    // Mark single notification as read
    $(document).on('click', '.mark-read-btn', function(e) {
      e.stopPropagation();
      const notificationId = $(this).data('id');

      $.ajax({
        url: `{{ route("admin.notifications.mark-as-read", ":id") }}`.replace(':id', notificationId),
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          loadNotifications(); // Reload notifications
          toastr.success(response.message);
        },
        error: function(xhr) {
          toastr.error('Failed to mark notification as read');
        }
      });
    });

    // Click on notification link to mark as read
    $(document).on('click', '.notification-link', function(e) {
      e.preventDefault();
      const notificationId = $(this).data('id');

      $.ajax({
        url: `{{ route("admin.notifications.mark-as-read", ":id") }}`.replace(':id', notificationId),
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          loadNotifications(); // Reload notifications
          // Additional action can be performed here if needed
        },
        error: function(xhr) {
          console.error('Failed to mark notification as read:', xhr);
        }
      });
    });
  });

  // Add moment.js for time formatting
  if (typeof moment === 'undefined') {
    // Load moment.js if not already present
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js';
    document.head.appendChild(script);
  }
</script>
{{-- END CUSTOM SCRIPTS --}}

{{-- BEGIN PAGE LEVEL SCRIPTS --}}
@stack('scripts')
{{-- END PAGE LEVEL SCRIPTS --}}
