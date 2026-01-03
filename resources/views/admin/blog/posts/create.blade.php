@extends('admin.layouts.master')

@section('page-title', 'Create Blog Post')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.posts.index') }}">Blog Posts</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Create Blog Post</h3>
      <p class="card-subtitle">Enter blog post details below</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf
      @include('admin.blog.posts._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Create Post</button>
      </div>
    </form>
  </div>
</div>
@endsection