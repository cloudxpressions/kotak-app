@extends('admin.layouts.master')

@section('page-title', 'Edit User Classification')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.user-classifications.index') }}">User Classifications</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit User Classification</h3>
      <p class="card-subtitle">Update User Classification</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.user-classifications.update', $userClassification->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.user-classifications._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.user-classifications.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update User Classification</button></div>
    </form>
  </div>
</div>
@endsection
