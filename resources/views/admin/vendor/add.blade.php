@extends('layouts.master')

@section('title', 'Add Vendor')

@section('style')
    <style>
        label {
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Add Vendor</h3>
				<form action="{{route('vendor.add') }}" method="post" enctype="multipart/form-data">
					@csrf
                    <div class="form-group">
                        <label for="project" class="col-form-label">For Project : <span class="red">*</span></label>
                        <select name="project_id" id="project" class="custom-select" required>
                            <option selected disabled>--- Select Project ---</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>
                    </div>
		            <div class="form-group">
		            	<label for="name" class="col-form-label">Name : <span class="red">*</span></label>
		            	<input placeholder="Please Enter Your Name" type="text" class="form-control" id="name" name="name" required="" value="{{ old('name') }}">
		            </div>

                    <div class="form-group">
                        <label for="mobile" class="col-form-label">Mobile : <span class="red">*</span></label>
                        <input placeholder="Please Enter Your 11 Digit Mobile Number" type="tel" class="form-control" id="mobile" name="mobile" required value="{{ old('mobile') }}">
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-form-label">Email : </label>
                        <input placeholder="Please Enter Your Valid Email Address"type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>

					<div class="form-group">
		            	<label for="address" class="col-form-label">Address :</label>
		            	<textarea placeholder="Please Enter Your Address"class="form-control" name="address" id="address" cols="30" rows="5">{!! old('address') !!}</textarea>
		            </div>

                    <div class="form-group">
                        <label for="note" class="col-form-label">Note :</label>
                        <textarea placeholder="Please Enter Additional Notes"class="form-control" name="note" id="note" cols="30" rows="5">{!! old('note') !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Add Image: <small class="text-muted">(Optional)</small></label>
                        <div id="rmmUpload">
                            <img src="{{ asset('images/logo.jpg') }}" alt="" style="max-height: 250px; max-width: 300px;">
                            <input type="hidden" id="site_logo" name="image" value="{{ old('image') }}">
                        </div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                            Change
                        </button>
                    </div>

					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary">Add</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
    <rbt-media-manager directory="Vendors" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
</div>
<br>

@endsection
