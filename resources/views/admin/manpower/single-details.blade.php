@extends('layouts.master')

@section('title', 'Staff Details')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<style>
	.form-group {
		margin-bottom: unset;
	}
	.form-group {
		margin-bottom: unset;
	}
	.search-form{
	  position: relative;
	  width: 250px;

	}
	.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    border-radius: 5px;
    /* box-shadow: inset -5px 20px 8px 0px; */
    border: 1px solid orange;
}
select {
    text-transform: none;
    padding: 4px;
    background: orange;
    border: orange;
    margin-bottom: 5px;
}
</style>
@endsection
@section('content')
<h2 style="text-align: center;">Profile of {{ $labour->name }}</h2><br>
<div class="row">
	<div class="col-md-4">
		<div class="card comp-card">
			<div class="card-body">
				<div class="image-responsive" align="center">
					@if($labour->image =="" || !isset($labour->image) || $labour->image==NULL)
					    <img height="120" width="120" src="{{asset('images/labour-image/man.png')}}" alt="">
					@else
					<img height="120" width="120" src="{{ imageCache($labour->image, '200') }}" alt="">
					@endif
				</div><hr>
				<div class="row">
					<div class="col-md-12" align="center">
						<h6>Name : {{$labour->name}}</h6>
						<h6>Project : {{ $project->project_name }}</h6>
						<p>Father Name : {{ $labour->fathers_name }}</p>
						<p>Address : {{ $labour->address }}</p>
						<p>Phone : {{ mobileNumber($labour->mobile) }}</p>
						<p>Section : {{ $labour->section }}</p>
						<h5 style="color: red;">Salary : {{number_format($labour->salary,2)}}</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card prod-p-card card-red">
			<div class="card-body">
				<div class="row align-items-center m-b-30">
					<div class="col">
						<h6 class="m-b-5 text-white">Total Payable Money</h6>
						<h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($payable,2) }}</h3>
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
                        @if ($payable >= $paid)
                            <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($paid,2) }}</h3>
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
						<h6 class="m-b-5 text-white" >Due</h6>
                        @if ($payable >= $paid)
                            <h3 class="m-b-0 f-w-700 text-white" id="helloDue"  > {{ number_format($payable - $paid,2) }}</h3>
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
				<form action="{{ route('man_power.pay') }}" method="post" onsubmit="this.submit.disabled = true;">
					@csrf
					<h3 style="text-align: center;">Pay Money</h3 >
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Date :<span class="red">*</span></label>
		            	<input type="date" class="form-control" id="recipient-name" name="date" required>
		            </div>
					<div class="form-group">
		            	<label for="recipient-name" class="col-form-label">Amount: <span class="red">*</span></label>
		            	<input name="project_id" type="hidden" value="{{ $project->project_id }}">
		            	<input name="labour_id" type="hidden" value="{{ $labour->id }}">
		            	<input name="given_by" type="hidden" value="{{ Auth::id() }}">
		            	<input type="number" class="form-control" id="recipient-name" name="amount" onblur="myCalculation()">
		            </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Note :<span class="red">*</span></label>
                        <textarea class="form-control" id="recipient-name" name="note" rows="5" style="resize: none;"></textarea>
                    </div>
					<br>
					<div class="form-group" align="center">
                        @if ($payable >= $paid)
                            <button type="submit" class="btn btn-mat btn-info btn-block"> Pay</button>
                        @else
                            <button type="submit" class="btn btn-mat btn-info btn-block" value="Submit" disabled>You are not authorized</button>
                        @endif
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="card comp-card">
			<div class="card-header">
				<h3>Transaction History</h3>
			</div>
			<div class="card-body">

				<div class="table-responsive-sm">
					<table class="table table-hover" id="tranTable">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Date</th>
					      <th scope="col">Amount</th>
					    </tr>
					  </thead>
					  <tbody>
					  	@foreach($labour->staffPayments as $index => $transaction)
					    <tr>
					      <th scope="row">{{ $index + 1 }}</th>
					      <td>{{ $transaction->payment_date }}</td>
					      <td>{{ number_format($transaction->payment_amount,2) }} tk</td>
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
<div class="row justify-content-center" align="center">
	<div class="col-12" id='calendar'>
		<div class="card comp-card">
			<div class="card-body">
				{!! $calendar->calendar() !!}
			</div>
		</div>

	</div>
</div>
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
{!! $calendar->script() !!}

<script>
	// $('#tranTable').DataTable();

    $('#tranTable').DataTable( {
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'pdf', 'print'
        ]
    });
    $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');

</script>

<script>
    let dueAmount = document.getElementById('helloDue').innerText;
   function myCalculation() {
       var valuess = document.getElementById('recipient-name');
       const a = valuess.value;
       console.log(a);
   }



    console.log(dueAmount);
</script>
@endsection
