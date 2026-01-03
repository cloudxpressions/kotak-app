@extends('admin.layouts.master')

@section('page-title', 'Import Translations')

@section('breadcrumbs')
<ol class="breadcrumb" aria-label="breadcrumbs">
  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="#">System</a></li>
  <li class="breadcrumb-item"><a href="{{ route('admin.system.translations.index') }}">Translations</a></li>
  <li class="breadcrumb-item active" aria-current="page">Import</li>
</ol>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Import Translations</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Instructions</h4>
                <ol>
                    <li>Download the current translations file to get the correct format and keys.</li>
                    <li>Open the downloaded Excel file and modify the translations as needed.</li>
                    <li>Do not modify the <strong>key</strong> or <strong>module</strong> columns.</li>
                    <li>Save the file and upload it below.</li>
                </ol>
                <div class="mb-3">
                    <a href="{{ route('admin.system.translations.export-excel') }}" class="btn btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                        Download Template / Export Current
                    </a>
                </div>
            </div>
            <div class="col-md-6 border-start">
                <h4>Upload File</h4>
                <form action="{{ route('admin.system.translations.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Excel File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx, .csv" required>
                        @error('file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                            Start Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
