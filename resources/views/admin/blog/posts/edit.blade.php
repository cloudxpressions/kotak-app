@extends('admin.layouts.master')

@section('page-title', 'Edit Blog Post')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.posts.index') }}">Blog Posts</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Blog Post</h3>
      <p class="card-subtitle">Update blog post details</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      @include('admin.blog.posts._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Update Post</button>
      </div>
    </form>
  </div>
</div>
@endsection