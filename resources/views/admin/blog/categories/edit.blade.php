@extends('admin.layouts.master')

@section('page-title', 'Edit Blog Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.categories.index') }}">Blog Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Blog Category</h3>
      <p class="card-subtitle">Update blog category details</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.blog.categories._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Update Category</button>
      </div>
    </form>
  </div>
</div>
@endsection