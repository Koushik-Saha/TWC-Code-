<div class="form-group">
    <label for="accountFor" class="col-form-label">Manager: <span class="required">*</span></label>
    <select name="manager_id" id="accountFor" class="custom-select" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div>
