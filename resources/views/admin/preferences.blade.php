@extends('layouts.master')

@section('title', 'Preferences')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h3 class="card-header w-100 text-center">
                    Preferences
                </h3>
                <hr>
                <div class="card-body">
                    <form action="{{ route('option.save') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label font-weight-bold">Company Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Company Name" value="{{ getOption('company_name') }}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 col-form-label font-weight-bold">Company Mobile</label>
                            <div class="col-sm-10">
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Company Mobile" value="{{ getOption('company_phone') }}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label font-weight-bold">Company Address</label>
                            <div class="col-sm-10">
                                <textarea name="address" class="form-control" id="address" rows="5" style="resize: none;">{!! getOption('company_address') !!}</textarea>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="background" class="col-sm-2 col-form-label font-weight-bold">Background Image:</label>
                            <div class="col-sm-10">
                                <div id="rmmUpload">
                                    @if(getOption('background_image') && strlen(getOption('background_image')) > 5)
                                        <img src="{{ 'uploads/public/cache/large/' . getOption('background_image') }}" alt="" style="max-height: 250px; max-width: 300px;">
                                    @else
                                        <img src="{{ asset('images/logo.jpg') }}" alt="" style="max-height: 250px; max-width: 300px;">
                                    @endif
                                    <input type="hidden" id="site_logo" name="image" value="{{ getOption('background_image') }}">
                                </div>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#rbtMediaManager">
                                    Change
                                </button>
                                <small class="form-text text-danger">Recommended Size: (1920px x 1080px) OR (1366px x 768 px)</small>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <rbt-media-manager directory="Background" user_id="{{ auth()->id() }}" url_prefix="/bs-mm-api" show_as="modal" element_id="rmmUpload"></rbt-media-manager>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
