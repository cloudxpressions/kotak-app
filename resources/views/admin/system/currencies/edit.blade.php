@extends('admin.layouts.master')

@section('page-title', 'Edit Currency')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.currencies.index') }}">Currencies</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Currency</h3>
      <p class="card-subtitle">Update Currency</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.currencies.update', $currency->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.currencies._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.currencies.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update Currency</button></div>
    </form>
  </div>
</div>
@endsection
