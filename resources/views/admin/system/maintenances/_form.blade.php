@php($maintenance = $maintenance ?? null)

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', optional($maintenance)->maintenance_mode) ? 'checked' : '' }}>
      <span class="form-check-label">Enable Maintenance Mode</span>
    </label>
  </div>
  <div class="col-md-6">
    <label class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_emergency" value="1" {{ old('is_emergency', optional($maintenance)->is_emergency) ? 'checked' : '' }}>
      <span class="form-check-label">Emergency Downtime</span>
    </label>
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-6">
    <label class="form-label">Title</label>
    <input type="text" name="title" value="{{ old('title', optional($maintenance)->title) }}" class="form-control @error('title') is-invalid @enderror">
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Maintenance Banner</label>
    <input type="text" name="maintenance_page_banner" value="{{ old('maintenance_page_banner', optional($maintenance)->maintenance_page_banner) }}" class="form-control @error('maintenance_page_banner') is-invalid @enderror" placeholder="https://example.com/banner.png">
    @error('maintenance_page_banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-12">
    <label class="form-label">Subtitle / Message</label>
    <textarea name="subtitle" rows="3" class="form-control @error('subtitle') is-invalid @enderror">{{ old('subtitle', optional($maintenance)->subtitle) }}</textarea>
    @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-6">
    <label class="form-label">Starts At</label>
    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional(optional($maintenance)->starts_at)->format('Y-m-d\\TH:i')) }}" class="form-control @error('starts_at') is-invalid @enderror">
    @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Ends At</label>
    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional(optional($maintenance)->ends_at)->format('Y-m-d\\TH:i')) }}" class="form-control @error('ends_at') is-invalid @enderror">
    @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-12">
    <label class="form-label">Allowed IPs</label>
    @php($existingIps = $maintenance && $maintenance->allowed_ips ? implode(PHP_EOL, $maintenance->allowed_ips) : '')
    <textarea name="allowed_ips" rows="4" class="form-control @error('allowed_ips') is-invalid @enderror" placeholder="One IP per line">{{ old('allowed_ips', $existingIps) }}</textarea>
    @error('allowed_ips') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="form-hint">IPs listed here bypass maintenance mode (one per line or comma separated).</small>
  </div>
</div>
