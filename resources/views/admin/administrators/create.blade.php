@extends('layouts.master')

@section('title', 'Add New Administrator')

@section('content')
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Add New Administrator</h3>
				<form action="{{route('administrators.add')}}" method="post">
					@csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Type: <span class="red">*</span></label>
                        <select class="custom-select" id="recipient-name" name="role" autofocus required>
                            <option selected>----- Select Administrator Type -----</option>
							@foreach($roles as $role)
                            	<option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="name" required value="{{old('name')}}" placeholder="Please enter your name">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Password: <span class="red">*</span></label>
		            	<input type="password" class="form-control" id="recipient-name" name="password" required placeholder="Please enter your password">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Confirm Password: <span class="red">*</span></label>
		            	<input type="password" class="form-control" id="recipient-name" name="password_confirmation" required placeholder="Please confirm your password">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Mobile: <span class="red">*</span></label>
		            	<input type="number" class="form-control" id="recipient-name" name="mobile" value="{{old('mobile')}}" required placeholder="Please enter your 11 digit mobile number">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Email: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="email" value="{{old('email')}}" required="" placeholder="Please enter your valid email address">
		            </div>
                    <div class="form-group">
                        <label for="address" class="col-form-label">Address: <span class="red">*</span></label>
                        <textarea placeholder="Please enter your address" class="form-control" name="address" id="address" cols="30" rows="5" required>{!! old('address') !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="note" class="col-form-label">Note: <small class="text-muted">(Optional)</small></label>
                        <textarea placeholder="Please enter some note" class="form-control" name="note" id="note" cols="30" rows="5">{!! old('note') !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Add Image: <small class="text-muted">(Optional)</small></label>
                        <div id="rmmUpload" class="mb-3">
                            <img src="{{ asset('images/user.png') }}" alt="" style="max-height: 250px; max-width: 300px;">
                            <input type="hidden" id="site_logo" name="image" value="{{ old('image') }}">
                        </div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                            Change
                        </button>
                    </div>
					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary"> Create</button>
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
