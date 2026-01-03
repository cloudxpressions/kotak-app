@extends('admin.layouts.master')

@section('page-title', 'Create Language')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.languages.index') }}">Languages</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Add New Language</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.system.languages.store') }}" method="POST" novalidate>
          @csrf
          @include('admin.system.languages._form')
          
          <div class="text-end mt-3"><a href="{{ route('admin.system.languages.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary ms-2">Create Language</button></div></form>
      </div>
    </div>
  </div>
</div>
@endsection
