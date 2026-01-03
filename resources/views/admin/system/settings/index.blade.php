@extends('admin.layouts.master')

@section('page-title', 'System Settings')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Settings</li>
</ol>
@endsection

@section('content')
<div class="alert alert-info">
  <div class="d-flex">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
      <path d="M12 8v4" />
      <path d="M12 16h.01" />
    </svg>
    <div>
      <strong>Information!</strong> Update system-wide settings for your application.
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">System Settings</h3>
      <p class="card-subtitle">Manage global application settings</p>
    </div>
  </div>
  <form action="{{ route('admin.system.settings.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="app_name" class="form-label required">Application Name</label>
            <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                   id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
            @error('app_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="app_tagline" class="form-label">Application Tagline</label>
            <input type="text" class="form-control @error('app_tagline') is-invalid @enderror" 
                   id="app_tagline" name="app_tagline" value="{{ old('app_tagline', $settings['app_tagline']) }}">
            @error('app_tagline')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                   id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}">
            @error('company_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="company_email" class="form-label">Company Email</label>
            <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                   id="company_email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}">
            @error('company_email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="company_phone" class="form-label">Company Phone</label>
            <input type="tel" class="form-control @error('company_phone') is-invalid @enderror" 
                   id="company_phone" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}">
            @error('company_phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="company_address" class="form-label">Company Address</label>
            <textarea class="form-control @error('company_address') is-invalid @enderror" 
                      id="company_address" name="company_address" rows="3">{{ old('company_address', $settings['company_address']) }}</textarea>
            @error('company_address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="mb-3">
        <label for="copyright_text" class="form-label">Copyright Text</label>
        <input type="text" class="form-control @error('copyright_text') is-invalid @enderror" 
               id="copyright_text" name="copyright_text" value="{{ old('copyright_text', $settings['copyright_text']) }}">
        @error('copyright_text')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="app_theme" class="form-label">Application Theme</label>
            <select class="form-select @error('app_theme') is-invalid @enderror" 
                    id="app_theme" name="app_theme">
              <option value="default" {{ old('app_theme', $settings['app_theme']) === 'default' ? 'selected' : '' }}>Default</option>
              <option value="dark" {{ old('app_theme', $settings['app_theme']) === 'dark' ? 'selected' : '' }}>Dark</option>
              <option value="light" {{ old('app_theme', $settings['app_theme']) === 'light' ? 'selected' : '' }}>Light</option>
            </select>
            @error('app_theme')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="email_from_address" class="form-label">Email From Address</label>
            <input type="email" class="form-control @error('email_from_address') is-invalid @enderror" 
                   id="email_from_address" name="email_from_address" 
                   value="{{ old('email_from_address', $settings['email_from_address']) }}">
            @error('email_from_address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="maintenance_mode" 
                     value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
              <span class="form-check-label">Maintenance Mode</span>
            </label>
            <div class="form-hint">Turn on maintenance mode to temporarily disable public access</div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="enable_registration" 
                     value="1" {{ old('enable_registration', $settings['enable_registration']) ? 'checked' : '' }}>
              <span class="form-check-label">Enable Registration</span>
            </label>
            <div class="form-hint">Allow new users to register</div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <div class="btn-list">
        <button type="reset" class="btn btn-outline-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">Update Settings</button>
      </div>
    </div>
  </form>
</div>
@endsection