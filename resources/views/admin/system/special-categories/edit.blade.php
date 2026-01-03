@extends('admin.layouts.master')

@section('page-title', 'Edit Special Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.special-categories.index') }}">Special Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Special Category</h3>
      <p class="card-subtitle">Update Special Category</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.special-categories.update', $specialCategory->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.special-categories._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.special-categories.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update Special Category</button></div>
    </form>
  </div>
</div>
@endsection
