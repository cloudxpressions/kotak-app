<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Format</label>
      <input type="text" class="form-control @error('format') is-invalid @enderror" name="format" value="{{ old('format', $dateFormat->format ?? '') }}" required>
      @error('format')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">PHP date format (e.g., Y-m-d, d/m/Y)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Normal View</label>
      <input type="text" class="form-control @error('normal_view') is-invalid @enderror" name="normal_view" value="{{ old('normal_view', $dateFormat->normal_view ?? '') }}" required>
      @error('normal_view')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Display example (e.g., 17th May, 2019)</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $dateFormat->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  @error('is_active')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Inactive formats won't be available for selection</small>
</div>