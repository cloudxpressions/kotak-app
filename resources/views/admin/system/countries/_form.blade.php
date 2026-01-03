<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $country->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Native Name</label>
      <input type="text" class="form-control @error('native_name') is-invalid @enderror" name="native_name" value="{{ old('native_name', $country->native_name ?? '') }}">
      @error('native_name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Name in native language</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">ISO3</label>
      <input type="text" class="form-control @error('iso3') is-invalid @enderror" name="iso3" value="{{ old('iso3', $country->iso3 ?? '') }}" maxlength="3">
      @error('iso3')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">3-letter ISO code (e.g., IND)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">ISO2</label>
      <input type="text" class="form-control @error('iso2') is-invalid @enderror" name="iso2" value="{{ old('iso2', $country->iso2 ?? '') }}" maxlength="2">
      @error('iso2')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">2-letter ISO code (e.g., IN)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Phone Code</label>
      <input type="text" class="form-control @error('phonecode') is-invalid @enderror" name="phonecode" value="{{ old('phonecode', $country->phonecode ?? '') }}">
      @error('phonecode')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">International dialing code (e.g., 91)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Currency</label>
      <input type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" value="{{ old('currency', $country->currency ?? '') }}" maxlength="3">
      @error('currency')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Currency code (e.g., INR)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Capital</label>
      <input type="text" class="form-control @error('capital') is-invalid @enderror" name="capital" value="{{ old('capital', $country->capital ?? '') }}">
      @error('capital')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Continent</label>
      <input type="text" class="form-control @error('continent') is-invalid @enderror" name="continent" value="{{ old('continent', $country->continent ?? '') }}">
      @error('continent')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Emoji Flag</label>
      <input type="text" class="form-control @error('emoji_flag') is-invalid @enderror" name="emoji_flag" value="{{ old('emoji_flag', $country->emoji_flag ?? '') }}">
      @error('emoji_flag')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Unicode flag emoji (e.g., 🇮🇳)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $country->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
      </label>
      @error('is_active')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Inactive countries won't be available for selection</small>
    </div>
  </div>
</div>