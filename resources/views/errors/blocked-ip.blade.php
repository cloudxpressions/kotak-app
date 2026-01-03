<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Access Blocked</title>
  <link rel="stylesheet" href="{{ asset('public/dist/css/tabler.min.css') }}">
</head>
<body class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light text-center px-3">
  <div class="card shadow-sm" style="max-width: 480px;">
    <div class="card-body">
      <h1 class="card-title mb-3">Access Blocked</h1>
      <p class="text-secondary">
        Requests from your IP address ({{ $blockedIp->ip_address }}) are currently blocked.
      </p>
      @if($blockedIp->reason)
        <p class="text-secondary">
          <strong>Reason:</strong> {{ $blockedIp->reason }}
        </p>
      @endif
      @if($blockedIp->blocked_until && ! $blockedIp->is_permanent)
        <p class="text-secondary">
          Block expires on {{ $blockedIp->blocked_until->format('d M Y H:i') }}.
        </p>
      @endif
    </div>
  </div>
</body>
</html>
