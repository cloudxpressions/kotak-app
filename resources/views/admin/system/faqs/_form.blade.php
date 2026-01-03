<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Sort Order</label>
    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
    <small class="form-hint">Lower numbers appear first.</small>
    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $faq->is_featured ?? false) ? 'checked' : '' }}>
      <span class="form-check-label">Mark as Featured</span>
    </label>
    <small class="form-hint">Featured FAQs are highlighted.</small>
  </div>
  <div class="col-md-3">
    <label class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
      <span class="form-check-label">Active</span>
    </label>
    <small class="form-hint">Inactive FAQs are hidden.</small>
  </div>
</div>

<div class="row g-3 mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">FAQ Translations</h3>
      </div>
      <div class="card-body">
        @if($languages->count() > 0)
          @foreach($languages as $index => $lang)
            @php
              $translation = isset($faq) ? $faq->translations->where('language_id', $lang->id)->first() : null;
            @endphp
            <div class="row g-3 mb-4 p-3 border rounded">
              <div class="col-md-12">
                <h4>{{ $lang->name }} ({{ $lang->code }})</h4>
              </div>
              <div class="col-md-12">
                <label class="form-label">Category</label>
                <input type="text"
                       class="form-control @error('translations.'.$index.'.category') is-invalid @enderror"
                       name="translations[{{ $index }}][category]"
                       value="{{ old('translations.'.$index.'.category', $translation?->category) }}"
                       placeholder="e.g. Payments & Pricing">
                @error('translations.'.$index.'.category')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-12">
                <label class="form-label required">Question</label>
                <input type="text"
                       class="form-control @error('translations.'.$index.'.question') is-invalid @enderror"
                       name="translations[{{ $index }}][question]"
                       value="{{ old('translations.'.$index.'.question', $translation?->question) }}"
                       required>
                @error('translations.'.$index.'.question')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-12">
                <label class="form-label required">Answer</label>
                <textarea name="translations[{{ $index }}][answer]"
                          class="form-control @error('translations.'.$index.'.answer') is-invalid @enderror"
                          rows="6"
                          placeholder="Provide a detailed yet concise answer"
                          required>{{ old('translations.'.$index.'.answer', $translation?->answer) }}</textarea>
                @error('translations.'.$index.'.answer')
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
