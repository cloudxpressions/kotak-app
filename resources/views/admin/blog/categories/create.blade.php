@extends('admin.layouts.master')

@section('page-title', 'Create Blog Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.categories.index') }}">Blog Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Blog Category</h3>
      <p class="card-subtitle">Enter blog category details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.categories.store') }}" method="POST" novalidate>
      @csrf
      @include('admin.blog.categories._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Create Category</button>
      </div>
    </form>
  </div>
</div>
@endsection