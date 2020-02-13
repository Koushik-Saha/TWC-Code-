@extends('layouts.master')

@section('title', 'Add Loan')

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
                    <h3 style="text-align: center;">Add Loan</h3>
                    <form action="{{route('loan.new') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="loan_name" class="col-form-label">Loan Name: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="loan_name" name="loan_name" required="" value="{{ old('loan_name') }}">
                        </div>

                        <div class="form-group">
                            <label for="loan_from" class="col-form-label">Loan From : <span class="red">*</span></label>
                            <input type="text" class="form-control" id="loan_from" name="loan_from" required="" value="{{ old('loan_from') }}">
                        </div>

                        <div class="form-group">
                            <label for="loan_no" class="col-form-label">Loan No :</label>
                            <input type="text" class="form-control" id="loan_no" name="loan_no" value="{{ old('loan_no') }}">
                        </div>

                        <div class="form-group">
                            <label for="loan_amount" class="col-form-label">Amount :  <span class="red">*</span></label>
                            <input type="number" class="form-control" id="loan_amount" name="loan_amount" required value="{{ old('loan_amount') }}">
                        </div>


                        <div class="form-group">
                            <label for="loan_note" class="col-form-label">Note :</label>
                            <textarea class="form-control" name="loan_note" id="loan_note" cols="30" rows="5">{!! old('loan_note') !!}</textarea>
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
    </div>
    <br>

@endsection


@section('script')

@endsection
