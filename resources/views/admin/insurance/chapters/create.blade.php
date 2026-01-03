@extends('admin.layouts.master')

@section('page-title', 'Create Chapter')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.chapters.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.chapters.index') }}">Chapters</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.chapters.store') }}" method="POST">
          @csrf
          @include('admin.insurance.chapters._form')
          
          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.chapters.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Create Chapter</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection