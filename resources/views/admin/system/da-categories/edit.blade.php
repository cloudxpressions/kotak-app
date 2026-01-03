@extends('admin.layouts.master')

@section('page-title', 'Edit DA Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.da-categories.index') }}">DA Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit DA Category</h3>
      <p class="card-subtitle">Update DA Category</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.da-categories.update', $daCategory->id) }}" method="POST" novalidate>
      @csrf
      @method('PUT')
      @include('admin.system.da-categories._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.da-categories.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update DA Category</button></div>
    </form>
  </div>
</div>
@endsection
