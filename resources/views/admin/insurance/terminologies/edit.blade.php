@extends('admin.layouts.master')

@section('page-title', 'Edit Terminology')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.terminologies.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.terminologies.index') }}">Terminologies</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.terminologies.update', $terminology->id) }}" method="POST">
          @csrf
          @method('PUT')
          @include('admin.insurance.terminologies._form')

          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.terminologies.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Update Terminology</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection