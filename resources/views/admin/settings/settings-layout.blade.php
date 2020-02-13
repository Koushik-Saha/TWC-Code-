@extends('layouts.master')

@section('title','Create Settings')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Settings</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Header Title</small>
                                    <input type="text" name="header_title" class="form-control" id="" placeholder="Header One">
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Footer Logo (175px*53px)</small>
                                    <input type="file" name="header_img" id="" class="form-control">
                                </div>
                            </div>
                                <div class="form-row mb-3">
                                    <div class="col">
                                        <small class="text-uppercase text-dark">Icon (16px*16px)</small>
                                        <input type="file" name="icon_img" id="" class="form-control">
                                    </div>
                                </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <input type="submit" value="Submit" id="" class="btn btn-success text-uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
