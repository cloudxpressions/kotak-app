@extends('admin.layouts.master')

@section('page-title', 'Create Newsletter')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.newsletters.index') }}">Newsletters</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create New Newsletter</h3>
      <p class="card-subtitle">Compose and schedule your newsletter campaign</p>
    </div>
  </div>
  <form action="{{ route('admin.system.newsletters.store') }}" method="POST">
    @csrf
    <div class="card-body">
      <div class="mb-3">
        <label for="subject" class="form-label required">Subject</label>
        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
               id="subject" name="subject" value="{{ old('subject') }}" required>
        @error('subject')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="body_html" class="form-label required">HTML Content</label>
        <textarea class="form-control @error('body_html') is-invalid @enderror" 
                  id="body_html" name="body_html" rows="15">{{ old('body_html') }}</textarea>
        @error('body_html')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="body_text" class="form-label">Plain Text Version (Optional)</label>
        <textarea class="form-control @error('body_text') is-invalid @enderror" 
                  id="body_text" name="body_text" rows="10">{{ old('body_text') ?? strip_tags(old('body_html', '')) }}</textarea>
        <small class="form-hint">Automatically generated from HTML if left empty</small>
        @error('body_text')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="status" class="form-label required">Status</label>
            <select class="form-select @error('status') is-invalid @enderror" 
                    id="status" name="status" required>
              <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="scheduled" {{ old('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6" id="schedule-section" style="display: none;">
          <div class="mb-3">
            <label for="scheduled_for" class="form-label">Schedule For</label>
            <input type="datetime-local" class="form-control @error('scheduled_for') is-invalid @enderror" 
                   id="scheduled_for" name="scheduled_for" value="{{ old('scheduled_for') }}">
            @error('scheduled_for')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Leave empty for immediate send after approval</small>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <div class="btn-list">
        <a href="{{ route('admin.system.newsletters.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Newsletter</button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Handle status change to show/hide schedule section
  $('#status').on('change', function() {
    if ($(this).val() === 'scheduled') {
      $('#schedule-section').show();
    } else {
      $('#schedule-section').hide();
    }
  });

  // Trigger initial state based on selected value
  $('#status').trigger('change');

  // Initialize TinyMCE for HTML editor if available
  if (typeof tinymce !== 'undefined') {
    tinymce.init({
      selector: '#body_html',
      height: 500,
      menubar: false,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
      ],
      toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help',
      content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif; font-size: 14px }'
    });
  }
});
</script>
@endpush