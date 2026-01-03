@extends('admin.layouts.master')

@section('page-title', 'Add Testimonial')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Testimonials</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('page-actions')
<a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">
  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
    <path d="M15 12h-10" />
    <path d="M8 9l-3 3l3 3" />
  </svg>
  Back to Testimonials
</a>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Add New Testimonial</h3>
      <p class="card-subtitle">Create a new customer testimonial</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label required">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Name of the person giving the testimonial</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Designation</label>
            <input type="text" class="form-control @error('designation') is-invalid @enderror" 
                   name="designation" value="{{ old('designation') }}">
            @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Job title or position of the person</small>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-12">
          <div class="mb-3">
            <label class="form-label required">Message</label>
            <textarea class="form-control @error('message') is-invalid @enderror" 
                      name="message" rows="5" required>{{ old('message') }}</textarea>
            @error('message')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">The testimonial content</small>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Avatar</label>
            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                   name="avatar" accept="image/*">
            @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Upload a profile image. Accepted formats: jpeg, png, jpg, gif, webp. Max size: 10MB.</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label required">Rating</label>
            <select class="form-select @error('rating') is-invalid @enderror" name="rating" required>
              <option value="">Select Rating</option>
              @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                  {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                </option>
              @endfor
            </select>
            @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Choose a rating from 1 to 5 stars</small>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                   name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
            @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Position for sorting (lower numbers appear first)</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
              <span class="form-check-label">Visible on Website</span>
            </label>
            <small class="form-hint">Toggle to make this testimonial visible on the website</small>
          </div>
        </div>
      </div>

      <div class="text-end">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Testimonial</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Image preview functionality
  $('input[name="avatar"]').on('change', function() {
    const file = this.files[0];
    if (!file) return;
    
    if (!file.type.match('image.*')) {
      return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
      // Remove any existing preview
      $('.image-preview-container').remove();
      
      // Create a preview container
      const previewContainer = $('<div class="image-preview-container mt-2"></div>');
      
      const previewImg = $('<img class="img-thumbnail" style="max-height: 150px;">').attr('src', e.target.result);
      const removeBtn = $('<button type="button" class="btn btn-sm btn-danger mt-1">Remove Preview</button>').on('click', function() {
        previewContainer.remove();
        $('input[name="avatar"]').val('');
      });
      
      previewContainer.append(previewImg).append(removeBtn);
      $('input[name="avatar"]').after(previewContainer);
    };
    
    reader.readAsDataURL(file);
  });
});
</script>
@endpush