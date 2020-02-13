@extends('layouts.master')

@section('title', 'Add New Administrator')

@section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card comp-card">
                <div class="card-body">
                    <h3 style="text-align: center;">Assign Admins To Project</h3>
                    <form action="{{route('administrators.add')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Type: <span class="red">*</span></label>
                            <select class="custom-select" id="recipient-name" name="role" autofocus required>
                                <option selected>----- Select Administrator Type -----</option>
                                <option value="administrator">Administrator</option>
                                <option value="manager">Manager</option>
                                <option value="accountant">Accountant</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="name" required value="{{old('name')}}">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Password: <span class="red">*</span></label>
                            <input type="password" class="form-control" id="recipient-name" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Confirm Password: <span class="red">*</span></label>
                            <input type="password" class="form-control" id="recipient-name" name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Mobile: <span class="red">*</span></label>
                            <input type="number" class="form-control" id="recipient-name" name="mobile" value="{{old('mobile')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Email: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="email" value="{{old('email')}}" required="" >
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-form-label">Address: <span class="red">*</span></label>
                            <textarea class="form-control" name="address" id="address" cols="30" rows="5" required>{!! old('address') !!}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="note" class="col-form-label">Note: <small class="text-muted">(Optional)</small></label>
                            <textarea class="form-control" name="note" id="note" cols="30" rows="5">{!! old('note') !!}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail" class="col-form-label">Change Image: <small class="text-muted">(Optional)</small></label>
                            <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                                <i class="feather icon-image"></i> Choose
                                            </a>
                                        </span>
                                <input id="thumbnail" class="form-control" type="hidden" name="image">
                            </div>
                            <img id="holder" style="margin-top:15px; max-height:200px;">
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
    </div>
    <br>

@endsection


@section('script')
    <script src="{{ asset('vendor/laravel-filemanager/js/lfm.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#lfm').filemanager('image');
        });
    </script>
@endsection
