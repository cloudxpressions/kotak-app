<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $city->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Country</label>
      <select class="form-select @error('country_id') is-invalid @enderror" name="country_id" id="country_id" required>
        <option value="">Select Country</option>
        @foreach($countries ?? [] as $country)
          <option value="{{ $country->id }}" {{ (old('country_id', $city->country_id ?? '') == $country->id) ? 'selected' : '' }}>
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

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">State</label>
      <select class="form-select @error('state_id') is-invalid @enderror" name="state_id" id="state_id" required>
        <option value="">Select State</option>
        @foreach($states ?? [] as $state)
          <option value="{{ $state->id }}" {{ (old('state_id', $city->state_id ?? '') == $state->id) ? 'selected' : '' }}>
            {{ $state->name }}
          </option>
        @endforeach
      </select>
      @error('state_id')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Latitude</label>
      <input type="text" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude', $city->latitude ?? '') }}">
      @error('latitude')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Decimal degrees (e.g., 13.0827)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Longitude</label>
      <input type="text" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude', $city->longitude ?? '') }}">
      @error('longitude')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Decimal degrees (e.g., 80.2707)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $city->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
      </label>
      @error('is_active')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Inactive cities won't be available for selection</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_district_hq" value="1" {{ old('is_district_hq', $city->is_district_hq ?? false) ? 'checked' : '' }}>
    <span class="form-check-label">District Headquarters</span>
  </label>
  @error('is_district_hq')
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
  <small class="form-hint">Mark if this city is a district headquarters</small>
</div>