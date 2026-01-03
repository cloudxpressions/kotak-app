<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $state->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Code</label>
      <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $state->code ?? '') }}" maxlength="10">
      @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">State code (e.g., TN, CA)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Type</label>
      <input type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $state->type ?? '') }}">
      @error('type')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">State or Union Territory</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Country</label>
      <select class="form-select @error('country_id') is-invalid @enderror" name="country_id" required>
        <option value="">Select Country</option>
        @foreach($countries ?? [] as $country)
          <option value="{{ $country->id }}" {{ (old('country_id', $state->country_id ?? '') == $country->id) ? 'selected' : '' }}>
            {{ $country->name }}
          </option>
        @endforeach
      </select>
      @error('country_id')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $state->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  @error('is_active')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Inactive states won't be available for selection</small>
</div>