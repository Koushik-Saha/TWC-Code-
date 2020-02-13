<br>
<div class="row">
<div class="col-md-12">
	<div class="card comp-card">
		<div class="card-body">
			<h3 style="text-align: center;">Transferred Items of <span style="color: #f91484;">{{ $project->project_name }}</span> Project</h3>
			<div class="table-responsive">
				<table class="table" id="allProjects">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Item Name</th>
							<th scope="col">Quantity</th>
							<th scope="col">Transferred To</th>
							<th scope="col">Invoice</th>
						</tr>
					</thead>
					<tbody>
						@foreach($project->transferredItemLogs as $index => $inv)
						<tr>
							<th scope="row">{{ $index+1 }}</th>
							<td>
                                {{ $inv->item->item_name }} &nbsp;&nbsp; --- &nbsp;&nbsp; {{ $inv->item->item_unit }}
                            </td>

							<td>
                                @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                    {{ $inv->il_quantity }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('project.show', ['id' => $inv->project->project_id]) }}">
                                    {{ $inv->project->project_name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('items.invoice', ['id' => $inv->il_id]) }}" class="btn btn-success">
                                    <i class="feather icon-eye"></i>
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

</div>


<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function () {
        $('#allProjects').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        } );
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    });
</script>
