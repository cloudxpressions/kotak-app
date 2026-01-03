<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $currency->name ?? '') }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Code</label>
      <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $currency->code ?? '') }}" maxlength="3" required>
      @error('code')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">ISO 4217 code (e.g., USD, EUR, INR)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Symbol</label>
      <input type="text" class="form-control @error('symbol') is-invalid @enderror" name="symbol" value="{{ old('symbol', $currency->symbol ?? '') }}">
      @error('symbol')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Currency symbol (e.g., $, €, ₹)</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Conversion Rate</label>
      <input type="number" step="0.0001" class="form-control @error('conversion_rate') is-invalid @enderror" name="conversion_rate" value="{{ old('conversion_rate', $currency->conversion_rate ?? 1) }}" required>
      @error('conversion_rate')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Exchange rate to base currency</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Symbol Position</label>
      <select class="form-select @error('symbol_position') is-invalid @enderror" name="symbol_position" required>
        <option value="before" {{ old('symbol_position', $currency->symbol_position ?? 'before') == 'before' ? 'selected' : '' }}>Before (₹500)</option>
        <option value="after" {{ old('symbol_position', $currency->symbol_position ?? 'before') == 'after' ? 'selected' : '' }}>After (500₹)</option>
      </select>
      @error('symbol_position')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Decimal Places</label>
      <input type="number" min="0" max="10" class="form-control @error('decimal_places') is-invalid @enderror" name="decimal_places" value="{{ old('decimal_places', $currency->decimal_places ?? 2) }}" required>
      @error('decimal_places')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">Number of decimal places (typically 0-4)</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_default" value="1" {{ old('is_default', $currency->is_default ?? false) ? 'checked' : '' }}>
        <span class="form-check-label">Default Currency</span>
      </label>
      @error('is_default')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Only one currency can be set as default</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $currency->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
      </label>
      @error('is_active')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Inactive currencies won't be available for selection</small>
    </div>
  </div>
</div>