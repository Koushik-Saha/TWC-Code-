<div class="form-group">
    <label for="accountFor" class="col-form-label">Account For Name: <span class="required">*</span></label>
    <select name="user_id" id="accountFor" class="custom-select" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
</div>
