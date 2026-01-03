<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $daCategory->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Code</label>
      <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $daCategory->code ?? '') }}" maxlength="10">
      @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Short code (e.g., OH, VH, HI)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Severity Level</label>
      <select class="form-select @error('severity_level') is-invalid @enderror" name="severity_level">
        <option value="">Select Severity Level</option>
        <option value="mild" {{ old('severity_level', $daCategory->severity_level ?? '') == 'mild' ? 'selected' : '' }}>Mild</option>
        <option value="moderate" {{ old('severity_level', $daCategory->severity_level ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
        <option value="severe" {{ old('severity_level', $daCategory->severity_level ?? '') == 'severe' ? 'selected' : '' }}>Severe</option>
      </select>
      @error('severity_level')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Percentage</label>
      <input type="number" min="0" max="100" class="form-control @error('percentage') is-invalid @enderror" name="percentage" value="{{ old('percentage', $daCategory->percentage ?? '') }}">
      @error('percentage')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Disability percentage (0-100)</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Description</label>
  <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $daCategory->description ?? '') }}</textarea>
  @error('description')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <small class="form-hint">Optional description for UI/help text</small>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $daCategory->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  @error('is_active')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Inactive categories won't be available for selection</small>
</div>