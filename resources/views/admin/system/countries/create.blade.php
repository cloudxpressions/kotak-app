@extends('admin.layouts.master')

@section('page-title', 'Create Country')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.countries.index') }}">Countries</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Country</h3>
      <p class="card-subtitle">Enter country details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.countries.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.system.countries._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.countries.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create Country</button></div>
    </form>
  </div>
</div>
@endsection
