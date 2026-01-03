@extends('admin.layouts.master')

@section('page-title', 'Create Special Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.special-categories.index') }}">Special Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Special Category</h3>
      <p class="card-subtitle">Enter special category details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.special-categories.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.system.special-categories._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.special-categories.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create Special Category</button></div>
    </form>
  </div>
</div>
@endsection
