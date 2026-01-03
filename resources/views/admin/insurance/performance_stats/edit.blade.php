@extends('admin.layouts.master')

@section('page-title', 'Edit Performance Stat')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.performance_stats.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.performance_stats.index') }}">Performance Stats</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.performance_stats.update', $performanceStat->id) }}" method="POST">
          @csrf
          @method('PUT')
          @include('admin.insurance.performance_stats._form')

          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.performance_stats.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Update Performance Stat</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection