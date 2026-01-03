@extends('admin.layouts.master')

@section('page-title', 'reCAPTCHA Settings')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">System</a></li>
    <li class="breadcrumb-item active" aria-current="page">reCAPTCHA Settings</li>
</ol>
@endsection

@section('content')
<div class="alert alert-info">
    <div class="d-flex">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
            <path d="M12 9h.01" />
            <path d="M11 12h1v4h1" />
        </svg>
        <div>
            <strong>Information!</strong> Configure your Google reCAPTCHA settings here. You can get your keys from the <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin Console</a>.
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">reCAPTCHA Configuration</h3>
            <p class="card-subtitle">Configure Google reCAPTCHA for enhanced security</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.system.recaptcha-setting.store') }}" method="POST">
            @csrf
            @method('POST')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Site Key</label>
                        <input type="text" class="form-control @error('site_key') is-invalid @enderror" 
                               name="site_key" value="{{ old('site_key', $setting->site_key) }}" 
                               placeholder="Enter your reCAPTCHA site key">
                        @error('site_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">This is your public site key for the reCAPTCHA widget</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Secret Key</label>
                        <input type="password" class="form-control @error('secret_key') is-invalid @enderror" 
                               name="secret_key" 
                               placeholder="Enter your reCAPTCHA secret key (leave blank to keep existing)">
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">This is your private secret key. Leave blank to keep the existing value.</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_enabled" 
                           value="1" {{ old('is_enabled', $setting->is_enabled) ? 'checked' : '' }}>
                    <span class="form-check-label">Enable reCAPTCHA</span>
                </label>
                <small class="form-hint">Turn this on to activate reCAPTCHA protection</small>
            </div>

            <div class="mb-3">
                <label class="form-label">reCAPTCHA Version</label>
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="version" value="v2_checkbox" 
                               class="form-selectgroup-input" 
                               {{ old('version', $setting->version) === 'v2_checkbox' ? 'checked' : '' }}>
                        <span class="form-selectgroup-label">reCAPTCHA v2 Checkbox</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="version" value="v2_invisible" 
                               class="form-selectgroup-input" 
                               {{ old('version', $setting->version) === 'v2_invisible' ? 'checked' : '' }}>
                        <span class="form-selectgroup-label">reCAPTCHA v2 Invisible</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="version" value="v3" 
                               class="form-selectgroup-input" 
                               {{ old('version', $setting->version) === 'v3' ? 'checked' : '' }}>
                        <span class="form-selectgroup-label">reCAPTCHA v3</span>
                    </label>
                </div>
                <small class="form-hint">Choose the version of reCAPTCHA to use</small>
            </div>

            <div class="mb-3 v3-config" style="{{ old('version', $setting->version) === 'v3' ? '' : 'display: none;' }}">
                <label class="form-label">v3 Score Threshold</label>
                <input type="number" step="0.01" min="0" max="1" class="form-control @error('v3_score_threshold') is-invalid @enderror" 
                       name="v3_score_threshold" value="{{ old('v3_score_threshold', $setting->v3_score_threshold) }}" 
                       placeholder="0.5">
                @error('v3_score_threshold')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-hint">Score threshold for reCAPTCHA v3 (0.0 to 1.0, where 1.0 is very likely legitimate)</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Enable for:</label>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="captcha_for_login" 
                                   value="1" {{ old('captcha_for_login', $setting->captcha_for_login) ? 'checked' : '' }}>
                            <span class="form-check-label">Login</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="captcha_for_register" 
                                   value="1" {{ old('captcha_for_register', $setting->captcha_for_register) ? 'checked' : '' }}>
                            <span class="form-check-label">Register</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="captcha_for_contact" 
                                   value="1" {{ old('captcha_for_contact', $setting->captcha_for_contact) ? 'checked' : '' }}>
                            <span class="form-check-label">Contact Form</span>
                        </label>
                    </div>
                </div>
                <small class="form-hint">Select where you want to enable reCAPTCHA protection</small>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide v3 config when version changes
    $('input[name="version"]').on('change', function() {
        if ($(this).val() === 'v3') {
            $('.v3-config').show();
        } else {
            $('.v3-config').hide();
        }
    });
    
    // Initialize on page load
    if($('input[name="version"]:checked').val() === 'v3') {
        $('.v3-config').show();
    } else {
        $('.v3-config').hide();
    }
});
</script>
@endpush