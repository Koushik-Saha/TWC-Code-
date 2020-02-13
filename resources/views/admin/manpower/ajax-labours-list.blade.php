<br>
<div class="row">
<div class="col-md-12">
	<div class="card comp-card">
		<div class="card-body">
			<h3 style="text-align: center;">All Staffs of <span style="color: #f91484;">{{$project->project_name}}</span> Project</h3>
			<div class="table-responsive">
				<table class="table" id="allProjects">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Name</th>
							<th scope="col">Designation</th>
							<th scope="col">Status</th>
							<th scope="col">Salary</th>
							<th scope="col">Total <br>Payable</th>
							<th scope="col">Paid</th>
							<th scope="col">Due</th>
							<th scope="col">Mobile</th>
{{--                            <th scope="col" style="max-width: 50px;">Note</th>--}}
							@can('manage-man-power')
								<th scope="col">Added By</th>
								<th scope="col">Action</th>
							@endcan

						</tr>
					</thead>
					<tbody>
						@foreach($staffs as $index => $labour)
						<tr>
							<th scope="row">{{$index+1}}</th>
							<td><a href="{{route('man_power.show', ['project' => $project->project_id, 'id' => $labour->id])}}">{{$labour->name}}</a></td>

							<td>
								@if($labour->role->role_slug == 'machine')
									<span class="label label-success">{{$labour->role->role_name}}</span>
								@elseif($labour->role_slug == 'labour')
									<span class="label label-warning">{{$labour->role->role_name}}</span>
								@else
									<span class="label label-primary">{{$labour->role->role_name}}</span>
								@endif
							</td>
                            <td>
                                <input type="checkbox"  data-id="{{ $labour->id }}" name="status" class="js-switch" {{ $labour->status == 1 ? 'checked' : '' }}>
                            </td>
							<td>{{ number_format($labour->salary, 2) }} </td>

							@php($payable = $labour->attendances->sum('attendance_payable_amount'))
							@php($paid = $labour->staffPayments->sum('payment_amount'))

							<td>{{ number_format($payable, 2) }} </td>
							<td>{{ number_format($paid, 2) }} </td>
							<td>{{ number_format(($payable - $paid), 2) }} </td>
							<td>{{ mobileNumber($labour->mobile) }}</td>
{{--                            <td>{!! $labour->note !!}</td>--}}
							@can('manage-man-power')
								<td>{{ ($labour->addedBy()) ? $labour->addedBy()->name : 'N/A' }}</td>
								<td>
									<a href="{{ route('man_power.edit', ['id' => $labour->id]) }}" class="btn btn-warning">
										<i class="feather icon-edit-2"></i>
									</a>
								</td>
							@endcan
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>



</div>


<script>
    let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
        let switchery = new Switchery(html,  { size: 'small' });
    });

    $(document).ready(function(){

        $('#allProjects').on('change', '.js-switch', function(){
            // ... skipped ...
            let status = $(this).prop('checked') === true ? 1 : 0;
            let userId = $(this).data('id');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('man_power.salary_report-s') }}',
                data: {'status': status, 'user_id': userId},
                success: function (data) {
                    toastr.options.closeButton = true;
                    toastr.options.closeMethod = 'fadeOut';
                    toastr.options.closeDuration = 100;
                    toastr.success(data.message);
                }
            });
        });
    });

    $('#allProjects').DataTable({
        responsive: true,
        language : {
            sLengthMenu: "Show _MENU_"
        },
    })

</script>
