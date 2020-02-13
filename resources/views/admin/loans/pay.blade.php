@extends('layouts.master')

@section('title', 'Pay Loan')

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
                    <h3 style="text-align: center;">Pay Loan</h3>
                    <form action="{{route('loan.pay') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="loan_id" class="col-form-label">Loan: <span class="red">*</span></label>
                            <select name="loan_id" id="loan_id" class="form-control" required>
                                <option selected disabled>--- Select Loan ---</option>
                                @foreach($loans as $loan)
                                    <option value="{{ $loan->loan_id }}">{{ $loan->loan_name . ' --- ' . $loan->loan_from }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>

                        <div class="form-group">
                            <label for="payment_by" class="col-form-label">By: <span class="red">*</span></label>
                            <select name="payment_by" id="payment_by" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="check">Check</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>
                        <br>
                        <div id="bankAccounts"></div>


                        <div class="form-group">
                            <label for="amount" class="col-form-label">Amount :  <span class="red">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount" required value="{{ old('amount') }}">
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

    <script>
        $(document).ready(function() {
            $('#payment_by').change(function() {
                let payment_by = this.value;
                if(payment_by !== 'cash') {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url  : "{{ route('loan.banks') }}",
                        type : "GET",
                        success : function(response){
                            $('#bankAccounts').html(response);
                        },
                        error : function(xhr, status){
                            console.log(xhr);
                        }
                    });
                }
                else {
                    $('#bankAccounts').html(null);
                }
            });
        });
    </script>

@endsection
