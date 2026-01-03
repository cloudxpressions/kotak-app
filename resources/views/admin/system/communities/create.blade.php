@extends('admin.layouts.master')

@section('page-title', 'Create Community')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.communities.index') }}">Communities</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Community</h3>
      <p class="card-subtitle">Enter community details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.communities.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.system.communities._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.communities.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create Community</button></div>
    </form>
  </div>
</div>
@endsection
