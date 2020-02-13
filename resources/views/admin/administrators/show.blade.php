@extends('layouts.master')

@section('title', $administrator->name)

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
<h1 class="text-center">Administrator Details</h1>
<hr>
<div class="row justify-content-around">
	<div class="col-md-4">
		<div class="card comp-card" style="min-height: 310px;">
			<div class="card-body">
				<div class="row">
					<div style="margin: 0 auto;">
                        @if($administrator->image !== null)
                            <img height="80" width="80" src="{{imageCache($administrator->image)}}" alt="{{ $administrator->name }}">
                        @else
						    <img height="80" width="80" src="{{asset('images/labour-image/man.png')}}" alt="{{ $administrator->name }}">
                        @endif
					</div>
				</div>
				<br>
				<h5 class="text-center text-success">Name : {{$administrator->name}}</h5>
				<h6 class="text-center">Phone : {{mobileNumber($administrator->mobile)}}</h6>

				<h6 class="text-center">Email : {{$administrator->email}}</h6>

			</div>
		</div>
	</div>
	<div class="col-md-4">
        <div class="card prod-p-card card-success">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-5 text-white">Total Received</h6>
                        <h3 class="m-b-0 f-w-700 text-white">
                            ৳ {{ ($administrator->isAdmin()) ? 'N/A' : number_format($administrator->managerPayments->sum('payment_amount'),2) }}
                        </h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                    </div>
                </div>
            </div>
        </div>
		<div class="card prod-p-card card-red">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<h6 class="m-b-5 text-white">Total Expense 	</h6>
                        @if ($total_received >= $total_expense)
                            <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($administrator->payments()->where('payment_type', '=', 'debit')->sum('payment_amount'),2) }}</h3>
                        @else
                            <p>You spend lot of money, please contact with administrator</p>
                        @endif
					</div>
					<div class="col-auto">
						<i class="fas fa-money-bill-alt text-c-red f-18"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="card prod-p-card card-primary">
			<div class="card-body">
				<div class="row align-items-center">
					<div class="col">
						<h6 class="m-b-5 text-white">Remaining Balance</h6>
						<h3 class="m-b-0 f-w-700 text-white">
                            @if($administrator->isAdmin())
                                N/A
                            @else
                                @if ($total_received >= $total_expense)
                                    ৳ {{ number_format($administrator->managerPayments->sum('payment_amount') - $administrator->payments()->where('payment_type', '=', 'debit')->sum('payment_amount'),2) }}
                                @else
                                    <p>Contact with Administrator</p>
                                @endif
                            @endif
                        </h3>
					</div>
					<div class="col-auto">
						<i class="fas fa-money-bill-alt text-c-red f-18"></i>
					</div>
				</div>
			</div>
		</div>

        <div class="card prod-p-card card-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-5 text-white">Total Manpower Expense Balance</h6>
                        <h3 class="m-b-0 f-w-700 text-white">
                            @if($administrator->isAdmin())
                                N/A
                            @else
                                @php
                                    $transMan = $administrator->payments()->where('payment_purpose', '=', 'salary')->get();
                                @endphp
                                ৳ {{ number_format($transMan->sum('payment_amount'),2) }}
                            @endif
                        </h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card prod-p-card card-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-5 text-white">Total Vendor Expense Balance</h6>
                        @php
                            $vendorTransaction = $administrator->payments()
                                ->select(
                            DB::raw('
                            payment_to_user,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_for_project) AS payment_for_project,
                            MAX(created_at) AS created_at
                            '))
                            ->where('payment_purpose', '=', 'vendor_payment')
                            ->orWhereRaw('payment_purpose', '=', 'vendor_refund')
                            ->groupBy('payment_to_user')
                            ->get();

                            $total = 0;
                        @endphp
                        <h3 class="m-b-0 f-w-700 text-white">
                            @if($administrator->isAdmin())
                                N/A
                            @else
                                @foreach($vendorTransaction as $index => $vtransaction)
                                        @php
                                            $total += $vtransaction->payment_amount
                                        @endphp
                                @endforeach
                                ৳ {{ number_format($total,2)  }}
                            @endif
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
<div id="ajaxResult">
<div class="row">
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-body">
				<h6 class="text-center">Transaction List (Manpower)</h6>
				<div class="table-responsive">
					<table id="table1" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Worker Name</th>
								<th scope="col">For Project</th>
								<th scope="col">Date</th>
								<th scope="col">Amount</th>

							</tr>
						</thead>
						<tbody>
                            @php
                                $transMan = $administrator->payments()
                                ->select(
                                DB::raw('
                                payment_to_user,
                                sum(payment_amount) AS payment_amount,
                                MAX(payment_for_project) AS payment_for_project,
                                MAX(created_at) AS created_at
                                '))
                                ->where('payment_purpose', '=', 'salary')
                                ->groupBy('payment_to_user')
                                ->get();
                            @endphp
							@foreach($transMan as $index => $transaction)
							<tr>
								<td>{{$index+1}}</td>
								<td>
                                    <a href="{{ route('man_power.show', ['project' => $transaction->project->project_id, 'id' => ($transaction->toUser) ? $transaction->toUser->id : $transaction->payment_id]) }}">
                                        {{ ($transaction->toUser) ? $transaction->toUser->name : 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('project.show', ['id' => $transaction->project->project_id]) }}">
                                        {{ $transaction->project->project_name }}
                                    </a>
                                </td>
								<td>{{$transaction->created_at->format('d M, Y')}}</td>
								<td>{{number_format($transaction->payment_amount,2)}}</td>
							</tr>
							@endforeach
							<tr>
								<th></th>
								<th></th>
								<th></th>
                                <th>Total</th>
                                <th>{{  number_format($transMan->sum('payment_amount'),2) }}</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<br>


<div class="row">
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-body">
				<h6 class="text-center">Transaction List (Vendor)</h6>
				<div class="table-responsive">
					<table id="table3" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Vendor Name</th>
                                <th scope="col">For Project</th>
								<th scope="col">Date</th>
								<th scope="col">Amount</th>

							</tr>
						</thead>

						<tbody>
                        @php
                            $vendorTransaction = $administrator->payments()
                                ->select(
                            DB::raw('
                            payment_to_user,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_for_project) AS payment_for_project,
                            MAX(created_at) AS created_at
                            '))
                            ->where('payment_purpose', '=', 'vendor_payment')
                            ->orWhereRaw('payment_purpose', '=', 'vendor_refund')
                            ->groupBy('payment_to_user')
                            ->get();

                            $total = 0;
                        @endphp
							@foreach($vendorTransaction as $index => $vtransaction)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>
                                            <a href="{{route('vendor.show', ['id' => $vtransaction->toUser->id])}}">
                                                {{ $vtransaction->toUser->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('project.show', ['id' => $vtransaction->project->project_id]) }}">
                                                {{ $vtransaction->project->project_name }}
                                            </a>
                                        </td>
                                        <td>{{$vtransaction->created_at->format('d M, Y')}}</td>
                                        <td>{{number_format($vtransaction->payment_amount,2)}}</td>
                                    </tr>
                                    @php($total += $vtransaction->payment_amount)
							@endforeach
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th>Total</th>
                                <th>{{  number_format($total,2) }}</th>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<br>

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

<script>
	$('#table1').DataTable( {
	    responsive: true,
	    dom: 'Bfrtip',
	    buttons: [
	        'csv', 'pdf', 'print'
	    ]
	});

	$('#table3').DataTable( {
	    responsive: true,
	    dom: 'Bfrtip',
	    buttons: [
	        'csv', 'pdf', 'print'
	    ]
	} );
    $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
</script>
@endsection
