@extends('layouts.master')

@section('title', 'Loans')

@section('content')

    <a href="{{ route('loan.new') }}" class="btn btn-primary mb-3">
        New Loan
    </a>
    <a href="{{ route('loan.pay') }}" class="btn btn-success mb-3">
        Pay Loan
    </a>

    <div class="row">
        <div class="col-md-12">
            <div class="card comp-card">
                <div class="card-body">
                    <h4 class="text-center">All Loans</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="loansTable">
                            <thead>
                            <tr>
                                <th scope="col" style="vertical-align: middle; text-align: center;">#</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Details</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Amount</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Remaining</th>
                                <th scope="col" style="vertical-align: middle; text-align: center;">Date</th>
                                <th scope="col" style="vertical-align: middle;">Note</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $index => $loan)
                                <tr>
                                    <td style="vertical-align: middle; text-align: center;">{{ $index+1 }}</td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        <a href="{{ route('loan.show', ['id' => $loan->loan_id]) }}" class="font-weight-bold" style="font-size: 14px;">
                                            From: {{ $loan->loan_from }}
                                            <br>
                                            Loan No: {{ $loan->loan_no }}
                                        </a>
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{ number_format($loan->loan_amount, 2) }}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{ number_format(($loan->loan_amount - $loan->loan_paid), 2) }}
                                    </td>
                                    <td style="vertical-align: middle; text-align: center;">
                                        {{ $loan->created_at->format('d M, y ') }}
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {!! $loan->loan_note !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#loansTable').DataTable();
        } );
    </script>
@endsection
