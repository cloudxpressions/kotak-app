@extends('admin.layouts.master')

@section('page-title', 'Edit Blog Tag')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.blog.tags.index') }}">Blog Tags</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Edit Blog Tag</h3>
      <p class="card-subtitle">Update blog tag details</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.blog.tags.update', $tag->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.blog.tags._form')

      <div class="text-end mt-3">
        <a href="{{ route('admin.blog.tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Update Tag</button>
      </div>
    </form>
  </div>
</div>
@endsection