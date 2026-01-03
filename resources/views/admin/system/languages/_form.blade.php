<div class="mb-3">
  <label class="form-label required">Name</label>
  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $language->name ?? '') }}" required>
  @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <small class="form-hint">Language name in English (e.g., "English", "Tamil")</small>
</div>

<div class="mb-3">
  <label class="form-label">Native Name</label>
  <input type="text" class="form-control @error('native_name') is-invalid @enderror" name="native_name" value="{{ old('native_name', $language->native_name ?? '') }}">
  @error('native_name')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  <small class="form-hint">Language name in native script (e.g., "தமிழ்", "हिन्दी")</small>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label required">Language Code</label>
      <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $language->code ?? '') }}" maxlength="10" required>
      @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">ISO code (e.g., "en", "ta", "hi")</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Slug</label>
      <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $language->slug ?? '') }}" maxlength="20">
      @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
      <small class="form-hint">URL slug (optional, defaults to lang code)</small>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-label required">Text Direction</label>
  <select class="form-select @error('direction') is-invalid @enderror" name="direction" required>
    <option value="ltr" {{ old('direction', $language->direction ?? 'ltr') == 'ltr' ? 'selected' : '' }}>Left to Right (LTR)</option>
    <option value="rtl" {{ old('direction', $language->direction ?? '') == 'rtl' ? 'selected' : '' }}>Right to Left (RTL)</option>
  </select>
  @error('direction')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="default" value="1" {{ old('default', $language->default ?? false) ? 'checked' : '' }}>
    <span class="form-check-label">Set as Default Language</span>
  </label>
  <small class="form-hint">Only one language can be set as default</small>
</div>

<div class="mb-3">
  <label class="form-check form-switch">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $language->is_active ?? true) ? 'checked' : '' }}>
    <span class="form-check-label">Active</span>
  </label>
  <small class="form-hint">Inactive languages won't be available for selection</small>
</div>
