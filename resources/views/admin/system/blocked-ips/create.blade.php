@extends('admin.layouts.master')

@section('page-title', 'Block IP Address')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.blocked-ips.index') }}">Blocked IPs</a></li>
  <li class="breadcrumb-item active" aria-current="page">Create</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Block New IP</h3>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.blocked-ips.store') }}" method="POST">
      @csrf
      @include('admin.system.blocked-ips._form')

      <div class="text-end mt-4">
        <a href="{{ route('admin.system.blocked-ips.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection
