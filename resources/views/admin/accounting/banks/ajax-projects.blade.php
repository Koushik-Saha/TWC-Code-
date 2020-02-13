<div class="form-group">
    <label for="accountFor" class="col-form-label">For Project: <span class="required">*</span></label>
    <select name="project_id" id="accountFor" class="custom-select" required>
        <option selected disabled>--- Select Project ---</option>
        @foreach($projects as $project)
            <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
        @endforeach
    </select>
</div>
