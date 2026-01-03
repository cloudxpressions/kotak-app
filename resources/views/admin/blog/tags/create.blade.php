@extends('admin.layouts.master')

@section('page-title', 'Create Blog Tag')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.tags.index') }}">Blog Tags</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Blog Tag</h3>
      <p class="card-subtitle">Enter blog tag details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.tags.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.blog.tags._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Create Tag</button>
      </div>
    </form>
  </div>
</div>
@endsection