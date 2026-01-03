@extends('admin.layouts.master')

@section('page-title', 'Create City')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.cities.index') }}">Cities</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create City</h3>
      <p class="card-subtitle">Enter city details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.cities.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.system.cities._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.cities.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create City</button></div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#country_id').on('change', function() {
        var countryId = $(this).val();

        if(countryId) {
            $.ajax({
                url: '/admin/system/states-by-country/' + countryId,
                type: 'GET',
                success: function(data) {
                    $('#state_id').empty();
                    $('#state_id').append('<option value="">Select State</option>');

                    $.each(data, function(key, value) {
                        $('#state_id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        } else {
            $('#state_id').empty();
            $('#state_id').append('<option value="">Select State</option>');
        }
    });
});
</script>
@endpush
