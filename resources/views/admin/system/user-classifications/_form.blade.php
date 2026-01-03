<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $userClassification->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Type</label>
      <input type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $userClassification->type ?? '') }}">
      @error('type')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Category group or classification type</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Icon</label>
      <input type="text" class="form-control @error('icon') is-invalid @enderror" name="icon" value="{{ old('icon', $userClassification->icon ?? '') }}">
      @error('icon')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Icon class or emoji (e.g., fa-user, 👥)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $userClassification->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
      </label>
      @error('is_active')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Inactive classifications won't be available for selection</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Description</label>
  <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $userClassification->description ?? '') }}</textarea>
  @error('description')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <small class="form-hint">Optional description for UI/help text</small>
</div>