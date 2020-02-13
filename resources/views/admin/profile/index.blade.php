@extends('layouts.master')

@section('title', 'Profile')

@section('style')
	<style>
		.profile .form-control {
			border: 0;
			border-bottom: 1px solid #e8e8e8;
			font-weight: 600;
		}

		.profile .form-control:focus {
			box-shadow: none;
			border-bottom: 3px solid #1eceab;
		}

		.profile .input-group-text {
			background: #1eceab;
			border: 0;
			font-weight: 600;
		}
	</style>
@endsection

@section('content')
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card comp-card profile">
				<div class="card-body p-md-5">
					<h3 class="text-center">Profile</h3>
					<form action="{{route('profile.update')}}" method="POST">
						@csrf
						@method('PATCH')
						<div class="form-group">
							<label for="thumbnail" class="col-form-label sr-only"> Image</label>
							<div id="rmmUpload" class="mb-3 text-center position-relative">
								<img src="{{ (Auth::user()->image) ? imageCache(Auth::user()->image) : asset('images/user.png') }}" alt="" style="max-height: 250px; max-width: 300px; margin: 10px auto;">
								<input type="hidden" id="site_logo" name="image" value="{{ Auth::user()->image }}">

								<button type="button" title="Change Profile Image" class="btn position-absolute" data-toggle="modal" data-target="#rbtMediaManager" style="bottom: 5px; left: 70%; background: transparent;">
									<i class="feather icon-edit-1" style="color: #ff0018;"></i>
								</button>
							</div>
						</div>

						<div class="form-group row mt-5">
							<label for="recipient-name" class="col-form-label col-md-2">Name: <span class="red">*</span></label>
							<div class="col-md-10">
								<input type="text" class="form-control @error('name') is-invalid @enderror" id="recipient-name" name="name" required
									   value="{{ old('name', Auth::user()->name) }}">
								@error('name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="recipient-mobile" class="col-form-label col-md-2">Mobile: <span class="red">*</span></label>
							<div class="col-md-10 input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">+880</span>
								</div>
								<input type="text" class="form-control @error('mobile') is-invalid @enderror" id="recipient-mobile" name="mobile"
									   value="{{ old('mobile', Auth::user()->mobile) }}" required>
								@error('mobile')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label for="recipient-email" class="col-form-label col-md-2">Email: <span class="red">*</span></label>
							<div class="col-md-10">
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="recipient-email" value="{{ old('email', Auth::user()->email) }}" name="email" {{ (!Auth::user()->isAdmin()) ? 'disabled' : '' }}>
								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
								@if(!Auth::user()->isAdmin())
									<small class="form-text text-muted">To change your Email please contact with the Administrator.</small>
								@endif
							</div>
						</div>
						<div class="form-group row align-middle">
							<label for="address" class="col-form-label col-md-2 pt-md-3">Address: <span class="red">*</span></label>
							<div class="col-md-10">
								<textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" cols="30" rows="3" style="resize: none;">{!! old('address', Auth::user()->address) !!}</textarea>
								@error('address')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>

						<div class="form-group text-center mt-5">
							<button type="submit" class="btn btn-mat btn-success"> Update Profile</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<rbt-media-manager directory="Administrators" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
	</div>

	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card comp-card profile">
				<div class="card-body p-md-5">
					<h3 class="text-center">Change Password</h3>
					<form action="{{ route('profile.password') }}" method="POST">
						@csrf
						@method('PATCH')
						<div class="form-group row">
							<label for="recipient-password-old" class="col-form-label col-md-4">Old Password: <span class="red">*</span></label>
							<div class="col-md-8">
								<input type="password" class="form-control @error('old_password') is-invalid @enderror" id="recipient-password-old" name="old_password" required>
								@error('old_password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label for="recipient-password" class="col-form-label col-md-4">New Password: <span class="red">*</span></label>
							<div class="col-md-8">
								<input type="password" class="form-control @error('password') is-invalid @enderror" id="recipient-password" name="password" required>
								@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label for="recipient-password-confirm" class="col-form-label col-md-4">Confirm New Password: <span class="red">*</span></label>
							<div class="col-md-8">
								<input type="password" class="form-control @error('name') is-invalid @enderror" id="recipient-password-confirm" name="password_confirmation" required>
							</div>
						</div>
						<div class="form-group text-center mt-5">
							<button type="submit" class="btn btn-mat btn-success"> Update Password</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<br>

@endsection
