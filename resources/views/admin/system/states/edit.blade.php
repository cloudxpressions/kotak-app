@extends('admin.layouts.master')

@section('page-title', 'Edit State')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.states.index') }}">States</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit State</h3>
      <p class="card-subtitle">Update State</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.states.update', $state->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.states._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.states.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update State</button></div>
    </form>
  </div>
</div>
@endsection
