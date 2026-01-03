@extends('admin.layouts.master')

@section('page-title', 'Create Insurance Category')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.categories.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.categories.index') }}">Categories</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.categories.store') }}" method="POST">
          @csrf
          @include('admin.insurance.categories._form')
          
          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.categories.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Create Category</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection