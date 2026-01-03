<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
  <div class="mb-2 mb-md-0">
    <p class="text-secondary mb-0">Highlight the skills and proficiency levels you are comfortable sharing.</p>
  </div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5v14" /><path d="M5 12h14" /></svg>
    Add Skill
  </button>
</div>

@if($admin->skills->count())
  <div class="table-responsive">
    <table class="table table-vcenter">
      <thead>
        <tr>
          <th>Skill</th>
          <th>Proficiency</th>
          <th>Description</th>
          <th class="w-1">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($admin->skills as $skill)
          @php
            $levelBadges = [
              'Beginner' => 'bg-secondary-lt',
              'Intermediate' => 'bg-info-lt',
              'Advanced' => 'bg-primary-lt',
              'Expert' => 'bg-success-lt',
              'Master' => 'bg-warning-lt',
            ];
            $badgeClass = $levelBadges[$skill->proficiency_level] ?? 'bg-secondary-lt';
          @endphp
          <tr>
            <td>{{ $skill->skill_name }}</td>
            <td><span class="badge {{ $badgeClass }}">{{ $skill->proficiency_level }}</span></td>
            <td>{{ $skill->description }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
              <button type="button"
                class="btn btn-sm btn-icon btn-primary edit-skill-btn"
                data-id="{{ $skill->id }}"
                data-name="{{ $skill->skill_name }}"
                data-level="{{ $skill->proficiency_level }}"
                data-description="{{ e($skill->description) }}"
                data-update-url="{{ route('admin.profile.update-skill', $skill->id) }}"
                title="Edit skill">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                <span class="visually-hidden">Edit</span>
              </button>
              <form action="{{ route('admin.profile.delete-skill', $skill->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this skill?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-icon btn-danger ms-1 delete-skill-btn" title="Delete skill">
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
    <p class="empty-title">No skills added</p>
    <p class="empty-subtitle text-secondary">Add at least one skill to let your team know your strengths.</p>
    <div class="empty-action">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">Add first skill</button>
    </div>
  </div>
@endif

{{-- Add Skill Modal --}}
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.profile.add-skill') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Skill Name</label>
            <input type="text" class="form-control" name="skill_name" value="{{ old('skill_name') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Proficiency Level</label>
            <select class="form-select" name="proficiency_level" required>
              <option value="">Select level</option>
              @foreach($proficiencyLevels as $level)
                <option value="{{ $level }}" {{ old('proficiency_level') === $level ? 'selected' : '' }}>{{ $level }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Skill Modal --}}
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSkillForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label required">Skill Name</label>
            <input type="text" class="form-control" name="skill_name" id="edit_skill_name" required>
          </div>
          <div class="mb-3">
            <label class="form-label required">Proficiency Level</label>
            <select class="form-select" name="proficiency_level" id="edit_proficiency_level" required>
              @foreach($proficiencyLevels as $level)
                <option value="{{ $level }}">{{ $level }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="edit_skill_description" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Skill</button>
        </div>
      </form>
    </div>
  </div>
</div>
