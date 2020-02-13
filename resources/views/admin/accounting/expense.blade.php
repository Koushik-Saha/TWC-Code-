@extends('layouts.master')

@section('title', 'Expenses')

{{-- testing line.. deleteabla --}}
@section('content')
<div class="row">
	<div class="col-12">
        <div class="rbt-data-table">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center w-100">Expenses</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="expenseTable" style="width: 100%;">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Method</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Received By</th>
                                <th scope="col">Purpose</th>
                                <th scope="col">Project</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $index => $payment)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->toFormattedDateString() }}
                                    </td>
                                    <td>
                                        {{ ucfirst(strtolower($payment->payment_by)) }}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ number_format($payment->payment_amount,2)}}
                                    </td>
                                    <td>
                                        {{ $payment->name }}
                                    </td>
                                    <td>
                                        {{ $payment->payment_purpose }}
                                    </td>
                                    <td>
                                        <a href="{{ route('project.show', $payment->project_id) }}" title="See Project Details">
                                            {{ $payment->project_name }}
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
{{--		<expense-data-table--}}
{{--				:projects="{{ json_encode($projects) }}">--}}
{{--		</expense-data-table>--}}
	</div>
</div>
<br>


<div class="row">
    <div class="col-12">
        <div class="rbt-data-table">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center w-100">Loan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="expenseTable1" style="width: 100%;">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Method</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Received By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paymentsExpense as $index => $payment)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->toFormattedDateString() }}
                                    </td>
                                    <td>
                                        {{ ucfirst(strtolower($payment->payment_by)) }}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ number_format($payment->payment_amount,2)}}
                                    </td>
                                    <td>
                                        {{ $payment->name }}
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        {{--            <income-data-table--}}
        {{--                :projects="{{ json_encode($projects) }}"--}}
        {{--                >--}}
        {{--            </income-data-table>--}}

    </div>
</div>
@endsection


@section('script')
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    @if(Auth::user()->isAdmin())
        <script>
            $('#expenseTable').DataTable( {
                order: [[ 0, 'desc' ]],
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'pdf', 'print'
                ]
            } );
            $('#expenseTable1').DataTable( {
                order: [[ 0, 'desc' ]],
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'pdf', 'print'
                ]
            } );
            $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
        </script>
    @else
        <script>
            $('#expenseTable').DataTable( {
                order: [[ 0, 'desc' ]],
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ]
            } );
            $('.buttons-print').addClass('btn btn-success mr-1');
        </script>
    @endif

@endsection


