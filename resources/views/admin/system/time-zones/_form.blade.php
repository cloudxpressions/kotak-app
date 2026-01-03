<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $timeZone->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Timezone name (e.g., Asia/Kolkata)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Timezone</label>
      <input type="text" class="form-control @error('timezone') is-invalid @enderror" name="timezone" value="{{ old('timezone', $timeZone->timezone ?? '') }}" required>
      @error('timezone')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Timezone identifier (e.g., IST)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Offset</label>
      <input type="text" class="form-control @error('offset') is-invalid @enderror" name="offset" value="{{ old('offset', $timeZone->offset ?? '') }}">
      @error('offset')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">UTC offset (e.g., +05:30)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">UTC Offset Minutes</label>
      <input type="number" class="form-control @error('utc_offset_minutes') is-invalid @enderror" name="utc_offset_minutes" value="{{ old('utc_offset_minutes', $timeZone->utc_offset_minutes ?? 0) }}" required>
      @error('utc_offset_minutes')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Total minutes from UTC (e.g., 330 for +05:30)</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $timeZone->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  @error('is_active')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Inactive timezones won't be available for selection</small>
</div>