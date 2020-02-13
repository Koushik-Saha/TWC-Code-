@extends('layouts.master')

@section('title', 'Add Man Power')

@section('style')
<style>
	.form-group {
margin-bottom: unset;
	}
	.form-group {
	margin-bottom: unset;
	}
</style>
@endsection
@section('content')
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Add a new Staff</h3>
				<form action="{{route('man_power.add')}}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="recipient-name" name="name" value="{{ old('name') }}" required placeholder="Please enter your name">
		            </div>
		            <div class="form-group">
		            	<label for="fathers_name" class="col-form-label">Father's Name : <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="fathers_name" name="fathers_name" value="{{ old('fathers_name') }}" required placeholder="Please enter your father name">
		            </div>

		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Address : <span class="red">*</span></label>
                        <textarea placeholder="Please enter your address" name="address" class="form-control" id="recipient-name" cols="30" rows="4" style="resize: none;" required>{!! old('address') !!}</textarea>
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Mobile :<span class="red">*</span></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-secondary">+880</span>
							</div>
							<input placeholder="Please enter your 8 digit of your mobile number" type="text" class="form-control" id="recipient-name" name="mobile" value="{!! old('mobile') !!}" required>
						</div>
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Select Designation : <span class="red">*</span></label>
		            	<select name="role" id="recipient-name" class="form-control" required>
                            <option selected disabled>----- Select Designation -----</option>
							@foreach($roles as $role)
							    <option {{ (old("role") == $role->role_id ? "selected" : "") }} value="{{ $role->role_id }}" >{{ $role->role_name }}</option>
							@endforeach
						</select>
		            </div>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">For Project : <span class="red">*</span> </label>
						<select name="project_id" id="recipient-name" class="form-control" required="">
							<option disabled="" selected>----- Select Project -----</option>
							@foreach($projects as $project)
								<option {{ (old("project_id") == $project->project_id ? "selected" : "") }} value="{{$project->project_id}}">
                                    {{$project->project_name}}
                                </option>
							@endforeach

						</select>
					</div>
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Section : <span class="red">*</span></label>
		            	<input placeholder="Please enter which section he/she is working" type="text" class="form-control" id="recipient-name" name="section" required="" value="{{old('section')}}">
		            </div>
		            <div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Salary per day : <span class="red">*</span></label>
		            	<input placeholder="Please enter per day salary" type="number" class="form-control" id="recipient-name" name="salary" required="" value="{{old('salary')}}">
		            </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Note:</label>
                        <textarea placeholder="Please enter some note" name="note" class="form-control" id="recipient-name" cols="30" rows="4" style="resize: none;">{!! old('note') !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Staff Image: <small class="text-muted">(Optional)</small></label>
						<div id="rmmUpload">
							<img src="{{ asset('images/user.png') }}" alt="" style="max-height: 250px; max-width: 300px;">
							<input type="hidden" id="site_logo" name="image" value="{{ old('image') }}">
						</div>
						<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
							Change
						</button>
                    </div>
					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-success"> Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
	<rbt-media-manager directory="Staffs" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
</div>

<br>

@endsection
