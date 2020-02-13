@extends('layouts.master')

@section('title', 'Edit Category Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="{{route('category', ['id' => $motherCategory->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Add a mother category</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Mother Category<span
                                            class="required text-danger">*</span></small>
                                    <input type="text" id="mother_name" name="mother_name" class="form-control"
                                           placeholder="Mother Category" value="{{ old('mother_name', $motherCategory->mother_name) }}" required>

                                    @if ($errors->has('mother_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('mother_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-inverse">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
    </div>
@endsection
