@extends('layouts.master')

@section('title', 'Edit Project')

@section('style')

    <style>

        #createProject .form-group label {
            font-weight: 600;
        }

        .create-btn {
            font-size: 12px;
            font-style: italic;
            text-decoration: underline;
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card comp-card">
                <h3 class="card-header" style="text-align: center;">Edit Project</h3>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form action="{{route('project.edit', ['id' => $project->project_id])}}" method="post" id="createProject">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Project Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="recipient-name" name="name" required
                                           value="{{ (old('name') == null || strlen(old('name') < 5) ? $project->project_name : old('name') ) }}">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Project Location: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="recipient-name" name="location" required=""
                                           value="{{ (old('location') == null || strlen(old('location') < 5) ? $project->project_location : old('location') ) }}">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name1" class="col-form-label">Project Estimated Total: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="recipient-name1" name="price" required=""
                                           value="{{ (old('price') == null || strlen(old('price') < 5) ? $project->project_price : old('price') ) }}">
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-radio custom-control-inline pl-0">
                                        <label >Project Client:</label>
                                    </div>
                                    <input type="hidden" name="aHiddenRes" value="{{ old('client_type') }}">

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline1" name="client_type" value="old" class="custom-control-input"
                                               required {{ (old('client_type') && old('client_type') == 'old') ? 'checked' : '' }}{{ (old('client_type') == null) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadioInline1">Old</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="customRadioInline2" name="client_type" value="new" class="custom-control-input"
                                               required {{ (old('client_type') && old('client_type') == 'new') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customRadioInline2">New</label>
                                    </div>
                                </div>

                                <div class="tab-content" style="border: 1px solid #d6d6d6; padding: 12px;">
                                    <div class="tab-pane fade" id="oldTab" role="tabpanel" aria-labelledby="old-tab">
                                        <div id="ifOld" class="form-group">
                                            <label for="clientId" class="col-form-label">Client Name: <span class="text-danger">*</span></label>
                                            <select name="client_id" id="clientId" class="form-control" aria-describedby="clientHelp">
                                                <option selected>----- Select Client -----</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ ($project->client->id == $client->id) ? 'selected' : '' }}>{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="newTab" role="tabpanel" aria-labelledby="new-tab">
                                        <div class="form-group">
                                            <label for="clientName" class="col-form-label">Client Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('client_name') is-invalid @enderror" id="clientName" name="client_name"
                                                   value="{{ old('client_name') }}">
                                            @error('client_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="clientMobile" class="col-form-label">Client Mobile: <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('client_number') is-invalid @enderror" id="clientMobile" name="client_number"
                                                   value="{{ old('client_number') }}">
                                            @error('client_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="clientEmail" class="col-form-label">Client Email:</label>
                                            <input type="text" class="form-control @error('client_email') is-invalid @enderror" id="clientEmail" name="client_email"
                                                   value="{{ old('client_email') }}">
                                            @error('client_email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address-text" class="col-form-label">Client Address:</label>
                                            <textarea class="form-control" id="address-text" name="client_address">{!! old('client_address') !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Project Description:</label>
                                    <textarea class="form-control" id="message-text" name="description">{!! $project->project_description !!}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="thumbnail" class="col-form-label">Project Image: <small class="text-muted">(Optional)</small></label>
                                    <div id="rmmUpload">
                                        <img src="{{ ($project->project_image) ? imageCache($project->project_image) : '' }}" alt="" style="max-height: 250px; max-width: 300px;">
                                        <input type="hidden" id="site_logo" name="project_image" value="{{ ($project->project_image) ? url('uploads/public/cache/large/' . $project->project_image) : old('image') }}">
                                    </div>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                                        Change
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <rbt-media-manager directory="Projects" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            let cTypeH = $('input[type=hidden][name=aHiddenRes]');
            if (cTypeH.val() === 'old' || (typeof cTypeH.val() === 'string' && cTypeH.val().length < 3)) {
                $('#newTab').removeClass('show active');
                $('#oldTab').addClass('show active');
            }
            else if (cTypeH.val() === 'new') {
                $('#newTab').addClass('show active');
                $('#oldTab').removeClass('show active');
            }

            let cType = $('input[type=radio][name=client_type]');
            cType.change(function() {
                if (this.value === 'old') {
                    cTypeH.val('old');
                    $('#newTab').removeClass('show active');
                    $('#oldTab').addClass('show active');
                }
                else if (this.value === 'new') {
                    cTypeH.val('new');
                    $('#newTab').addClass('show active');
                    $('#oldTab').removeClass('show active');
                }
            });
        });
    </script>
@endsection
