@extends('layouts.master')
{{-- testing line.. deleteabla --}}

@section('title', 'Bank Details')

@section('content')


<div class="row">

    @if(isset($bank))
		<div class="col-12">
			<div class="row justify-content-center">
				<div class="col-md-6">
					<div class="card prod-p-card card-success">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col">
									<h5 class="m-b-5 text-white">
										Name: {{ $bank->bank_account_name }}
									</h5>
									<h5 class="m-b-5 text-white">
										NO: {{ $bank->bank_account_no }}
									</h5>
									<h5 class="m-b-5 text-white">
										User: {{ ($bank->user) ? $bank->user->name : 'Office Account' }}
									</h5>
									<h5 class="m-b-5 text-white">
										Bank: {{ ($bank->bank_name) ? $bank->bank_name : 'N/A' }}
									</h5>
									<h5 class="m-b-5 text-white">
										Branch: {{ ($bank->bank_branch) ? $bank->bank_branch : 'N/A' }}
									</h5>
									<h3 class="m-b-0 f-w-700 text-white">
										Balance: {{ $balance }}
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
	@endif

	<div class="col-md-12">
		@if(isset($bank))
			<div class="card comp-card">
				<div class="card-body">
					<h4 class="text-center">{{ isset($bank) ? $bank->bank_account_name : 'Cash' }} - Ledger</h4>
					<div class="table-responsive">
						<table id="table1" class="table table-hover" id="bankDetails">
							<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Purpose</th>
								<th scope="col">Project</th>
								<th scope="col">Type</th>
								<th scope="col">From</th>
								<th scope="col">To</th>
								<th scope="col">Amount</th>
								<th scope="col">Date</th>
								<th scope="col">Transaction By</th>
								<th scope="col">Note</th>
							</tr>
							</thead>
							<tbody>
							@foreach($payments as $index => $payment)
								<tr>
									<td>{{ $index }}</td>
									<td class="text-capitalize">
										{{ Str::contains($payment->payment_purpose, '_') ? Str::before($payment->payment_purpose, '_') . ' ' . Str::after($payment->payment_purpose, '_') : $payment->payment_purpose }}
									</td>
									<td>
										<a href="{{ ($payment->project) ? route('project.show', ['id' => $payment->project->project_id]) : '#' }}">
											{{ ($payment->project) ? $payment->project->project_name : '' }}
										</a>
									</td>
									<td class="text-capitalize">{{ ($payment->payment_type) ? $payment->payment_type : 'N/A' }}</td>
									<td class="text-capitalize">
										{{ $payment->fromUser ? $payment->fromUser->name : 'N/A' }}
									</td>
									<td class="text-capitalize">
										{{ $payment->toUser ? $payment->toUser->name : 'N/A' }}
									</td>
									<td>
										@if($payment->payment_purpose == 'employee_transfer' || $payment->payment_purpose == 'office_withdraw' || $payment->payment_type == 'debit')
											{{ $payment->payment_amount }}
										@elseif($payment->payment_purpose == 'employee_refund')
											{{ Str::after($payment->payment_amount , '-') }}
										@elseif($payment->payment_type == 'credit' || $payment->payment_purpose == 'office_deposit')
											{{ $payment->payment_amount }}
										@endif
									</td>
									<td>{{ $payment->created_at->format('d M, Y') }}</td>
									<td>{{ $payment->activity->activityBy->name }}</td>
									<td>{{ $payment->payment_note }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@else
            <div class="row">
                <div class="col-12">
                    <div class="rbt-data-table">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center w-100">Cash Leger</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="expenseTable" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Method</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Amounts</th>
                                            <th scope="col">Purpose</th>
                                            <th scope="col">Project</th>
                                            <th scope="col">From</th>
                                            <th scope="col">To</th>
                                            <th scope="col">Received By</th>

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
                                                <td>
                                                    {{ ucfirst(strtolower($payment->payment_type)) }}
                                                </td>
                                                <td class="font-weight-bold">
                                                    {{ number_format($payment->payment_amount,2)}}
                                                </td>
                                                <td>
                                                    {{ ucfirst(strtolower($payment->payment_purpose)) }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('project.show', $payment->project_id) }}" title="See Project Details">
                                                        {{ $payment->project_name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $payment->fromUser_name }}
                                                </td>
                                                <td>
                                                    {{ $payment->toUser_name }}
                                                </td>
                                                <td>
                                                    {{ $payment->activityUser_name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
{{--                    			<transactions-data-table--}}
{{--                    					:projects="{{ json_encode($projects) }}">--}}
{{--                    			</transactions-data-table>--}}
                </div>
            </div>
            <br>


            <div class="row">
                <div class="col-12">
                    <div class="rbt-data-table">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center w-100">Loans</h4>
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
                                        @foreach($paymentsloanacash as $index => $payment)
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
{{--			<transactions-data-table--}}
{{--					:projects="{{ json_encode($projects) }}">--}}
{{--			</transactions-data-table>--}}
                </div>
            </div>

		@endif
	</div>
</div>

@endsection


@section('script')
<script>
	$(document).ready(function() {
        $('#bankDetails').DataTable(
        );
    });
</script>

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
