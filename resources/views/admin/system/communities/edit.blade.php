@extends('admin.layouts.master')

@section('page-title', 'Edit Community')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.communities.index') }}">Communities</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Community</h3>
      <p class="card-subtitle">Update Community</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.communities.update', $community->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.communities._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.communities.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update Community</button></div>
    </form>
  </div>
</div>
@endsection
