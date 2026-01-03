@extends('admin.layouts.master')

@section('page-title', 'Edit Date Format')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.date-formats.index') }}">Date Formats</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Date Format</h3>
      <p class="card-subtitle">Update Date Format</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.date-formats.update', $dateFormat->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.system.date-formats._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.date-formats.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Update Date Format</button></div>
    </form>
  </div>
</div>
@endsection
