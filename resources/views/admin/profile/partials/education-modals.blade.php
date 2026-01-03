@php
    $currentYear = (int) date('Y');
    $maxYear = $currentYear + 10;
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
  <div class="mb-2 mb-md-0">
    <p class="text-secondary mb-0">Maintain the education timeline that appears on your public profile.</p>
  </div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12h14" /></svg>
    Add Education
  </button>
</div>

@if($admin->education->count())
  <div class="table-responsive">
    <table class="table table-vcenter">
      <thead>
        <tr>
          <th>Qualification</th>
          <th>Year of Passing</th>
          <th>Medium</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($admin->education as $education)
          <tr>
            <td>{{ $education->qualification }}</td>
            <td>{{ $education->year_of_passing }}</td>
            <td>{{ $education->medium }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
              <button type="button"
                class="btn btn-sm btn-icon btn-primary edit-education-btn"
                data-id="{{ $education->id }}"
                data-qualification="{{ $education->qualification }}"
                data-year="{{ $education->year_of_passing }}"
                data-medium="{{ $education->medium }}"
                data-update-url="{{ route('admin.profile.update-education', $education->id) }}"
                title="Edit education">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                <span class="visually-hidden">Edit</span>
              </button>
              <form action="{{ route('admin.profile.delete-education', $education->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this education entry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-icon btn-danger ms-1 delete-education-btn" title="Delete education">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                  <span class="visually-hidden">Delete</span>
                </button>
              </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  <div class="empty">
    <p class="empty-title">No education records yet</p>
    <p class="empty-subtitle text-secondary">
      Add your academic history so teammates understand your background.
    </p>
    <div class="empty-action">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationModal">Add first education</button>
    </div>
  </div>
@endif

{{-- Add Education Modal --}}
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.profile.add-education') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Education</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Qualification</label>
            <input type="text" class="form-control" name="qualification" value="{{ old('qualification') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Year of Passing</label>
            <input type="number" class="form-control" name="year_of_passing" min="1900" max="{{ $maxYear }}" value="{{ old('year_of_passing', $currentYear) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Medium</label>
            <input type="text" class="form-control" name="medium" value="{{ old('medium') }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Education</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Education Modal --}}
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editEducationForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Education</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Qualification</label>
            <input type="text" class="form-control" name="qualification" id="edit_qualification" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Year of Passing</label>
            <input type="number" class="form-control" name="year_of_passing" id="edit_year" min="1900" max="{{ $maxYear }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Medium</label>
            <input type="text" class="form-control" name="medium" id="edit_medium" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Education</button>
        </div>
      </form>
    </div>
  </div>
</div>
