@extends('layouts.master')

@section('title', 'Manufacture Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="{{route('brands', ['id' => $manufacturer->id])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Add Manufacturer</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Manufacturer<span
                                            class="required text-danger">*</span></small>
                                    <input type="text" id="name" name="name" class="form-control"
                                           placeholder="Manufacturer" required value="{{ old('name', $manufacturer->name) }}">

                                    @if ($errors->has('name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('name') }}</small>
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
