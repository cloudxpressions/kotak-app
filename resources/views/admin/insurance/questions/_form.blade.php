<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Question Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Difficulty</label>
                            <select class="form-select @error('difficulty') is-invalid @enderror" name="difficulty" required>
                                <option value="">Select Difficulty</option>
                                <option value="easy" {{ old('difficulty', $question->difficulty ?? '') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty', $question->difficulty ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty', $question->difficulty ?? '') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Correct Option</label>
                            <select class="form-select @error('correct_option') is-invalid @enderror" name="correct_option" required>
                                <option value="">Select Correct Option</option>
                                <option value="A" {{ old('correct_option', $question->correct_option ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('correct_option', $question->correct_option ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('correct_option', $question->correct_option ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('correct_option', $question->correct_option ?? '') == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                            @error('correct_option')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="hidden" name="is_active" value="0">
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
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

                    <div class="mb-3">
                        <label class="form-label">Question Text</label>
                        <textarea class="form-control @error("translations.$index.question_text") is-invalid @enderror" name="translations[{{ $index }}][question_text]" rows="3" required>{{ $translation['question_text'] ?? '' }}</textarea>
                        @error("translations.$index.question_text")
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option A</label>
                                <input type="text" class="form-control @error("translations.$index.option_a") is-invalid @enderror" name="translations[{{ $index }}][option_a]" value="{{ $translation['option_a'] }}" required>
                                @error("translations.$index.option_a")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option B</label>
                                <input type="text" class="form-control @error("translations.$index.option_b") is-invalid @enderror" name="translations[{{ $index }}][option_b]" value="{{ $translation['option_b'] }}" required>
                                @error("translations.$index.option_b")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option C</label>
                                <input type="text" class="form-control @error("translations.$index.option_c") is-invalid @enderror" name="translations[{{ $index }}][option_c]" value="{{ $translation['option_c'] }}" required>
                                @error("translations.$index.option_c")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option D</label>
                                <input type="text" class="form-control @error("translations.$index.option_d") is-invalid @enderror" name="translations[{{ $index }}][option_d]" value="{{ $translation['option_d'] }}" required>
                                @error("translations.$index.option_d")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @elseif(isset($question) && $question->translations->count() > 0)
                @foreach($question->translations as $index => $translation)
                <div class="translation-row mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="h6">Translation ({{ $translation->language->name ?? 'Unknown' }})</h4>
                        <button type="button" class="btn btn-sm btn-danger remove-translation">Remove</button>
                    </div>
                    <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $translation->language_id }}">
                    <input type="hidden" name="translations[{{ $index }}][language_code]" value="{{ $translation->language_code }}">

                    <div class="mb-3">
                        <label class="form-label">Question Text</label>
                        <textarea class="form-control @error("translations.$index.question_text") is-invalid @enderror" name="translations[{{ $index }}][question_text]" rows="3" required>{{ $translation->question_text ?? '' }}</textarea>
                        @error("translations.$index.question_text")
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option A</label>
                                <input type="text" class="form-control @error("translations.$index.option_a") is-invalid @enderror" name="translations[{{ $index }}][option_a]" value="{{ $translation->option_a }}" required>
                                @error("translations.$index.option_a")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option B</label>
                                <input type="text" class="form-control @error("translations.$index.option_b") is-invalid @enderror" name="translations[{{ $index }}][option_b]" value="{{ $translation->option_b }}" required>
                                @error("translations.$index.option_b")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option C</label>
                                <input type="text" class="form-control @error("translations.$index.option_c") is-invalid @enderror" name="translations[{{ $index }}][option_c]" value="{{ $translation->option_c }}" required>
                                @error("translations.$index.option_c")
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Option D</label>
                                <input type="text" class="form-control @error("translations.$index.option_d") is-invalid @enderror" name="translations[{{ $index }}][option_d]" value="{{ $translation->option_d }}" required>
                                @error("translations.$index.option_d")
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
    let translationIndex = {{ old('translations') ? count(old('translations')) : (isset($question) ? $question->translations->count() : 0) }};

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

                <div class="mb-3">
                    <label class="form-label">Question Text</label>
                    <textarea class="form-control" name="translations[${translationIndex}][question_text]" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Option A</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][option_a]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Option B</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][option_b]" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Option C</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][option_c]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Option D</label>
                            <input type="text" class="form-control" name="translations[${translationIndex}][option_d]" required>
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