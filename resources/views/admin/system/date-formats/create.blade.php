@extends('admin.layouts.master')

@section('page-title', 'Create Date Format')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.date-formats.index') }}">Date Formats</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Date Format</h3>
      <p class="card-subtitle">Enter date format details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.date-formats.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.system.date-formats._form')
      
      <div class="text-end mt-3"><a href="{{ route('admin.system.date-formats.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create Date Format</button></div>
    </form>
  </div>
</div>
@endsection
