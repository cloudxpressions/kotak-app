@extends('admin.layouts.master')

@section('page-title', 'Email Settings')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item active" aria-current="page">Email Settings</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Current Email Configuration</h3>
      <p class="card-subtitle">Review your current email settings</p>
    </div>
  </div>
  <div class="card-body border-bottom py-3">
    <dl class="row">
      <dt class="col-sm-3">From Name:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_FROM_NAME'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">From Mail:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_FROM_ADDRESS'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">Mailer:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_MAILER'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">Mail Host:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_HOST'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">Mail Port:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_PORT'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">Username:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_USERNAME'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">Encryption:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_ENCRYPTION'] ?? 'N/A' }}</dd>

      <dt class="col-sm-3">API Key:</dt>
      <dd class="col-sm-9">{{ $mailConfig['MAIL_API_KEY'] ? '********' : 'N/A' }}</dd>

      <dt class="col-sm-3">Status:</dt>
      <dd class="col-sm-9">
        <span class="badge {{ $mailConfig['MAIL_ACTIVE_STATUS'] == '1' ? 'bg-success-lt' : 'bg-warning-lt' }}">
          {{ $mailConfig['MAIL_ACTIVE_STATUS'] == '1' ? 'Active' : 'Inactive' }}
        </span>
      </dd>
    </dl>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header">
    <div>
      <h3 class="card-title">Configure Email Settings</h3>
      <p class="card-subtitle">Update your email configuration</p>
    </div>
  </div>
  <div class="card-body">
    <form id="settings-form" action="{{ route('admin.system.email-settings.update') }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_FROM_NAME" class="form-label required">From Name</label>
            <input type="text" class="form-control @error('MAIL_FROM_NAME') is-invalid @enderror" 
                   id="MAIL_FROM_NAME" name="MAIL_FROM_NAME" 
                   value="{{ old('MAIL_FROM_NAME', $mailConfig['MAIL_FROM_NAME']) }}" 
                   required>
            @error('MAIL_FROM_NAME')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_FROM_ADDRESS" class="form-label required">From Email</label>
            <input type="email" class="form-control @error('MAIL_FROM_ADDRESS') is-invalid @enderror" 
                   id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS" 
                   value="{{ old('MAIL_FROM_ADDRESS', $mailConfig['MAIL_FROM_ADDRESS']) }}" 
                   required>
            @error('MAIL_FROM_ADDRESS')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_MAILER" class="form-label required">Mail Driver</label>
            <select class="form-select @error('MAIL_MAILER') is-invalid @enderror" 
                    id="MAIL_MAILER" name="MAIL_MAILER" required>
              <option value="smtp" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'smtp' ? 'selected' : '' }}>SMTP</option>
              <option value="sendmail" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'sendmail' ? 'selected' : '' }}>PHP Mail</option>
              <option value="mailgun" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
              <option value="ses" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
              <option value="postmark" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'postmark' ? 'selected' : '' }}>Postmark</option>
              <option value="log" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'log' ? 'selected' : '' }}>Log (for testing)</option>
              <option value="array" {{ old('MAIL_MAILER', $mailConfig['MAIL_MAILER']) == 'array' ? 'selected' : '' }}>Array (for testing)</option>
            </select>
            @error('MAIL_MAILER')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_ACTIVE_STATUS" class="form-label">Status</label>
            <select class="form-select @error('MAIL_ACTIVE_STATUS') is-invalid @enderror" 
                    id="MAIL_ACTIVE_STATUS" name="MAIL_ACTIVE_STATUS">
              <option value="1" {{ old('MAIL_ACTIVE_STATUS', $mailConfig['MAIL_ACTIVE_STATUS']) == '1' ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('MAIL_ACTIVE_STATUS', $mailConfig['MAIL_ACTIVE_STATUS']) == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('MAIL_ACTIVE_STATUS')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <!-- SMTP Settings (shown when SMTP is selected) -->
      <div id="smtp-settings" class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_HOST" class="form-label">Mail Host</label>
            <input type="text" class="form-control @error('MAIL_HOST') is-invalid @enderror" 
                   id="MAIL_HOST" name="MAIL_HOST" 
                   value="{{ old('MAIL_HOST', $mailConfig['MAIL_HOST']) }}">
            @error('MAIL_HOST')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_PORT" class="form-label">Mail Port</label>
            <input type="number" class="form-control @error('MAIL_PORT') is-invalid @enderror" 
                   id="MAIL_PORT" name="MAIL_PORT" 
                   value="{{ old('MAIL_PORT', $mailConfig['MAIL_PORT']) }}">
            @error('MAIL_PORT')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_USERNAME" class="form-label">Username</label>
            <input type="text" class="form-control @error('MAIL_USERNAME') is-invalid @enderror" 
                   id="MAIL_USERNAME" name="MAIL_USERNAME" 
                   value="{{ old('MAIL_USERNAME', $mailConfig['MAIL_USERNAME']) }}">
            @error('MAIL_USERNAME')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_PASSWORD" class="form-label">Password</label>
            <input type="password" class="form-control @error('MAIL_PASSWORD') is-invalid @enderror" 
                   id="MAIL_PASSWORD" name="MAIL_PASSWORD" 
                   value="{{ old('MAIL_PASSWORD', $mailConfig['MAIL_PASSWORD']) }}">
            @error('MAIL_PASSWORD')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-hint">Leave blank to keep existing password</small>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_ENCRYPTION" class="form-label">Encryption</label>
            <select class="form-select @error('MAIL_ENCRYPTION') is-invalid @enderror" 
                    id="MAIL_ENCRYPTION" name="MAIL_ENCRYPTION">
              <option value="" {{ old('MAIL_ENCRYPTION', $mailConfig['MAIL_ENCRYPTION']) == '' ? 'selected' : '' }}>None</option>
              <option value="tls" {{ old('MAIL_ENCRYPTION', $mailConfig['MAIL_ENCRYPTION']) == 'tls' ? 'selected' : '' }}>TLS</option>
              <option value="ssl" {{ old('MAIL_ENCRYPTION', $mailConfig['MAIL_ENCRYPTION']) == 'ssl' ? 'selected' : '' }}>SSL</option>
            </select>
            @error('MAIL_ENCRYPTION')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="MAIL_API_KEY" class="form-label">API Key (for services like Mailgun, SES, Postmark)</label>
            <input type="text" class="form-control @error('MAIL_API_KEY') is-invalid @enderror" 
                   id="MAIL_API_KEY" name="MAIL_API_KEY" 
                   value="{{ old('MAIL_API_KEY', $mailConfig['MAIL_API_KEY']) }}">
            @error('MAIL_API_KEY')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary">Update Email Settings</button>
        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset Form</button>
      </div>
    </form>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header">
    <div>
      <h3 class="card-title">Send Test Email</h3>
      <p class="card-subtitle">Verify your email configuration</p>
    </div>
  </div>
  <div class="card-body">
    <form id="test-mail-form">
      @csrf
      <div class="mb-3">
        <label for="to_mail" class="form-label required">Recipient Email</label>
        <input type="email" class="form-control" id="to_mail" name="to_mail" required>
      </div>
      <div class="mb-3">
        <label for="mail_engine" class="form-label required">Email Engine</label>
        <select class="form-select" id="mail_engine" name="mail_engine">
          <option value="smtp" selected>SMTP</option>
          <option value="sendmail">PHP Mail</option>
          <option value="mailgun">Mailgun</option>
          <option value="ses">Amazon SES</option>
          <option value="postmark">Postmark</option>
          <option value="log">Log (for testing)</option>
          <option value="array">Array (for testing)</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Send Test Email</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Toggle SMTP settings based on mail driver selection
  function toggleSmtpSettings() {
    const mailer = $('#MAIL_MAILER').val();
    if (mailer === 'smtp') {
      $('#smtp-settings').show();
    } else {
      $('#smtp-settings').hide();
    }
  }

  // Initial check
  toggleSmtpSettings();

  // Event listener for mail driver change
  $('#MAIL_MAILER').on('change', function() {
    toggleSmtpSettings();
  });

  // Handle test email form submission
  $('#test-mail-form').on('submit', function(e) {
    e.preventDefault();

    const formData = {
      to_mail: $('#to_mail').val(),
      mail_engine: $('#mail_engine').val(),
      _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
      url: '{{ route("admin.system.email-settings.send-test-mail") }}',
      method: 'POST',
      data: formData,
      success: function(response) {
        if (response.status === 'success') {
          Swal.fire({
            title: 'Success!',
            text: response.message,
            icon: 'success',
            timer: 3000,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error'
          });
        }
      },
      error: function(xhr) {
        let errorMessage = 'An error occurred while sending the test email.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        
        Swal.fire({
          title: 'Error!',
          text: errorMessage,
          icon: 'error'
        });
      }
    });
  });

  // Reset form function
  window.resetForm = function() {
    document.getElementById('settings-form').reset();
  };
});

// Function to show toast notifications
function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type} position-fixed bottom-0 end-0 m-3`;
  toast.style.zIndex = 9999;
  toast.innerHTML = `
    <div class="toast-header">
      <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
      ${message}
    </div>
  `;

  document.body.appendChild(toast);
  
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
  
  setTimeout(() => {
    bsToast.hide();
    toast.remove();
  }, 5000);
}
</script>
@endpush