@extends('admin.layouts.master')

@section('page-title', 'Edit Maintenance Entry')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.maintenances.index') }}">Maintenance</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Maintenance Entry</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.maintenances.update', $maintenance->id) }}" method="POST">
      @csrf
      @method('PUT')

      @include('admin.system.maintenances._form', ['maintenance' => $maintenance])

      <div class="text-end mt-4">
        <a href="{{ route('admin.system.maintenances.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Update Entry</button>
      </div>
    </form>
  </div>
</div>
@endsection
