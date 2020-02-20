@extends('layouts.master')

@section('title', 'Edit Item')

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
                    <h3 style="text-align: center;">Edit {{$inventory->item_id}}</h3>
                    <form action="{{route('edit-request-inventory', ['id' => $inventory->id])}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="item_id"
                                   value="{{ old('item_id', $inventory->item_id) }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Item Price: <span
                                    class="red">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="recipient-name" name="price"
                                   value="{{ old('price', $inventory->price) }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Vat: </label>
                            <input type="number" step="0.01" class="form-control" id="recipient-name" name="vat"
                                   value="{{ old('vat', $inventory->vat) }}">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Quantity:  <span class="red">*</span></label>
                            <input required type="number" step="0.01" class="form-control" id="recipient-name" name="quantity"
                                   value="{{ old('quantity', $inventory->quantity) }}">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: </label>
                            <input required type="number" step="0.01" class="form-control" id="recipient" name="amount"
                                   value="{{ old('amount', $inventory->amount) }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="mother_category_id"
                                   value="{{ $inventory->mother_category_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="category_id"
                                   value="{{ $inventory->category_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="sub_category_id"
                                   value="{{ $inventory->sub_category_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="manufacture_id"
                                   value="{{ $inventory->manufacture_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="project_id"
                                   value="{{ $inventory->project_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="request_date"
                                   value="{{ $inventory->request_date }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="request_code"
                                   value="{{ $inventory->request_code }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="request_id"
                                   value="{{ $inventory->request_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="cartId"
                                   value="{{ $inventory->cartId }}">
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
    </div>

@endsection

