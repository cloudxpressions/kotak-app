@extends('admin.layouts.master')

@section('page-title', 'Activate States')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.states.index') }}">States</a></li>
  <li class="breadcrumb-item active" aria-current="page">Activate States</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Bulk Activate/Deactivate States by Country</h3>
    <p class="card-subtitle">Select countries to activate or deactivate all their states</p>
  </div>
  <div class="card-body">
    <form id="activation-form">
      @csrf
      <div class="row g-3">
        @foreach($countries as $country)
        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                  <span class="avatar avatar-md" style="font-size: 2rem;">{{ $country->emoji_flag }}</span>
                </div>
                <div class="flex-fill">
                  <div class="font-weight-medium">{{ $country->name }}</div>
                  <div class="text-muted small">
                    <span class="badge bg-success-lt">{{ $country->active_states_count }}</span> of 
                    <span class="badge bg-secondary-lt">{{ $country->states_count }}</span> states active
                  </div>
                </div>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input country-checkbox" type="checkbox" 
                       id="country_{{ $country->id }}" 
                       value="{{ $country->id }}"
                       data-country-name="{{ $country->name }}"
                       data-states-count="{{ $country->states_count }}"
                       {{ $country->active_states_count > 0 ? 'checked' : '' }}>
                <label class="form-check-label" for="country_{{ $country->id }}">
                  {{ $country->active_states_count > 0 ? 'Active' : 'Inactive' }}
                </label>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      @if($countries->isEmpty())
      <div class="empty">
        <div class="empty-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
        </div>
        <p class="empty-title">No countries found</p>
        <p class="empty-subtitle text-muted">
          There are no countries in the system yet.
        </p>
      </div>
      @endif
    </form>
  </div>
  @if($countries->isNotEmpty())
  <div class="card-footer">
    <div class="d-flex justify-content-between align-items-center">
      <a href="{{ route('admin.system.states.index') }}" class="btn btn-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
        Back to States
      </a>
      <button type="button" id="save-btn" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
        Save Changes
      </button>
    </div>
  </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let initialState = {};

    // Store initial state
    $('.country-checkbox').each(function() {
        initialState[$(this).val()] = $(this).is(':checked');
    });

    // Update label text when checkbox changes
    $('.country-checkbox').on('change', function() {
        const label = $(this).next('label');
        label.text($(this).is(':checked') ? 'Active' : 'Inactive');
    });

    $('#save-btn').on('click', function() {
        const changes = {
            activate: [],
            deactivate: []
        };

        // Collect changes
        $('.country-checkbox').each(function() {
            const id = $(this).val();
            const currentState = $(this).is(':checked');
            const previousState = initialState[id];

            if (currentState !== previousState) {
                if (currentState) {
                    changes.activate.push({
                        id: id,
                        name: $(this).data('country-name'),
                        count: $(this).data('states-count')
                    });
                } else {
                    changes.deactivate.push({
                        id: id,
                        name: $(this).data('country-name'),
                        count: $(this).data('states-count')
                    });
                }
            }
        });

        // Check if there are any changes
        if (changes.activate.length === 0 && changes.deactivate.length === 0) {
            toastr.info('No changes detected', 'Info');
            return;
        }

        // Build confirmation message
        let message = 'You are about to make the following changes:\n\n';
        let totalStates = 0;

        if (changes.activate.length > 0) {
            message += `<strong>Activate states for:</strong>\n`;
            changes.activate.forEach(country => {
                message += `• ${country.name} (${country.count} states)\n`;
                totalStates += parseInt(country.count);
            });
            message += '\n';
        }

        if (changes.deactivate.length > 0) {
            message += `<strong>Deactivate states for:</strong>\n`;
            changes.deactivate.forEach(country => {
                message += `• ${country.name} (${country.count} states)\n`;
                totalStates += parseInt(country.count);
            });
        }

        message += `\n<strong>Total states affected: ${totalStates}</strong>`;

        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Changes',
            html: message.replace(/\n/g, '<br>'),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#206bc4',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, save changes!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Process activations
                let promises = [];

                if (changes.activate.length > 0) {
                    promises.push(
                        $.ajax({
                            url: '{{ route("admin.system.states.bulk-activate") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                country_ids: changes.activate.map(c => c.id),
                                activate: true
                            }
                        })
                    );
                }

                if (changes.deactivate.length > 0) {
                    promises.push(
                        $.ajax({
                            url: '{{ route("admin.system.states.bulk-activate") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                country_ids: changes.deactivate.map(c => c.id),
                                activate: false
                            }
                        })
                    );
                }

                // Execute all requests
                Promise.all(promises)
                    .then(responses => {
                        let totalAffected = 0;
                        responses.forEach(response => {
                            totalAffected += response.affected_count || 0;
                        });

                        Swal.fire({
                            title: 'Success!',
                            text: `Successfully updated ${totalAffected} state(s)`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route("admin.system.states.index") }}';
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: error.responseJSON?.message || 'Failed to update states',
                            icon: 'error'
                        });
                    });
            }
        });
    });
});
</script>
@endpush
