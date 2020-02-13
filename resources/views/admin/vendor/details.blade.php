@extends('layouts.master')

@section('title', 'Vendor Details')

@section('style')
<style>
	.form-group {
margin-bottom: unset;
	}
	.form-group {
	margin-bottom: unset;
	}
    label { font-weight: 600; }
</style>
@endsection
@section('content')
<h1 class="text-center">Vendor Details</h1>
<hr>
<div class="row">
	<div class="col-md-4">
		<div class="card comp-card" style="    min-height: 310px;">
			<div class="card-body">
				<div class="row">
					<div style="margin: 0 auto;">
						<img  height="80" width="80" src="{{asset('images/labour-image/man.png')}}" alt="">
					</div>
				</div>
				<br>
				<h5 class="text-center text-success">{{ $vendor->name }}</h5>
				<h6 class="text-center"><i class="feather icon-phone text-dark mr-3"></i> {{ mobileNumber($vendor->mobile) }}</h6>

				<h6 class="text-center"><i class="feather icon-map-pin text-dark mr-3"></i> {{ $vendor->address }}</h6>

			</div>
		</div>
	</div>
	@php
	@endphp
	<div class="col-md-4">
		<div class="card prod-p-card card-red">
			<div class="card-body">
				<div class="row align-items-center m-b-30">
					<div class="col">
						<h6 class="m-b-5 text-white">Total Payable Money</h6>
						<h3 class="m-b-0 f-w-700 text-white">
                            ৳ {{ number_format($vendor->itemLogs->sum('il_cost'),2) }}
                        </h3>
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
						<h6 class="m-b-5 text-white">Total Paid</h6>
                        @if ($total_payable_money >= $total_paid)
                            <h3 class="m-b-0 f-w-700 text-white">
                                ৳ {{ number_format($vendor->vendorPayments->sum('payment_amount'),2) }}
                            </h3>
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
		<div class="card prod-p-card card-yellow">
			<div class="card-body">
				<div class="row align-items-center m-b-30">
					<div class="col">
						<h6 class="m-b-5 text-white">Due</h6>
                        @if ($total_payable_money >= $total_paid)
                            <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($vendor->itemLogs->sum('il_cost') - $vendor->vendorPayments->sum('payment_amount'),2) }}</h3>
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
	</div>
	<div class="col-md-4">
		<div class="card comp-card">
			<div class="card-body">
				<form action="{{ route('vendor.pay') }}" method="post" onsubmit="this.submit.disabled = true;">
					@csrf
					<h3 style="text-align: center;">Pay Money</h3 >
					<input type="hidden" name="vendor_id" value="{{$vendor->id}}">
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Date :<span class="red">*</span></label>
						<input type="date" class="form-control" id="recipient-name" name="date" required="">
					</div>

					<div class="form-group">
						<label for="" class="col-form-label">For Project: <span class="red">*</span></label>
						<select name="project_id" id="" class="form-control" required>
							<option selected disabled>Select Project</option>
							@foreach($project as $projects)
							<option value="{{$projects->project_id}}">{{$projects->project_name}}</option>
							@endforeach
						</select>
					</div>
                    <br>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Refund: <span class="red">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline1" name="refund" value="0" class="custom-control-input" checked>
                            <label class="custom-control-label" for="customRadioInline1">No</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline2" name="refund" value="1" class="custom-control-input">
                            <label class="custom-control-label" for="customRadioInline2">Yes</label>
                        </div>
                    </div>

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
						<label for="recipient-name" class="col-form-label">Amount: <span class="red">*</span></label>
						<input type="number" class="form-control" id="recipient-name" name="amount" required="">
					</div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Note:</label>
                        <textarea class="form-control" id="recipient-name" name="note" rows="3"></textarea>
                    </div>
					<br>
					<div class="form-group" align="center">
                        @if ($total_payable_money >= $total_paid)
                            <button type="submit" class="btn btn-mat btn-info btn-block"> Pay / Refund</button>
                        @else
                            <button type="submit" class="btn btn-mat btn-info btn-block" value="Submit" disabled>You are not authorized</button>
                        @endif

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-body">
				<h6 class="text-center"> Transaction List</h6>
				<div class="table-responsive">
					<table id="table1" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Date</th>
								<th scope="col">Amount</th>
								<th scope="col">Method</th>
								<th scope="col">Project</th>
							</tr>
						</thead>
						<tbody>
							@foreach($vendor->vendorPayments as $index => $transaction)
								@if(Auth::user()->isAdmin() || $transaction->activity->activityBy->id == Auth::id())
									<tr>
										<td>{{$index+1}}</td>
										<td>{{$transaction->payment_date}}</td>
										<td>{{number_format($transaction->payment_amount,2)}}</td>
										<td class="text-capitalize">{{ $transaction->payment_by }}</td>
										<td>
											<a href="{{ route('project.show', ['id' => $transaction->project->project_id]) }}">{{ $transaction->project->project_name }}</a>
										</td>
									</tr>
								@endif
							@endforeach
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
				<h4 class="text-center"> Project List of this Vendor</h4>
				<div class="table-responsive">
					<table id="table2" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Project Name</th>
								<th scope="col">Payable</th>

							</tr>
						</thead>
						<tbody>
							@foreach($vendor->vendorProjects->unique() as $index => $project)
							<tr>
								<td>{{$index+1}}</td>
								<td>{{$project->project_name}}</td>
								<td>
                                    {{number_format($project->itemLogs->where('il_vendor_id', '=', $vendor->id)->sum('il_cost'),2)}}
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
        });
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    </script>

    <script>
        $(document).ready(function() {
            $('#payment_by').change(function() {
                let payment_by = this.value;
                if(payment_by !== 'cash') {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url  : "{{route('vendor.banks')}}",
                        type : "POST",
                        data : { payment_by: payment_by },
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
