@extends('admin.layouts.master')

@section('page-title', 'Create One Liner')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.one_liners.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.one_liners.index') }}">One Liners</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.one_liners.store') }}" method="POST">
          @csrf
          @include('admin.insurance.one_liners._form')

          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.one_liners.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Create One Liner</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection