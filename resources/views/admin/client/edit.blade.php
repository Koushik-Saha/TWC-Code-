@extends('layouts.master')

@section('title', 'Edit Client')

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
				<h3 style="text-align: center;">Edit Client</h3>
				<form action="{{route('client.edit', ['id' => $client->id]) }}" method="post" enctype="multipart/form-data">
					@csrf
                    @method('PATCH')
		            <div class="form-group">
		            	<label for="name" class="col-form-label">Name : <span class="red">*</span></label>
		            	<input type="text" class="form-control" id="name" name="name" required="" value="{{ $client->name }}">
		            </div>

                    <div class="form-group">
                        <label for="mobile" class="col-form-label">Mobile : <span class="red">*</span></label>
                        <input type="number" class="form-control" id="mobile" name="mobile" required="" value="0{{ $client->mobile }}">
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-form-label">Email : </label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $client->email }}">
                    </div>

					<div class="form-group">
		            	<label for="address" class="col-form-label">Address :</label>
		            	<textarea class="form-control" name="address" id="address" cols="30" rows="5" required="">{!! $client->address !!}</textarea>
		            </div>

                    <div class="form-group">
                        <label for="note" class="col-form-label">Note :</label>
                        <textarea class="form-control" name="note" id="note" cols="30" rows="5">{!! $client->note !!}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="col-form-label">Change Image: <small class="text-muted">(Optional)</small></label>
                        <div id="rmmUpload">
                            <img src="{{ ($client->image) ? url('uploads/public/cache/medium/' . $client->image) : asset('images/user.png') }}" alt="" style="max-height: 250px; max-width: 300px;">
                            <input type="hidden" id="site_logo" name="client_image" value="{{ old('client_image') }}">
                        </div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                            Choose
                        </button>
                    </div>

					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-primary">Update</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="col-md-2"></div>
    <rbt-media-manager directory="Clients" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
</div>
<br>

@endsection
