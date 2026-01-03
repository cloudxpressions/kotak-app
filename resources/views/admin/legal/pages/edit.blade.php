@extends('admin.layouts.master')

@section('page-title', 'Edit Legal Page - ' . $legalPage->title)

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.legal.pages.index') }}">Legal Pages</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit - {{ $legalPage->title }}</li>
</ol>
@endsection

@section('page-actions')
<a href="{{ route('admin.legal.pages.index') }}" class="btn btn-secondary">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M15 12h-10" />
    <path d="M8 9l-3 3l3 3" />
  </svg>
  Back to Legal Pages
</a>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Legal Page</h3>
      <p class="card-subtitle">Update legal page: {{ $legalPage->title }}</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.legal.pages.update', $legalPage->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label required">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   name="title" value="{{ old('title', $legalPage->title) }}" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Enter a title for the legal page (e.g., Terms of Service, Privacy Policy)</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Status</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $legalPage->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
            <small class="form-hint">Toggle to enable or disable this legal page</small>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea class="form-control @error('content') is-invalid @enderror" 
                  name="content" rows="10">{{ old('content', $legalPage->content) }}</textarea>
        @error('content')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-hint">Main content of the legal page. Supports HTML formatting.</small>
      </div>

      <!-- SEO Section -->
      <div class="card mb-3">
        <div class="card-header">
          <h4 class="card-title mb-0">SEO Settings</h4>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">SEO Title</label>
                <input type="text" class="form-control @error('seo_title') is-invalid @enderror" 
                       name="seo_title" value="{{ old('seo_title', $legalPage->seo_title) }}">
                @error('seo_title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Title for search engines (recommended: 50-60 characters)</small>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">SEO Description</label>
                <textarea class="form-control @error('seo_description') is-invalid @enderror" 
                          name="seo_description" rows="3">{{ old('seo_description', $legalPage->seo_description) }}</textarea>
                @error('seo_description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Description for search engines (recommended: 150-160 characters)</small>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">SEO Keywords</label>
                <input type="text" class="form-control @error('seo_keywords') is-invalid @enderror" 
                       name="seo_keywords" value="{{ old('seo_keywords', $legalPage->seo_keywords) }}" 
                       placeholder="keyword1, keyword2, keyword3">
                @error('seo_keywords')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Comma-separated keywords for better search visibility</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-end">
        <a href="{{ route('admin.legal.pages.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update Legal Page</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
$(document).ready(function() {
    // Initialize CKEditor for content field
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('textarea[name="content"]'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
@endpush