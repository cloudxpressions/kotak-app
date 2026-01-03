<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We'll be back soon</title>
  <link rel="stylesheet" href="{{ asset('public/dist/css/tabler.min.css') }}">
</head>
<body class="d-flex flex-column justify-content-center align-items-center min-vh-100 bg-light text-center px-3">
  <div class="card shadow-sm" style="max-width: 520px;">
    @if($maintenance->maintenance_page_banner)
      <img src="{{ $maintenance->maintenance_page_banner }}" class="card-img-top" alt="Maintenance banner">
    @endif
    <div class="card-body">
      <h1 class="card-title mb-3">{{ $maintenance->title ?? 'Scheduled Maintenance' }}</h1>
      <p class="text-secondary">
        {{ $maintenance->subtitle ?? 'We are performing some updates. Please check back in a little while.' }}
      </p>
      @if($maintenance->starts_at || $maintenance->ends_at)
        <div class="mt-3 text-start">
          @if($maintenance->starts_at)
            <div><strong>Started:</strong> {{ $maintenance->starts_at->format('d M Y H:i') }}</div>
          @endif
          @if($maintenance->ends_at)
            <div><strong>Estimated End:</strong> {{ $maintenance->ends_at->format('d M Y H:i') }}</div>
          @endif
        </div>
      @endif
    </div>
  </div>
</body>
</html>
