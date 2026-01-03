@extends('admin.layouts.master')

@section('page-title', 'Activate Cities')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.cities.index') }}">Cities</a></li>
  <li class="breadcrumb-item active" aria-current="page">Activate Cities</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Bulk Activate/Deactivate Cities by State</h3>
    <p class="card-subtitle">Select states to activate or deactivate all their cities</p>
  </div>
  <div class="card-body">
    <form id="activation-form">
      @csrf
      @if($countries->isNotEmpty())
        <div class="accordion" id="countriesAccordion">
          @foreach($countries as $country)
            @if($country->states->isNotEmpty())
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading{{ $country->id }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $country->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $country->id }}">
                  <span class="avatar avatar-sm me-3" style="font-size: 1.5rem;">{{ $country->emoji_flag }}</span>
                  <div class="flex-fill">
                    <div class="font-weight-medium">{{ $country->name }}</div>
                    <div class="text-muted small">{{ $country->states->count() }} states</div>
                  </div>
                </button>
              </h2>
              <div id="collapse{{ $country->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $country->id }}" data-bs-parent="#countriesAccordion">
                <div class="accordion-body">
                  <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-outline-primary select-all-country" data-country-id="{{ $country->id }}">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12l5 5l10 -10" /><path d="M2 12l5 5m5 -5l5 -5" /></svg>
                      Select All States
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary deselect-all-country ms-2" data-country-id="{{ $country->id }}">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                      Deselect All
                    </button>
                  </div>
                  <div class="row g-3">
                    @foreach($country->states as $state)
                    <div class="col-md-6 col-lg-4">
                      <div class="card card-sm">
                        <div class="card-body">
                          <div class="d-flex align-items-center mb-2">
                            <div class="flex-fill">
                              <div class="font-weight-medium">{{ $state->name }}</div>
                              <div class="text-muted small">
                                <span class="badge bg-success-lt">{{ $state->active_cities_count }}</span> of 
                                <span class="badge bg-secondary-lt">{{ $state->cities_count }}</span> cities active
                              </div>
                            </div>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input state-checkbox country-{{ $country->id }}" type="checkbox" 
                                   id="state_{{ $state->id }}" 
                                   value="{{ $state->id }}"
                                   data-state-name="{{ $state->name }}"
                                   data-country-name="{{ $country->name }}"
                                   data-cities-count="{{ $state->cities_count }}"
                                   {{ $state->active_cities_count > 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="state_{{ $state->id }}">
                              {{ $state->active_cities_count > 0 ? 'Active' : 'Inactive' }}
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            @endif
          @endforeach
        </div>
      @else
        <div class="empty">
          <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
          </div>
          <p class="empty-title">No countries found</p>
          <p class="empty-subtitle text-muted">
            There are no countries with states in the system yet.
          </p>
        </div>
      @endif
    </form>
  </div>
  @if($countries->isNotEmpty())
  <div class="card-footer">
    <div class="d-flex justify-content-between align-items-center">
      <a href="{{ route('admin.system.cities.index') }}" class="btn btn-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
        Back to Cities
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
    $('.state-checkbox').each(function() {
        initialState[$(this).val()] = $(this).is(':checked');
    });

    // Update label text when checkbox changes
    $('.state-checkbox').on('change', function() {
        const label = $(this).next('label');
        label.text($(this).is(':checked') ? 'Active' : 'Inactive');
    });

    // Select all states in a country
    $('.select-all-country').on('click', function() {
        const countryId = $(this).data('country-id');
        $(`.country-${countryId}`).prop('checked', true).trigger('change');
    });

    // Deselect all states in a country
    $('.deselect-all-country').on('click', function() {
        const countryId = $(this).data('country-id');
        $(`.country-${countryId}`).prop('checked', false).trigger('change');
    });

    $('#save-btn').on('click', function() {
        const changes = {
            activate: [],
            deactivate: []
        };

        // Collect changes
        $('.state-checkbox').each(function() {
            const id = $(this).val();
            const currentState = $(this).is(':checked');
            const previousState = initialState[id];

            if (currentState !== previousState) {
                if (currentState) {
                    changes.activate.push({
                        id: id,
                        name: $(this).data('state-name'),
                        country: $(this).data('country-name'),
                        count: $(this).data('cities-count')
                    });
                } else {
                    changes.deactivate.push({
                        id: id,
                        name: $(this).data('state-name'),
                        country: $(this).data('country-name'),
                        count: $(this).data('cities-count')
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
        let totalCities = 0;

        if (changes.activate.length > 0) {
            message += `<strong>Activate cities for:</strong><br>`;
            changes.activate.forEach(state => {
                message += `• ${state.name}, ${state.country} (${state.count} cities)<br>`;
                totalCities += parseInt(state.count);
            });
            message += '<br>';
        }

        if (changes.deactivate.length > 0) {
            message += `<strong>Deactivate cities for:</strong><br>`;
            changes.deactivate.forEach(state => {
                message += `• ${state.name}, ${state.country} (${state.count} cities)<br>`;
                totalCities += parseInt(state.count);
            });
        }

        message += `<br><strong>Total cities affected: ${totalCities}</strong>`;

        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Changes',
            html: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#206bc4',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, save changes!',
            cancelButtonText: 'Cancel',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                // Process activations and deactivations
                let promises = [];

                if (changes.activate.length > 0) {
                    promises.push(
                        $.ajax({
                            url: '{{ route("admin.system.cities.bulk-activate") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                state_ids: changes.activate.map(s => s.id),
                                activate: true
                            }
                        })
                    );
                }

                if (changes.deactivate.length > 0) {
                    promises.push(
                        $.ajax({
                            url: '{{ route("admin.system.cities.bulk-activate") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                state_ids: changes.deactivate.map(s => s.id),
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
                            text: `Successfully updated ${totalAffected} city(s)`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '{{ route("admin.system.cities.index") }}';
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: error.responseJSON?.message || 'Failed to update cities',
                            icon: 'error'
                        });
                    });
            }
        });
    });
});
</script>
@endpush
