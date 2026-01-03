<div class="row g-3">
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Parent Category</label>
      <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
        <option value="">None</option>
        @foreach($categories ?? [] as $cat)
          <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
        @endforeach
      </select>
      @error('parent_id')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>
  <div class="col-md-6">
    <div class="mb-3">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
        <span class="form-check-label">Active</span>
      </label>
      @error('is_active')
      <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
      <small class="form-hint">Inactive categories won't be available for selection</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Category Translations</h3>
      </div>
      <div class="card-body">
        @if($languages->count() > 0)
          @foreach($languages as $index => $lang)
            @php
              $translation = isset($category) ? $category->translations->where('language_id', $lang->id)->first() : null;
            @endphp
            <div class="row g-3 mb-3 p-2 border rounded">
              <div class="col-md-12">
                <h4>{{ $lang->name }} ({{ $lang->code }})</h4>
              </div>
              <div class="col-md-6">
                <label class="form-label required">Name</label>
                <input type="text"
                       class="form-control @error('translations.'.$index.'.name') is-invalid @enderror"
                       name="translations[{{ $index }}][name]"
                       value="{{ old('translations.'.$index.'.name', $translation?->name) }}"
                       required>
                @error('translations.'.$index.'.name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label required">Slug</label>
                <input type="text"
                       class="form-control @error('translations.'.$index.'.slug') is-invalid @enderror"
                       name="translations[{{ $index }}][slug]"
                       value="{{ old('translations.'.$index.'.slug', $translation?->slug) }}"
                       required>
                @error('translations.'.$index.'.slug')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $lang->id }}">
              </div>
            </div>
          @endforeach
        @else
          <p class="text-warning">No active languages found. Please create at least one active language.</p>
        @endif
      </div>
    </div>
  </div>
</div>