@php($blockedIp = $blockedIp ?? null)

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label required">IP Address</label>
    <input type="text" name="ip_address" value="{{ old('ip_address', optional($blockedIp)->ip_address) }}" class="form-control @error('ip_address') is-invalid @enderror" required>
    @error('ip_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">User Agent</label>
    <input type="text" name="user_agent" value="{{ old('user_agent', optional($blockedIp)->user_agent) }}" class="form-control @error('user_agent') is-invalid @enderror">
    @error('user_agent') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-8">
    <label class="form-label">Reason</label>
    <textarea name="reason" rows="2" class="form-control @error('reason') is-invalid @enderror">{{ old('reason', optional($blockedIp)->reason) }}</textarea>
    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Blocked Until</label>
    <input type="datetime-local" name="blocked_until" value="{{ old('blocked_until', optional(optional($blockedIp)->blocked_until)->format('Y-m-d\\TH:i')) }}" class="form-control @error('blocked_until') is-invalid @enderror">
    @error('blocked_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-2">
  <div class="col-md-4">
    <label class="form-label">Attempts Count</label>
    <input type="number" name="attempts_count" value="{{ old('attempts_count', optional($blockedIp)->attempts_count ?? 0) }}" class="form-control @error('attempts_count') is-invalid @enderror" min="0">
    @error('attempts_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">Last Attempt At</label>
    <input type="datetime-local" name="last_attempt_at" value="{{ old('last_attempt_at', optional(optional($blockedIp)->last_attempt_at)->format('Y-m-d\\TH:i')) }}" class="form-control @error('last_attempt_at') is-invalid @enderror">
    @error('last_attempt_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-check form-switch mt-4">
      <input class="form-check-input" type="checkbox" name="is_permanent" value="1" {{ old('is_permanent', optional($blockedIp)->is_permanent) ? 'checked' : '' }}>
      <span class="form-check-label">Permanent Block</span>
    </label>
  </div>
</div>
