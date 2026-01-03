<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Performance Stat Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $performanceStat->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Exam</label>
                            <select class="form-select @error('exam_id') is-invalid @enderror" name="exam_id" required>
                                <option value="">Select Exam</option>
                                @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $performanceStat->exam_id ?? '') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->code }} - {{ $exam->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('exam_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Total Tests</label>
                            <input type="number" class="form-control @error('total_tests') is-invalid @enderror" name="total_tests" value="{{ old('total_tests', $performanceStat->total_tests ?? '') }}" required>
                            @error('total_tests')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Average Score</label>
                            <input type="number" step="0.01" class="form-control @error('avg_score') is-invalid @enderror" name="avg_score" value="{{ old('avg_score', $performanceStat->avg_score ?? '') }}" required>
                            @error('avg_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Accuracy</label>
                            <input type="number" step="0.01" class="form-control @error('accuracy') is-invalid @enderror" name="accuracy" value="{{ old('accuracy', $performanceStat->accuracy ?? '') }}" required>
                            @error('accuracy')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Test At</label>
                    <input type="datetime-local" class="form-control @error('last_test_at') is-invalid @enderror" name="last_test_at" value="{{ old('last_test_at', $performanceStat->last_test_at ? $performanceStat->last_test_at->format('Y-m-d\TH:i:s') : '') }}">
                    @error('last_test_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any additional scripts if needed
});
</script>
@endpush