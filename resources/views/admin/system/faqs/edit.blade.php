@extends('admin.layouts.master')

@section('page-title', 'Edit FAQ')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.faqs.index') }}">FAQs</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Update FAQ</h3>
      <p class="card-subtitle">Keep the information accurate and relevant.</p>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.system.faqs.update', $faq) }}" method="POST" novalidate>
      @csrf
      @method('PUT')
      @include('admin.system.faqs._form')
      <div class="text-end mt-3">
        <a href="{{ route('admin.system.faqs.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary ms-2">Update FAQ</button>
      </div>
    </form>
  </div>
</div>
@endsection
