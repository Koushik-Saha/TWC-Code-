@extends('layouts.master')

@section('title', 'Edit Purchase Item')

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
                    <h3 style="text-align: center;">Edit</h3>
                    <form action="{{route('edit-inventory-vendor', ['id' => $purchase->id])}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="item_id"
                                   value="{{ old('item_id', $purchase->item_id) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Item Price: <span
                                    class="red">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="recipient-name" name="price"
                                   value="{{ old('price', $purchase->price) }}" required="" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Vat: </label>
                            <input type="number" step="0.01" class="form-control" id="recipient-name" name="vat"
                                   value="{{ old('vat', $purchase->vat) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Quantity:  <span class="red">*</span></label>
                            <input required type="number" step="0.01" class="form-control" id="recipient-name" name="quantity"
                                   value="{{ old('quantity', $purchase->quantity) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Amount: </label>
                            <input required type="number" step="0.01" class="form-control" id="recipient" name="amount"
                                   value="{{ old('amount', $purchase->amount) }}" readonly>
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="project_id"
                                   value="{{ $purchase->project_id }}">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Request Code:  <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="request_code"
                                   value="{{ old('request_code', $purchase->request_code) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Payable Amount: </label>
                            <input type="number" class="form-control" id="payment_amount" name="payment_amount">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Select Vendor:  <span class="red">*</span></label>
                            <select id="vendor" name="vendor_id" class="form-control" required
                                    style="color: red">
                                <option>Select a Vendor...</option>
                                @foreach($vendor as $vendors)
                                    <option
                                        value="{{$vendors->id}}"
                                        id="vendor">{{$vendors->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="user_id"
                                   value="{{ $purchase->user_id }}">
                        </div>
                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="recipient-name" name="cartId"
                                   value="{{ $purchase->cartId }}">
                        </div>

                        <br>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-mat btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

