@extends('layouts.master')

@section('title', $client->name . '- Details')
@section('content')
<h1 class="text-center">Client Details</h1>
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="card comp-card" style="min-height: 310px;">
			<div class="card-body">
				<div class="row">
					<div style="margin: 0 auto;">
						@if($client->image !== null)
                            <img height="80" width="80" src="{{ asset($client->image) }}" class="img-radius">
                        @else
                            <img height="80" width="80" src="{{asset('images/labour-image/man.png')}}" class="img-radius">
                        @endif
					</div>
				</div>
				<br>
				<h5 class="text-center text-success">Name : {{$client->name}}</h5>
				<h6 class="text-center">Phone : {{ mobileNumber($client->mobile) }}</h6>

				<h6 class="text-center">address : {{ $client->address }}</h6>

				<h6 class="text-center">
                    Projects :
                    @foreach($client->clientProjects as $project)
                        <a href="{{ route('project.show', ['id' => $project->project_id]) }}">
                            {{ $project->project_name }}
                        </a>
						@if(!$loop->last)
							,
						@endif
                    @endforeach
                </h6>

                <div class="text-center p-2">
                    <a class="btn btn-warning" href="{{ route('client.edit', ['id' => $client->id ]) }}">
                        <i class="feather icon-edit"></i>
                    </a>
                </div>

			</div>
		</div>
	</div>

	<div class="col-md-4 offset-3">
        <div class="card prod-p-card card-red">
            <div class="card-body">
                <div class="row align-items-center m-b-30">
                    <div class="col">
                        <h6 class="m-b-5 text-white">Total Payable</h6>
                        <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($payable,2) }}</h3>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                    </div>
                </div>
            </div>
        </div>
		<div class="card prod-p-card card-blue">
			<div class="card-body">
				<div class="row align-items-center m-b-30">
					<div class="col">
						<h6 class="m-b-5 text-white">Received</h6>
						<h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($received->sum('payment_amount'),2) }}</h3>
					</div>
					<div class="col-auto">
						<i class="fas fa-money-bill-alt text-c-red f-18"></i>
					</div>
				</div>
			</div>
		</div>
		 <div class="card prod-p-card card-green">
			<div class="card-body">
				<div class="row align-items-center m-b-30">
					<div class="col">
						<h6 class="m-b-5 text-white">Remaining Balance</h6>
						<h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($payable - $received->sum('payment_amount'),2) }}</h3>
					</div>
					<div class="col-auto">
						<i class="fas fa-money-bill-alt text-c-red f-18"></i>
					</div>
				</div>
			</div>
		</div>

	</div>
{{-- 	<div class="col-md-4">
		<div class="card comp-card">
			<div class="card-body">
				<form action="{{route('manager.transection.search')}}" method="post">
					@csrf
					<h3 style="text-align: center;">Search</h3 >
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Start :<span class="red">*</span></label>
		            	<input type="date" class="form-control" id="recipient-name" name="start" required="" >
		            </div>
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">End: <span class="red">*</span></label>
		            	<input name="manager_id" type="hidden" value="{{$manager->user->id}}">
		            	<input type="date" class="form-control" id="recipient-name" name="end">
		            </div>
					<br>
					<div class="form-group" align="center">
						<button type="submit" class="btn btn-mat btn-info btn-block"> Search</button>
					</div>
				</form>
			</div>
		</div>
	</div> --}}
</div>
<div id="ajaxResult">
<div class="row">
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-body">
				<h6 class="text-center">Transaction History</h6>
				<div class="table-responsive">
					<table id="table1" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">For Project</th>
								<th scope="col">Payment Type</th>
								<th scope="col">Received Date</th>
								<th scope="col">Amount</th>
								<th scope="col">Note</th>
							</tr>
						</thead>
						<tbody>
                            @foreach($received as $index => $transaction)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>
										<a href="{{ route('project.show', ['id' => $transaction->project->project_id]) }}">
											{{ $transaction->project->project_name }}
										</a>
									</td>
                                    <td class="text-capitalize">{{$transaction->payment_by}}</td>

                                    <td>{{$transaction->payment_date}}</td>
                                    <td>{{ number_format($transaction->payment_amount, 2) }}</td>
                                    <td> {{ $transaction->payment_note }}
{{--                                        @if(!$loop->last)--}}
{{--                                            ,--}}
{{--                                            <br>--}}
{{--                                        @endif--}}
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

	$('#table2').DataTable( {
	    responsive: true,
	    dom: 'Bfrtip',
	    buttons: [
	        'csv', 'pdf', 'print'
	    ]
	} );
    $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
</script>
@endsection
