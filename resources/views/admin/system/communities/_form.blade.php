<div class="mb-3">
  <label class="form-label required">Name</label>
  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $community->name ?? '') }}" required>
  @error('name')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-label">Description</label>
  <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $community->description ?? '') }}</textarea>
  @error('description')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <small class="form-hint">Optional description for UI/help text</small>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $community->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  @error('is_active')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Inactive communities won't be available for selection</small>
</div>