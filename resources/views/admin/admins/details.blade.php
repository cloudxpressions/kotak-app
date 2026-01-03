@extends('admin.layouts.master')

@section('page-title', 'Admin Details - ' . $admin->name)

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admins</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ $admin->name }}</li>
</ol>
@endsection

@section('page-actions')
<div class="btn-list">
  <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
    Edit Basic Info
  </a>
</div>
@endsection

@section('content')
<div class="container-xl">
      <div class="card">
    <div class="row g-0">
      {{-- Left Sidebar Navigation --}}
      <div class="col-12 col-md-3 border-end">
        <div class="card-body">
          <h4 class="subheader">Profile</h4>
          <nav class="list-group list-group-transparent" id="managedAdminTabNav" role="tablist">
            <a class="list-group-item list-group-item-action d-flex align-items-center active"
              id="tab-personal-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-personal"
              role="tab"
              aria-controls="tab-personal"
              aria-selected="true">
              Personal Details
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center"
              id="tab-address-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-address"
              role="tab"
              aria-controls="tab-address"
              aria-selected="false">
              Address
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center"
              id="tab-family-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-family"
              role="tab"
              aria-controls="tab-family"
              aria-selected="false">
              Family
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center"
              id="tab-preferences-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-preferences"
              role="tab"
              aria-controls="tab-preferences"
              aria-selected="false">
              Preferences
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center"
              id="tab-social-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-social"
              role="tab"
              aria-controls="tab-social"
              aria-selected="false">
              Social Links
            </a>
            <a class="list-group-item list-group-item-action d-flex align-items-center"
              id="tab-payout-tab"
              data-bs-toggle="tab"
              data-bs-target="#tab-payout"
              role="tab"
              aria-controls="tab-payout"
              aria-selected="false">
              Payout Details
            </a>
          </nav>
          <h4 class="subheader mt-4">Additional</h4>
          <nav class="list-group list-group-transparent">
            <a href="#education-panel" class="list-group-item list-group-item-action d-flex align-items-center managed-admin-panel-link" data-panel-target="#education-panel">Education</a>
            <a href="#skills-panel" class="list-group-item list-group-item-action d-flex align-items-center managed-admin-panel-link" data-panel-target="#skills-panel">Skills</a>
          </nav>
        </div>
      </div>

      {{-- Right Content Area --}}
      <div class="col-12 col-md-9 d-flex flex-column">
        <div id="managedAdminFormContainer" class="d-flex flex-column flex-grow-1">
          <form action="{{ route('admin.admins.update-details', $admin->id) }}" method="POST" id="managedAdminForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-body">
              <div class="tab-content" id="managedAdminTabsContent">
            {{-- Personal Information Section --}}
            <div class="tab-pane fade show active" id="tab-personal" role="tabpanel" aria-labelledby="tab-personal-tab">
              <div class="mb-4">
                <h2 class="mb-1">Personal Information</h2>
                <p class="text-secondary mb-0">Basic identity details used across the platform.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label required">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $admin->name) }}" required>
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label required">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $admin->email) }}" required>
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Mobile</label>
                  <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile', $admin->mobile) }}">
                  @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">WhatsApp</label>
                  <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" name="whatsapp_number" value="{{ old('whatsapp_number', $admin->whatsapp_number) }}">
                  @error('whatsapp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Date of Birth</label>
                  <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob', $admin->dob ? $admin->dob->format('Y-m-d') : '') }}">
                  @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Gender</label>
                  <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender', $admin->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $admin->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $admin->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" name="is_differently_abled" id="is_differently_abled" value="1" {{ old('is_differently_abled', $admin->is_differently_abled) ? 'checked' : '' }}>
                    <span class="form-check-label">Differently Abled?</span>
                  </label>
                </div>
                <div class="col-md-6 d-none" id="da_category_div">
                  <label class="form-label">DA Category</label>
                  <select class="form-select select2" name="d_a_category_id">
                    <option value="">Select DA Category</option>
                    @foreach($daCategories as $dac)
                      <option value="{{ $dac->id }}" {{ old('d_a_category_id', $admin->d_a_category_id) == $dac->id ? 'selected' : '' }}>{{ $dac->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label class="form-label">Short Bio</label>
                  <input type="text" class="form-control @error('short_bio') is-invalid @enderror" name="short_bio" value="{{ old('short_bio', $admin->short_bio) }}">
                  @error('short_bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label class="form-label">Bio</label>
                  <textarea class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio', $admin->bio) }}</textarea>
                  @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label class="form-label">Profile Image</label>
                  <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                  <small class="form-hint">Upload a profile picture. Accepted formats: jpeg, png, jpg, gif, webp. Max size: 10MB.</small>
                  @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                  @if($admin->image)
                  <div class="mt-2">
                    <label class="form-check">
                      <input type="checkbox" class="form-check-input" name="delete_current_image" value="1">
                      <span class="form-check-label">Delete current image</span>
                    </label>
                    <div class="mt-2">
                      <img src="{{ asset('storage/' . $admin->image) }}" alt="Current Profile Image" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>

            {{-- Address Details Section --}}
            <div class="tab-pane fade" id="tab-address" role="tabpanel" aria-labelledby="tab-address-tab">
              <div class="mb-4">
                <h2 class="mb-1">Address Details</h2>
                <p class="text-secondary mb-0">Let us know where we should reach you.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label">Address</label>
                  <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2">{{ old('address', $admin->address) }}</textarea>
                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Locality</label>
                  <input type="text" class="form-control @error('locality') is-invalid @enderror" name="locality" value="{{ old('locality', $admin->locality) }}">
                  @error('locality') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pincode</label>
                  <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" value="{{ old('pincode', $admin->pincode) }}">
                  @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-4">
                  <label class="form-label">Country</label>
                  <select class="form-select select2 @error('country_id') is-invalid @enderror" name="country_id" id="country_id">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                      <option value="{{ $country->id }}" {{ old('country_id', $admin->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                  </select>
                  @error('country_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">State</label>
                  <select class="form-select select2 @error('state_id') is-invalid @enderror" name="state_id" id="state_id">
                    <option value="">Select State</option>
                    @foreach($states as $state)
                      <option value="{{ $state->id }}" {{ old('state_id', $admin->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                  </select>
                  @error('state_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">City</label>
                  <select class="form-select select2 @error('city_id') is-invalid @enderror" name="city_id" id="city_id">
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                      <option value="{{ $city->id }}" {{ old('city_id', $admin->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                  </select>
                  @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Aadhaar Number</label>
                  <input type="text" class="form-control @error('aadhaar_number') is-invalid @enderror" name="aadhaar_number" value="{{ old('aadhaar_number', $admin->aadhaar_number) }}">
                  @error('aadhaar_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- Family Details Section --}}
            <div class="tab-pane fade" id="tab-family" role="tabpanel" aria-labelledby="tab-family-tab">
              <div class="mb-4">
                <h2 class="mb-1">Family Details</h2>
                <p class="text-secondary mb-0">Share guardian details for emergency communication.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Father's Name</label>
                  <input type="text" class="form-control @error('fathers_name') is-invalid @enderror" name="fathers_name" value="{{ old('fathers_name', $admin->fathers_name) }}">
                  @error('fathers_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Mother's Name</label>
                  <input type="text" class="form-control @error('mothers_name') is-invalid @enderror" name="mothers_name" value="{{ old('mothers_name', $admin->mothers_name) }}">
                  @error('mothers_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Parent Mobile</label>
                  <input type="text" class="form-control @error('parent_mobile_number') is-invalid @enderror" name="parent_mobile_number" value="{{ old('parent_mobile_number', $admin->parent_mobile_number) }}">
                  @error('parent_mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- Preferences & Classifications Section --}}
            <div class="tab-pane fade" id="tab-preferences" role="tabpanel" aria-labelledby="tab-preferences-tab">
              <div class="mb-4">
                <h2 class="mb-1">Preferences & Classifications</h2>
                <p class="text-secondary mb-0">Control locale options and how we categorize your account.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label">Language</label>
                  <select class="form-select select2" name="language_id">
                    <option value="">Select Language</option>
                    @foreach($languages as $lang)
                      <option value="{{ $lang->id }}" {{ old('language_id', $admin->language_id) == $lang->id ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Time Zone</label>
                  <select class="form-select select2" name="timezone_id">
                    <option value="">Select Time Zone</option>
                    @foreach($timeZones as $tz)
                      <option value="{{ $tz->id }}" {{ old('timezone_id', $admin->timezone_id) == $tz->id ? 'selected' : '' }}>{{ $tz->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Currency</label>
                  <select class="form-select select2" name="currency_id">
                    <option value="">Select Currency</option>
                    @foreach($currencies as $curr)
                      <option value="{{ $curr->id }}" {{ old('currency_id', $admin->currency_id) == $curr->id ? 'selected' : '' }}>{{ $curr->name }} ({{ $curr->symbol }})</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Date Format</label>
                  <select class="form-select select2" name="dateformat_id">
                    <option value="">Select Date Format</option>
                    @foreach($dateFormats as $df)
                      <option value="{{ $df->id }}" {{ old('dateformat_id', $admin->dateformat_id) == $df->id ? 'selected' : '' }}>{{ $df->format }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-3">
                  <label class="form-label">Religion</label>
                  <select class="form-select select2" name="religion_id">
                    <option value="">Select Religion</option>
                    @foreach($religions as $rel)
                      <option value="{{ $rel->id }}" {{ old('religion_id', $admin->religion_id) == $rel->id ? 'selected' : '' }}>{{ $rel->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Community</label>
                  <select class="form-select select2" name="community_id">
                    <option value="">Select Community</option>
                    @foreach($communities as $comm)
                      <option value="{{ $comm->id }}" {{ old('community_id', $admin->community_id) == $comm->id ? 'selected' : '' }}>{{ $comm->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label">User Classification</label>
                  <select class="form-select select2" name="user_classifications_id">
                    <option value="">Select Classification</option>
                    @foreach($userClassifications as $uc)
                      <option value="{{ $uc->id }}" {{ old('user_classifications_id', $admin->user_classifications_id) == $uc->id ? 'selected' : '' }}>{{ $uc->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            {{-- Social Links Section --}}
            <div class="tab-pane fade" id="tab-social" role="tabpanel" aria-labelledby="tab-social-tab">
              <div class="mb-4">
                <h2 class="mb-1">Social Links</h2>
                <p class="text-secondary mb-0">Connect your social presence with the admin console.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Facebook</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg>
                    </span>
                    <input type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook', $admin->facebook) }}" placeholder="https://facebook.com/username">
                  </div>
                  @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Twitter</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" /></svg>
                    </span>
                    <input type="url" class="form-control @error('twitter') is-invalid @enderror" name="twitter" value="{{ old('twitter', $admin->twitter) }}" placeholder="https://twitter.com/username">
                  </div>
                  @error('twitter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">LinkedIn</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                    </span>
                    <input type="url" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin', $admin->linkedin) }}" placeholder="https://linkedin.com/in/username">
                  </div>
                  @error('linkedin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>

            {{-- Payout Details Section --}}
            <div class="tab-pane fade" id="tab-payout" role="tabpanel" aria-labelledby="tab-payout-tab">
              <div class="mb-4">
                <h2 class="mb-1">Payout Details</h2>
                <p class="text-secondary mb-0">Payment preferences for honorariums and reimbursements.</p>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Payout Email</label>
                  <input type="email" class="form-control @error('payout_email') is-invalid @enderror" name="payout_email" value="{{ old('payout_email', $admin->payout_email) }}">
                  @error('payout_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Payout Icon (URL)</label>
                  <input type="text" class="form-control @error('payout_icon') is-invalid @enderror" name="payout_icon" value="{{ old('payout_icon', $admin->payout_icon) }}">
                  @error('payout_icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Payout Amount</label>
                  <input type="number" step="0.01" class="form-control @error('payout') is-invalid @enderror" name="payout" value="{{ old('payout', $admin->payout) }}">
                  @error('payout') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
              </div>
            </div>

            <div class="card-footer bg-transparent mt-auto">
              <div class="btn-list justify-content-end">
                <button type="submit" class="btn btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                  Save Details
                </button>
              </div>
            </div>
          </form>
        </div>

        {{-- Education Section --}}
        <div id="education-panel" class="card-body border-top managed-admin-panel d-none">
          <h2 class="mb-4">Education</h2>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-secondary mb-0">Maintain the education timeline for this admin.</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              Add Education
            </button>
          </div>

          @if($admin->education->count() > 0)
            <div class="table-responsive">
              <table class="table table-vcenter">
                <thead>
                  <tr>
                    <th>Qualification</th>
                    <th>Year</th>
                    <th>Medium</th>
                    <th class="w-1">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($admin->education as $edu)
                    <tr>
                      <td>{{ $edu->qualification }}</td>
                      <td>{{ $edu->year_of_passing }}</td>
                      <td>{{ $edu->medium }}</td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-icon btn-primary edit-education-btn"
                          data-id="{{ $edu->id }}"
                          data-qualification="{{ $edu->qualification }}"
                          data-year="{{ $edu->year_of_passing }}"
                          data-medium="{{ $edu->medium }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                          <span class="visually-hidden">Edit</span>
                        </button>
                        <form action="{{ route('admin.admins.delete-education', [$admin->id, $edu->id]) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-icon btn-danger ms-1" title="Delete education">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            <span class="visually-hidden">Delete</span>
                          </button>
                        </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="empty">
              <p class="empty-title">No education records</p>
              <p class="empty-subtitle text-muted">Add education records for this admin.</p>
              <div class="empty-action">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">Add first education</button>
              </div>
            </div>
          @endif
        </div>

        {{-- Skills Section --}}
        <div id="skills-panel" class="card-body border-top managed-admin-panel d-none">
          <h2 class="mb-4">Skills</h2>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-secondary mb-0">Highlight the strengths this admin can help with.</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              Add Skill
            </button>
          </div>

          @if($admin->skills->count() > 0)
            <div class="table-responsive">
              <table class="table table-vcenter">
                <thead>
                  <tr>
                    <th>Skill Name</th>
                    <th>Proficiency</th>
                    <th>Description</th>
                    <th class="w-1">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($admin->skills as $skill)
                    @php
                      $badges = [
                        'Beginner' => 'bg-secondary-lt',
                        'Intermediate' => 'bg-info-lt',
                        'Advanced' => 'bg-primary-lt',
                        'Expert' => 'bg-success-lt',
                        'Master' => 'bg-warning-lt',
                      ];
                      $class = $badges[$skill->proficiency_level] ?? 'bg-secondary-lt';
                    @endphp
                    <tr>
                      <td>{{ $skill->skill_name }}</td>
                      <td><span class="badge {{ $class }}">{{ $skill->proficiency_level }}</span></td>
                      <td>{{ $skill->description }}</td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-icon btn-primary edit-skill-btn"
                          data-id="{{ $skill->id }}"
                          data-name="{{ $skill->skill_name }}"
                          data-level="{{ $skill->proficiency_level }}"
                          data-description="{{ $skill->description }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                          <span class="visually-hidden">Edit</span>
                        </button>
                        <form action="{{ route('admin.admins.delete-skill', [$admin->id, $skill->id]) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-icon btn-danger ms-1 delete-skill-btn" title="Delete skill">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            <span class="visually-hidden">Delete</span>
                          </button>
                        </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="empty">
              <p class="empty-title">No skills added</p>
              <p class="empty-subtitle text-muted">Add skills for this admin.</p>
              <div class="empty-action">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">Add first skill</button>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Add Education Modal --}}
<div class="modal fade" id="addEducationModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.admins.add-education', $admin->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Education</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Qualification</label>
            <input type="text" class="form-control" name="qualification" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Year of Passing</label>
            <input type="number" class="form-control" name="year_of_passing" min="1900" max="{{ date('Y') + 10 }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Medium</label>
            <input type="text" class="form-control" name="medium" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Education</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Education Modal --}}
<div class="modal fade" id="editEducationModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editEducationForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Education</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Qualification</label>
            <input type="text" class="form-control" name="qualification" id="edit_qualification" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Year of Passing</label>
            <input type="number" class="form-control" name="year_of_passing" id="edit_year" min="1900" max="{{ date('Y') + 10 }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Medium</label>
            <input type="text" class="form-control" name="medium" id="edit_medium" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Education</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Add Skill Modal --}}
<div class="modal fade" id="addSkillModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.admins.add-skill', $admin->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Skill Name</label>
            <input type="text" class="form-control" name="skill_name" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Proficiency Level</label>
            <select class="form-select" name="proficiency_level" required>
              <option value="">Select Level</option>
              @foreach($proficiencyLevels as $level)
                <option value="{{ $level }}">{{ $level }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Skill Modal --}}
<div class="modal fade" id="editSkillModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSkillForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Skill Name</label>
            <input type="text" class="form-control" name="skill_name" id="edit_skill_name" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Proficiency Level</label>
            <select class="form-select" name="proficiency_level" id="edit_proficiency_level" required>
              @foreach($proficiencyLevels as $level)
                <option value="{{ $level }}">{{ $level }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="edit_skill_description" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Initialize Select2
  $('.select2').select2({
    theme: 'bootstrap-5',
    width: '100%'
  });

  // Dynamic Location Loading
  $('#country_id').on('change', function() {
    const countryId = this.value;
    $('#state_id').html('<option value="">Select State</option>');
    $('#city_id').html('<option value="">Select City</option>');

    if (countryId) {
      $.get("{{ route('admin.system.cities.get-states-by-country', ':id') }}".replace(':id', countryId), function(data) {
        data.forEach(state => {
          $('#state_id').append(new Option(state.name, state.id));
        });
      });
    }
  });

  $('#state_id').on('change', function() {
    const stateId = this.value;
    $('#city_id').html('<option value="">Select City</option>');

    if (stateId) {
      $.get("{{ route('admin.system.cities.get-cities-by-state', ':id') }}".replace(':id', stateId), function(data) {
        data.forEach(city => {
          $('#city_id').append(new Option(city.name, city.id));
        });
      });
    }
  });

  const formContainer = $('#managedAdminFormContainer');
  const tabLinks = $('#managedAdminTabNav [data-bs-toggle="tab"]');
  const panelLinks = $('.managed-admin-panel-link');
  const additionalPanels = $('.managed-admin-panel');

  tabLinks.on('shown.bs.tab', function () {
    panelLinks.removeClass('active');
    additionalPanels.addClass('d-none');
    formContainer.removeClass('d-none');
  });

  panelLinks.on('click', function(e) {
    e.preventDefault();
    const targetSelector = $(this).data('panel-target');
    const targetPanel = $(targetSelector);
    if (!targetPanel.length) {
      return;
    }

    panelLinks.removeClass('active');
    $(this).addClass('active');

    tabLinks.each(function() {
      this.classList.remove('active');
      this.setAttribute('aria-selected', 'false');
    });
    $('#managedAdminTabsContent .tab-pane').removeClass('show active');

    formContainer.addClass('d-none');
    additionalPanels.addClass('d-none');
    targetPanel.removeClass('d-none');

    $('html, body').stop().animate({
      scrollTop: targetPanel.offset().top - 90
    }, 400);
  });

  // Auto-focus tab containing first error
  const firstInvalid = $('#managedAdminForm .is-invalid').first();
  if (firstInvalid.length) {
    const pane = firstInvalid.closest('.tab-pane');
    if (pane.length) {
      const targetId = `#${pane.attr('id')}`;
      const navTrigger = document.querySelector(`[data-bs-target="${targetId}"]`);
      const bs = window.bootstrap;
      if (navTrigger && bs && bs.Tab) {
        bs.Tab.getOrCreateInstance(navTrigger).show();
      } else if (navTrigger) {
        navTrigger?.click();
      }
      formContainer.removeClass('d-none');
      additionalPanels.addClass('d-none');
      $('html, body').animate({
        scrollTop: pane.offset().top - 120
      }, 500);
    }
  }

  const educationModalEl = document.getElementById('editEducationModal');
  const skillModalEl = document.getElementById('editSkillModal');

  $(document).on('click', '.edit-education-btn', function() {
    const id = $(this).data('id');
    $('#edit_qualification').val($(this).data('qualification'));
    $('#edit_year').val($(this).data('year'));
    $('#edit_medium').val($(this).data('medium'));
    $('#editEducationForm').attr('action', '{{ route("admin.admins.update-education", [$admin->id, ":id"]) }}'.replace(':id', id));
    if (educationModalEl && window.bootstrap && bootstrap.Modal) {
      bootstrap.Modal.getOrCreateInstance(educationModalEl).show();
    }
  });

  $(document).on('click', '.edit-skill-btn', function() {
    const id = $(this).data('id');
    $('#edit_skill_name').val($(this).data('name'));
    $('#edit_proficiency_level').val($(this).data('level'));
    $('#edit_skill_description').val($(this).data('description'));
    $('#editSkillForm').attr('action', '{{ route("admin.admins.update-skill", [$admin->id, ":id"]) }}'.replace(':id', id));
    if (skillModalEl && window.bootstrap && bootstrap.Modal) {
      bootstrap.Modal.getOrCreateInstance(skillModalEl).show();
    }
  });

  // DA Category Toggle
  const daCheckbox = $('#is_differently_abled');
  const daContainer = $('#da_category_div');

  function toggleDaCategory() {
    if (daCheckbox.is(':checked')) {
      daContainer.removeClass('d-none');
    } else {
      daContainer.addClass('d-none');
    }
  }

  daCheckbox.on('change', toggleDaCategory);
  toggleDaCategory(); // Run on load

  // Image preview and WebP conversion functionality
  document.querySelector('input[name="image"]')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Check if the file is an image
    if (!file.type.match('image.*')) {
      return;
    }

    // Create preview of the selected image
    const reader = new FileReader();
    reader.onload = function(event) {
      // Find any existing preview container and remove it
      const existingPreview = document.querySelector('.image-preview-container');
      if (existingPreview) {
        existingPreview.remove();
      }

      // Create a new preview container
      const previewContainer = document.createElement('div');
      previewContainer.className = 'image-preview-container mt-2';

      const previewImg = document.createElement('img');
      previewImg.src = event.target.result;
      previewImg.alt = 'Preview';
      previewImg.className = 'img-thumbnail';
      previewImg.style.maxHeight = '200px';

      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'btn btn-sm btn-danger mt-1';
      removeBtn.innerHTML = 'Remove Preview';
      removeBtn.onclick = function() {
        previewContainer.remove();
        e.target.value = ''; // Clear the file input
      };

      previewContainer.appendChild(previewImg);
      previewContainer.appendChild(removeBtn);

      // Insert the preview after the file input
      e.target.parentNode.insertBefore(previewContainer, e.target.nextSibling);
    };
    reader.readAsDataURL(file);

    // Use CompressorJS to compress the image
    new Compressor(file, {
      quality: 0.6, // Adjust quality as needed
      maxWidth: 1920, // Maximum width
      maxHeight: 1920, // Maximum height
      mimeType: 'image/webp', // Ensure output is WebP
      convertTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/gif'], // Types to convert
      success(result) {
        // The result from CompressorJS will already be in proper format
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
      },
      error(err) {
        console.error('Compression error:', err);
        // If compression fails, continue with the original file
      }
    });
  });
});
</script>
@endpush
