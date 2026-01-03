<div class="row g-3">
  {{-- MAIN CONTENT (LEFT) --}}
  <div class="col-lg-9">
    {{-- Translations / Content --}}
    <div class="card mb-3">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="card-title mb-0">Post Content</h3>
            <small class="text-secondary d-block">Write your article in all active languages.</small>
          </div>
        </div>
      </div>
      <div class="card-body">
        @if($languages->count() > 0)
          @foreach($languages as $index => $lang)
            @php
              $translation = isset($post) ? $post->translations->where('language_id', $lang->id)->first() : null;
            @endphp

            <div class="mb-4 pb-3 border-bottom">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">{{ $lang->name }} <span class="text-secondary">({{ $lang->code }})</span></h4>
                <span class="badge bg-primary-lt text-uppercase">Language {{ $index + 1 }}</span>
              </div>

              <div class="mb-3">
                <label class="form-label required">Title</label>
                <input
                  type="text"
                  class="form-control @error('translations.'.$index.'.title') is-invalid @enderror"
                  name="translations[{{ $index }}][title]"
                  value="{{ old('translations.'.$index.'.title', $translation?->title ?? '') }}"
                  required>
                @error('translations.'.$index.'.title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label class="form-label required">Summary</label>
                <textarea
                  class="form-control @error('translations.'.$index.'.summary') is-invalid @enderror"
                  name="translations[{{ $index }}][summary]"
                  rows="3"
                  required>{{ old('translations.'.$index.'.summary', $translation?->summary ?? '') }}</textarea>
                @error('translations.'.$index.'.summary')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Short intro shown in lists and previews.</small>
              </div>

              <div class="mb-0">
                <label class="form-label required">Content</label>
                <textarea
                  class="form-control hugerte-editor @error('translations.'.$index.'.content') is-invalid @enderror"
                  name="translations[{{ $index }}][content]"
                  id="content_{{ $lang->code }}_{{ $index }}"
                  rows="8"
                  required>{{ old('translations.'.$index.'.content', $translation?->content ?? '') }}</textarea>
                @error('translations.'.$index.'.content')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <input type="hidden" name="translations[{{ $index }}][language_id]" value="{{ $lang->id }}">
            </div>
          @endforeach
        @else
          <p class="text-warning mb-0">No active languages found. Please create at least one active language.</p>
        @endif
      </div>
    </div>

    {{-- References --}}
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title mb-0">References</h3>
        <small class="text-secondary d-block">Optional links to external sources, articles or docs.</small>
      </div>
      <div class="card-body">
        <div id="references-container">
          @if(isset($post) && $post->references->count() > 0)
            @foreach($post->references as $index => $reference)
              <div class="row g-2 mb-2 reference-row align-items-center">
                <div class="col-md-5">
                  <input type="text"
                         name="references[{{ $index }}][title]"
                         class="form-control"
                         placeholder="Reference Title"
                         value="{{ $reference->title }}">
                </div>
                <div class="col-md-6">
                  <input type="url"
                         name="references[{{ $index }}][url]"
                         class="form-control"
                         placeholder="Reference URL"
                         value="{{ $reference->url }}">
                </div>
                <div class="col-md-1 d-flex justify-content-end">
                  <button type="button" class="btn btn-icon btn-danger remove-reference" title="Remove">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M4 7l16 0" />
                      <path d="M10 11l0 6" />
                      <path d="M14 11l0 6" />
                      <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                      <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                  </button>
                </div>
              </div>
            @endforeach
          @else
            <div class="row g-2 mb-2 reference-row align-items-center">
              <div class="col-md-5">
                <input type="text"
                       name="references[0][title]"
                       class="form-control"
                       placeholder="Reference Title">
              </div>
              <div class="col-md-6">
                <input type="url"
                       name="references[0][url]"
                       class="form-control"
                       placeholder="Reference URL">
              </div>
              <div class="col-md-1 d-flex justify-content-end">
                <button type="button" class="btn btn-icon btn-danger remove-reference" title="Remove">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                       viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                       stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 7l16 0" />
                    <path d="M10 11l0 6" />
                    <path d="M14 11l0 6" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                  </svg>
                </button>
              </div>
            </div>
          @endif
        </div>

        <button type="button" class="btn btn-outline-primary mt-2" id="add-reference">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
               viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
               stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
          </svg>
          Add Reference
        </button>
      </div>
    </div>

    {{-- Attachments --}}
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title mb-0">Attachments</h3>
        <small class="text-secondary d-block">Upload additional files related to this post.</small>
      </div>
      <div class="card-body">
        <div id="attachments-container">
          <div class="mb-3 attachment-row">
            <label class="form-label">Upload Files</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
            <small class="form-hint">You can select multiple files at once. Max size 10MB each.</small>
          </div>
        </div>

        <button type="button" class="btn btn-outline-primary mt-2" id="add-attachment">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
               viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
               stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 5l0 14" />
            <path d="M5 12l14 0" />
          </svg>
          Add More Files
        </button>

        @if(isset($post) && $post->attachments->count() > 0)
          <div class="mt-3">
            <h5>Existing Attachments</h5>
            <div class="list-group">
              @foreach($post->attachments as $attachment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-decoration-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="20" height="20"
                           viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                           stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                      </svg>
                      {{ $attachment->file_name }}
                    </a>
                  </div>
                  <div>
                    <label class="form-check form-check-inline mb-0">
                      <input class="form-check-input" type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}">
                      <span class="form-check-label text-danger">Delete</span>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- SIDEBAR (RIGHT) --}}
  <div class="col-lg-3">
    <div class="sticky-top" style="top: 5.75rem;">
      {{-- Basic Meta --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Post Details</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Category</label>
            <select class="form-select @error('blog_category_id') is-invalid @enderror"
                    name="blog_category_id" required>
              <option value="">Select Category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}"
                  {{ old('blog_category_id', isset($post) ? $post->blog_category_id : '') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('blog_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label required">Author</label>
            <select class="form-select @error('user_id') is-invalid @enderror"
                    name="user_id" required>
              <option value="">Select Author</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}"
                  {{ old('user_id', isset($post) ? $post->user_id : '') == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
              @endforeach
            </select>
            @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-0">
            <label class="form-label required">Slug</label>
            <input type="text"
                   class="form-control @error('slug') is-invalid @enderror"
                   name="slug"
                   value="{{ old('slug', isset($post) ? $post->slug : '') }}"
                   required>
            @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">URL-friendly identifier (e.g., <code>my-amazing-post</code>).</small>
          </div>
        </div>
      </div>

      {{-- Featured Image --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Featured Image</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if(isset($post) && $post->image)
              <div class="mt-2">
                <img src="{{ Storage::url($post->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px;">
                <div class="form-check mt-1">
                  <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                  <label class="form-check-label text-danger" for="remove_image">Remove Image</label>
                </div>
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">Or Image URL</label>
            <input type="url" 
                   class="form-control @error('image_url') is-invalid @enderror" 
                   name="image_url" 
                   placeholder="https://example.com/image.jpg"
                   value="{{ old('image_url', isset($post) ? $post->image_url : '') }}">
            @error('image_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">External URL if not uploading.</small>
          </div>

          <div class="mb-0">
            <label class="form-label">Image Description</label>
            <textarea class="form-control @error('image_description') is-invalid @enderror" 
                      name="image_description" 
                      rows="2"
                      placeholder="Alt text for accessibility">{{ old('image_description', isset($post) ? $post->image_description : '') }}</textarea>
            @error('image_description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      {{-- Publish Options --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Publishing</h3>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label required">Publish Status</label>
            <select class="form-select @error('publish_status') is-invalid @enderror"
                    name="publish_status" required>
              <option value="draft"
                {{ old('publish_status', isset($post) ? $post->publish_status : 'draft') == 'draft' ? 'selected' : '' }}>
                Draft
              </option>
              <option value="scheduled"
                {{ old('publish_status', isset($post) ? $post->publish_status : 'draft') == 'scheduled' ? 'selected' : '' }}>
                Scheduled
              </option>
              <option value="published"
                {{ old('publish_status', isset($post) ? $post->publish_status : 'draft') == 'published' ? 'selected' : '' }}>
                Published
              </option>
            </select>
            @error('publish_status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Publish Date</label>
            <input type="datetime-local"
                   class="form-control @error('publish_date') is-invalid @enderror"
                   name="publish_date"
                   value="{{ old('publish_date', isset($post) && $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}">
            @error('publish_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Required for scheduled posts.</small>
          </div>

          <div class="mb-2">
            <label class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     name="is_visible"
                     value="1"
                     {{ old('is_visible', isset($post) ? $post->is_visible : true) ? 'checked' : '' }}>
              <span class="form-check-label">Visible</span>
            </label>
            @error('is_visible')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-0">
            <label class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     name="show_author"
                     value="1"
                     {{ old('show_author', isset($post) ? $post->show_author : true) ? 'checked' : '' }}>
              <span class="form-check-label">Show Author</span>
            </label>
            @error('show_author')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      {{-- Highlight Options --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Highlights</h3>
        </div>
        <div class="card-body">
          <div class="row row-cols-1 g-2">
            <div class="col">
              <label class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_slider"
                       value="1"
                       {{ old('is_slider', isset($post) ? $post->is_slider : false) ? 'checked' : '' }}>
                <span class="form-check-label">Slider</span>
              </label>
              @error('is_slider')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col">
              <label class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_featured"
                       value="1"
                       {{ old('is_featured', isset($post) ? $post->is_featured : false) ? 'checked' : '' }}>
                <span class="form-check-label">Featured</span>
              </label>
              @error('is_featured')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col">
              <label class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_breaking"
                       value="1"
                       {{ old('is_breaking', isset($post) ? $post->is_breaking : false) ? 'checked' : '' }}>
                <span class="form-check-label">Breaking</span>
              </label>
              @error('is_breaking')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col">
              <label class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_recommended"
                       value="1"
                       {{ old('is_recommended', isset($post) ? $post->is_recommended : false) ? 'checked' : '' }}>
                <span class="form-check-label">Recommended</span>
              </label>
              @error('is_recommended')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Access & Permissions --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Access</h3>
        </div>
        <div class="card-body">
          <div class="mb-2">
            <label class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     name="registered_only"
                     value="1"
                     {{ old('registered_only', isset($post) ? $post->registered_only : false) ? 'checked' : '' }}>
              <span class="form-check-label">Registered Users Only</span>
            </label>
            @error('registered_only')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-2">
            <label class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     name="is_paid_only"
                     value="1"
                     {{ old('is_paid_only', isset($post) ? $post->is_paid_only : false) ? 'checked' : '' }}>
              <span class="form-check-label">Paid Users Only</span>
            </label>
            @error('is_paid_only')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-0">
            <label class="form-check form-switch">
              <input class="form-check-input"
                     type="checkbox"
                     name="allow_print_pdf"
                     value="1"
                     {{ old('allow_print_pdf', isset($post) ? $post->allow_print_pdf : false) ? 'checked' : '' }}>
              <span class="form-check-label">Allow Print / PDF</span>
            </label>
            @error('allow_print_pdf')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      {{-- Tags --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title mb-0">Tags</h3>
        </div>
        <div class="card-body">
          @if($tags->count() > 0)
            <select class="form-select select2" name="tags[]" multiple>
              @foreach($tags as $tag)
                <option value="{{ $tag->id }}"
                  {{ (isset($post) && $post->tags->contains($tag->id)) || (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'selected' : '' }}>
                  {{ $tag->name }}
                </option>
              @endforeach
            </select>
            <small class="form-hint">Select one or more tags for this post.</small>
          @else
            <p class="text-muted mb-0">No tags available. Please create tags first.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Scripts --}}
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // References
    const refContainer = document.getElementById('references-container');
    const addRefBtn = document.getElementById('add-reference');

    if (addRefBtn) {
      addRefBtn.addEventListener('click', function() {
        const index = refContainer.querySelectorAll('.reference-row').length;
        const template = `
          <div class="row g-2 mb-2 reference-row align-items-center">
            <div class="col-md-5">
              <input type="text"
                     name="references[${index}][title]"
                     class="form-control"
                     placeholder="Reference Title">
            </div>
            <div class="col-md-6">
              <input type="url"
                     name="references[${index}][url]"
                     class="form-control"
                     placeholder="Reference URL">
            </div>
            <div class="col-md-1 d-flex justify-content-end">
              <button type="button" class="btn btn-icon btn-danger remove-reference" title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
              </button>
            </div>
          </div>
        `;
        refContainer.insertAdjacentHTML('beforeend', template);
      });

      refContainer.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-reference');
        if (btn) {
          const row = btn.closest('.reference-row');
          if (row) row.remove();
        }
      });
    }

    // Attachments
    const attachmentContainer = document.getElementById('attachments-container');
    const addAttachmentBtn = document.getElementById('add-attachment');

    if (addAttachmentBtn) {
      addAttachmentBtn.addEventListener('click', function() {
        const template = `
          <div class="mb-3 attachment-row">
            <label class="form-label">Upload Files</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
            <div class="mt-1">
              <button type="button" class="btn btn-sm btn-danger remove-attachment">Remove</button>
            </div>
          </div>
        `;
        attachmentContainer.insertAdjacentHTML('beforeend', template);
      });

      attachmentContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-attachment')) {
          const row = e.target.closest('.attachment-row');
          if (row) row.remove();
        }
      });
    }

    // Image upload handler function (User's preferred method)
    function uploadImage(blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('image', blobInfo.blob(), blobInfo.filename());
            // formData.append('folder', 'contents'); // Not used in current controller, but kept for consistency if needed

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.blog.upload-image") }}');

            // Set CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
            } else {
                reject('CSRF token not found');
                return;
            }

            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            resolve(response.url);
                        } else {
                            reject(response.message || 'Upload failed');
                        }
                    } catch (e) {
                        reject('Invalid JSON response');
                    }
                } else {
                    reject('HTTP Error: ' + xhr.status);
                }
            };

            xhr.onerror = () => {
                reject('Image upload failed due to a network error.');
            };

            xhr.send(formData);
        });
    }

    // Initialize HugeRTE editors for content fields
    const contentEditors = document.querySelectorAll('.hugerte-editor');
    contentEditors.forEach(editor => {
      // Only initialize if not already initialized
      if (!editor.dataset.hugerteInitialized) {
        hugerte.init({
          selector: '#' + editor.id,
          height: 500,
          plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'anchor',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
          ],
          toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | image media | help',
          content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
          // Use the defined upload handler
          images_upload_handler: uploadImage
        });

        // Mark as initialized to prevent double initialization
        editor.dataset.hugerteInitialized = 'true';
      }
    });

    // Image compression and WebP conversion for featured image
    document.querySelector('input[name="image"]')?.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      // Check if the file is an image
      if (!file.type.match('image.*')) {
        alert('Please select a valid image file.');
        e.target.value = '';
        return;
      }

      // Use CompressorJS to compress the image
      new Compressor(file, {
        quality: 0.6, // Adjust quality as needed
        maxWidth: 1920, // Maximum width
        maxHeight: 1920, // Maximum height
        mimeType: 'image/webp', // Ensure output is WebP
        convertTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/gif'], // Types to convert
        success(result) {
          // Create a new File object from the processed result
          const webpFile = new File([result], file.name.replace(/\.\w+$/, '.webp'), {
            type: 'image/webp',
            lastModified: Date.now()
          });

          // Create a new DataTransfer object to hold the file
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(webpFile);

          // Replace the file in the input
          e.target.files = dataTransfer.files;

          console.log('Image compressed and converted to WebP successfully');
        },
        error(err) {
          console.error('Compression error:', err);
          alert('Failed to compress image. Please try a different file.');
          e.target.value = '';
        }
      });
    });
  });
</script>
