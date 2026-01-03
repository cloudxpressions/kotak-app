<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Material Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Exam</label>
                            <select class="form-select @error('exam_id') is-invalid @enderror" name="exam_id" required>
                                <option value="">Select Exam</option>
                                @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $material->exam_id ?? '') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->code }} - {{ $exam->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('exam_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                                <option value="">Select Type</option>
                                <option value="pdf" {{ old('type', $material->type ?? '') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="poster" {{ old('type', $material->type ?? '') == 'poster' ? 'selected' : '' }}>Poster</option>
                                <option value="note" {{ old('type', $material->type ?? '') == 'note' ? 'selected' : '' }}>Note</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">File Size (bytes)</label>
                            <input type="number" class="form-control @error('file_size') is-invalid @enderror" name="file_size" value="{{ old('file_size', $material->file_size ?? '') }}">
                            @error('file_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="hidden" name="is_active" value="0">
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $material->is_active ?? true) ? 'checked' : '' }}>
                        <span class="form-check-label">Active</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Translations</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Language</label>
                    <select class="form-select" id="language-select">
                        <option value="">Select Language</option>
                        @foreach($languages as $language)
                        <option value="{{ $language->id }}" data-code="{{ $language->code }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Add Translation</label>
                    <button type="button" class="btn btn-primary w-100" id="add-translation-btn">Add Translation</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Translations</h3>
    </div>
    <div class="card-body">
        <div id="translations-container">
            @if(old('translations'))
                @foreach(old('translations') as $index => $translation)
                <div class="translation-row mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="h6">Translation ({{ $languages->firstWhere('id', $translation['language_id'])->name ?? 'Unknown' }})</h4>
                        <button type="button" class="btn btn-sm btn-danger remove-translation">Remove</button>
                    </div>
                    <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $translation['language_id'] }}">
                    <input type="hidden" name="translations[{{ $index }}][language_code]" value="{{ $languages->firstWhere('id', $translation['language_id'])->code ?? '' }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control @error("translations.$index.title") is-invalid @enderror" name="translations[{{ $index }}][title]" value="{{ $translation['title'] }}" required>
                                @error("translations.$index.title")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">File Path</label>
                                <input type="text" class="form-control @error("translations.$index.file_path") is-invalid @enderror" name="translations[{{ $index }}][file_path]" value="{{ $translation['file_path'] }}" required>
                                @error("translations.$index.file_path")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @elseif(isset($material) && $material->translations->count() > 0)
                @foreach($material->translations as $index => $translation)
                <div class="translation-row mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="h6">Translation ({{ $translation->language->name ?? 'Unknown' }})</h4>
                        <button type="button" class="btn btn-sm btn-danger remove-translation">Remove</button>
                    </div>
                    <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $translation->language_id }}">
                    <input type="hidden" name="translations[{{ $index }}][language_code]" value="{{ $translation->language_code }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control @error("translations.$index.title") is-invalid @enderror" name="translations[{{ $index }}][title]" value="{{ $translation->title }}" required>
                                @error("translations.$index.title")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">File Path</label>
                                <input type="text" class="form-control @error("translations.$index.file_path") is-invalid @enderror" name="translations[{{ $index }}][file_path]" value="{{ $translation->file_path }}" required>
                                @error("translations.$index.file_path")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let translationIndex = {{ old('translations') ? count(old('translations')) : (isset($material) ? $material->translations->count() : 0) }};

    document.getElementById('add-translation-btn').addEventListener('click', function() {
        const selectedOption = document.getElementById('language-select').selectedOptions[0];
        if (!selectedOption || !selectedOption.value) {
            alert('Please select a language first.');
            return;
        }

        const languageId = selectedOption.value;
        const languageCode = selectedOption.getAttribute('data-code');
        const languageName = selectedOption.textContent;

        // Check if translation for this language already exists
        const existingTranslations = document.querySelectorAll('.translation-row input[type="hidden"][name$="[language_id]"]');
        for (let i = 0; i < existingTranslations.length; i++) {
            if (existingTranslations[i].value == languageId) {
                alert('Translation for this language already exists.');
                return;
            }
        }

        const translationHtml = `
            <div class="translation-row mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 class="h6">Translation (${languageName})</h4>
                    <button type="button" class="btn btn-sm btn-danger remove-translation">Remove</button>
                </div>
                <input type="hidden" name="translations[${translationIndex}][language_id]" value="${languageId}">
                <input type="hidden" name="translations[${translationIndex}][language_code]" value="${languageCode}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][title]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">File Path</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][file_path]" required>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('translations-container').insertAdjacentHTML('beforeend', translationHtml);
        translationIndex++;
    });

    // Use event delegation for remove buttons
    document.getElementById('translations-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-translation')) {
            e.target.closest('.translation-row').remove();
        }
    });
});
</script>
@endpush