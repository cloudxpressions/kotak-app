@extends('admin.layouts.master')

@section('page-title', 'AdMob Settings')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">System</a></li>
    <li class="breadcrumb-item active" aria-current="page">AdMob Settings</li>
</ol>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">AdMob Settings</h3>
            <p class="card-subtitle">Configure AdMob settings for your application</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.system.ad-mob-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">App ID</label>
                        <input type="text"
                               class="form-control @error('app_id') is-invalid @enderror"
                               name="app_id"
                               value="{{ old('app_id', $adMobSetting->app_id) }}"
                               placeholder="ca-app-pub-XXXXXXXXXXXXXXXX~YYYYYYYYYY">
                        @error('app_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Your AdMob App ID. Format: ca-app-pub-XXXXXXXXXXXXXXXX~YYYYYYYYYY</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Banner Ad Unit ID</label>
                        <input type="text"
                               class="form-control @error('banner_id') is-invalid @enderror"
                               name="banner_id"
                               value="{{ old('banner_id', $adMobSetting->banner_id) }}"
                               placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/YYYYYYYYYY">
                        @error('banner_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Ad Unit ID for Banner ads</small>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Interstitial Ad Unit ID</label>
                        <input type="text"
                               class="form-control @error('interstitial_id') is-invalid @enderror"
                               name="interstitial_id"
                               value="{{ old('interstitial_id', $adMobSetting->interstitial_id) }}"
                               placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/YYYYYYYYYY">
                        @error('interstitial_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Ad Unit ID for Interstitial ads</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Rewarded Ad Unit ID</label>
                        <input type="text"
                               class="form-control @error('rewarded_id') is-invalid @enderror"
                               name="rewarded_id"
                               value="{{ old('rewarded_id', $adMobSetting->rewarded_id) }}"
                               placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/YYYYYYYYYY">
                        @error('rewarded_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Ad Unit ID for Rewarded ads</small>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Native Ad Unit ID</label>
                        <input type="text"
                               class="form-control @error('native_id') is-invalid @enderror"
                               name="native_id"
                               value="{{ old('native_id', $adMobSetting->native_id) }}"
                               placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/YYYYYYYYYY">
                        @error('native_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-hint">Ad Unit ID for Native ads</small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_live" value="1" {{ old('is_live', $adMobSetting->is_live) ? 'checked' : '' }}>
                            <span class="form-check-label">Enable Live Ads</span>
                        </label>
                        <small class="form-hint">Toggle this to enable/disable live ads. Disable during testing to use test ads.</small>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection