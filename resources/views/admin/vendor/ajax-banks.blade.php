<div class="form-group">
    <label for="bankAccount" class="col-form-label">Account: <span class="red">*</span></label>
    <select name="bank_id" id="bankAccount" class="form-control" required>
        @foreach($banks as $bank)
            <option value="{{ $bank->bank_id }}">
                @if(!$bank->bank_account_no || strlen($bank->bank_account_no) <= 3)
                    {{ $bank->bank_account_name . ' --- ' . $bank->user->name }}
                @else
                    {{ $bank->bank_account_name . ' --- ' . $bank->bank_account_no }}
                @endif
            </option>
        @endforeach
    </select>
</div>
<br>
