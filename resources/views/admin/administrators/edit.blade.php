@extends('layouts.master')

@section('title', 'Add New Administrator')

@section('content')
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Edit Administrator</h3>
				<form action="{{route('administrators.edit', ['id' => $administrator->id])}}" method="post">
					@csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Type: <span class="red">*</span></label>
                        <select class="custom-select" id="recipient-name" name="role" autofocus required>
                            <option selected>----- Select Administrator Type -----</option>
                            <option value="administrator" {{ ($administrator->role->role_slug === 'administrator') ? 'selected' : '' }}>Administrator</option>
                            <option value="manager" {{ ($administrator->role->role_slug === 'manager') ? 'selected' : '' }}>Manager</option>
                            <option value="accountant" {{ ($administrator->role->role_slug === 'accountant') ? 'selected' : '' }}>Accountant</option>
                        </select>
                    </div>
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="name" required value="{{ $administrator->name }}">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Change Password: <span class="red">*</span></label>
		            	<input type="password" class="form-control" id="recipient-name" name="password">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Confirm Password: <span class="red">*</span></label>
		            	<input type="password" class="form-control" id="recipient-name" name="password_confirmation">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Mobile: <span class="red">*</span></label>
		            	<input type="number" class="form-control" id="recipient-name" name="mobile" value="{{ $administrator->mobile }}" required>
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Email: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="email" value="{{ $administrator->email }}" required="" >
		            </div>
                    <div class="form-group">
                        <label for="address" class="col-form-label">Address: <span class="red">*</span></label>
                        <textarea class="form-control" name="address" id="address" cols="30" rows="5" required>{!! $administrator->address !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="note" class="col-form-label">Note: <small class="text-muted">(Optional)</small></label>
                        <textarea class="form-control" name="note" id="note" cols="30" rows="5">{!! $administrator->note !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Change Image: <small class="text-muted">(Optional)</small></label>
                        <div id="rmmUpload" class="mb-3">
                            <img src="{{ ($administrator->image) ? imageCache($administrator->image) : asset('images/user.png') }}" alt="{{ $administrator->name }}" style="max-height: 250px; max-width: 300px;">
                            <input type="hidden" id="site_logo" name="image" value="{{ $administrator->image }}">
                        </div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                            Change
                        </button>
                    </div>
					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary"> Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
    <rbt-media-manager directory="Administrators" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
</div>
<br>

@endsection
