@extends('admin.layouts.master')

@section('page-title', 'Edit Exam')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.exams.index') }}">Insurance</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.insurance.exams.index') }}">Exams</a></li>
  <li class="breadcrumb-item active" aria-current="page">Edit</li>
</ol>
@endsection

@section('content')
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <form action="{{ route('admin.insurance.exams.update', $exam->id) }}" method="POST">
          @csrf
          @method('PUT')
          @include('admin.insurance.exams._form')
          
          <div class="card-footer text-end">
            <div class="d-flex">
              <a href="{{ route('admin.insurance.exams.index') }}" class="btn btn-link">Cancel</a>
              <button type="submit" class="btn btn-primary ms-auto">Update Exam</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection