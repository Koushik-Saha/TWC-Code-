@extends('layouts.master')

@section('title', 'Attendance Report')

@section('style')
	<link rel="stylesheet" href="{{ asset('js/jqueryPreloader/css/preloader.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.semanticui.min.css">

{{--    Switchery Toggle--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>

{{--    Toastr Notifications--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
<div class="row justify-content-center" style="min-height: 85vh;" id="reportPreloader">
	<div class="col-md-6 col-xl-4">
		<div class="card comp-card">
			<div class="card-body">
				<h3 style="text-align: center;">Attendance List</h3><br>
				<form action="{{route('man_power.attendance_report')}}" method="post" id="labSearch">
					@csrf
					<div class="form-group">
						<select name="pid" id="pid" class="form-control" required="">
							<option disabled="" selected>Select Project To See All Labour</option>
							@foreach($projects as $project)
							<option value="{{$project->project_id}}">{{$project->project_name}}</option>
							@endforeach
						</select>
					</div><br>
					<div class="form-group">
						<label class="">Start Date : </label>
						<input type="date" id="start" name="start" class="form-control">
					</div>
                    <div class="form-group">
                        <label class="">End Date : </label>
                        <input type="date" id="end" name="end" class="form-control">
                    </div>
					<br>
					<div class="form-group">
						<button type="submit" class="btn btn-sm btn-block btn-primary"><span style="font-size: 15px;">Search</span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div id="ajaxResult">
		</div>
	</div>
</div>


@endsection


@section('script')
	<script src="{{ asset('js/jqueryPreloader/js/jquery.preloader.min.js') }}"></script>
	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#labSearch").submit(function(e) {
				e.preventDefault();

				$('#reportPreloader').preloader({ text: 'Processing Your Request' });

				let pid=$("#pid").val();
				let start=$("#start").val();
				let end=$("#end").val();
				//console.log(pid);
				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					url  : "{{route('man_power.attendance_report')}}",
					type : "POST",
					data : {pid: pid, start: start, end: end},
					success : function(response){
                        $('#reportPreloader').preloader('remove');
						$("#ajaxResult").html(response);

						let dt =$('#attendanceOfDay').DataTable( {
							responsive: true,
							//dom: '<".row"<".col-md-4"l><".col-md-4"f><".col-md-4"B>>rt<".row"<".col-md-5"i><".col-md-7"p>>',  //lBfrtip
							dom: "<'row my-3'<'col-sm-12 col-md-4 text-center text-md-left'l><'col-sm-12 col-md-4 text-center'f><'col-sm-12 col-md-4 text-center text-md-right'B>>" +
                                "<'row'<'col-sm-12'<'table-responsive'tr>>>" +
                                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Search..."
                            },
							buttons: [
								{
									extend: 'excelHtml5',
									charset: 'utf-8',
									exportOptions: {
										columns: [ 0, 1, 2, 3, 4, 5, 6 ]
									}
								},
								{
									extend: 'print',
									exportOptions: {
										columns: [ 0, 1, 2, 3, 4, 5, 6 ]
									}
								}
							]
						} );
						$('.dt-button').addClass('ui button');
						$('.dataTables_filter, .dataTables_filter span, .dataTables_filter label, .dataTables_filter input').css('width', '100%');
					},
					error : function(xhr, status) { console.log(xhr) }
				});
			});
		});
	</script>
@endsection
