@extends('layouts.master')
{{-- testing line.. deleteabla --}}

@section('title', 'Bank Details')

@section('content')


    <div class="row">

        <div class="col-12">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card prod-p-card card-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="m-b-5 text-white">
                                        Name: {{ $loan->loan_name }}
                                    </h5>
                                    <h5 class="m-b-5 text-white">
                                        NO: {{ $loan->loan_no }}
                                    </h5>
                                    <h5 class="m-b-5 text-white">
                                        From: {{ $loan->loan_from }}
                                    </h5>
                                    <h3 class="m-b-0 f-w-700 text-white">
                                        Amount: {{ number_format($loan->loan_amount, 2) }}
                                    </h3>
                                    <h3 class="m-b-0 f-w-700 text-white">
                                        Remaining: {{ number_format(($loan->loan_amount - $loan->loan_paid), 2) }}
                                    </h3>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('#bankDetails').DataTable();
        });
    </script>
@endsection
