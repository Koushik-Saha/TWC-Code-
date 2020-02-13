<br>
<div class="row">
<div class="col-md-12">
	<div class="card comp-card">
		<div class="card-body">
			<h3 style="text-align: center;">All Items of All Project List</h3>
			<div class="table-responsive">
				<table class="table" id="allProjects">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Item Name</th>
                            <th scope="col">Item Unit</th>
                            <th scope="col">Per Unit Price</th>
{{--							<th scope="col">Quantity</th>--}}
{{--							<th scope="col">Reusable</th>--}}
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($item as $index => $inv)
						<tr>
							<th scope="row" style="width: 30px">{{ $index+1 }}</th>
							<td style="word-break: break-word; word-wrap: break-word">
                                <a href="{{ route('items.showItemsDetails', ['id' => $inv->item_id]) }}" title="See Details">
                                    {{ $inv->item_name }}
                                </a>
                            </td>
                            <td style="width: 30px">{{ $inv->item_unit }}</td>
                            <td style="width: 30px">{{ number_format($inv->item_price,2) }}</td>

{{--							<td>--}}
{{--                                {{ $inv->il_quantity }}--}}

{{--                                @if($trans = $inv->itemLogs()->where('il_project_from', '=', $project->project_id)->get())--}}
{{--                                    {{ $inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_quantity') - $trans->sum('il_quantity') }}--}}
{{--                                @else--}}
{{--                                    {{ $inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_quantity') }}--}}
{{--                                @endif--}}
{{--                            </td>--}}

							<td style="width: 30px">
								@if( $inv->item_reusable  )
									<span class="label label-success">Yes</span>
								@else
									<span class="label label-danger">No</span>
								@endif
							</td>
{{--                            <td>--}}
{{--                                @if( $inv->item_reusable  )--}}
{{--                                    <button type="button" class="btn btn-warning transferBtn" id="{{ $inv->item_id }}" data-toggle="modal" data-target="#transferModal" title="Transfer To Another Project" style="padding: 0 8px;">--}}
{{--                                        <i class="feather icon-trending-up" id="{{ $inv->item_id }}"></i>--}}
{{--                                    </button>--}}
{{--                                @else--}}
{{--                                    <span class="font-weight-bold">N/A</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

</div>


<!-- Modal -->

{{--@section('script')--}}

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script>
        $('#allProjects').DataTable( {
            responsive: true,
            language : {
                sLengthMenu: "Show _MENU_"
            },
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        });
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');

    </script>
{{--<script>--}}
{{--    $(document).ready(function () {--}}
{{--        $('.transferBtn').on('click', function (e) {--}}
{{--            $('#itemId').val(e.target.id);--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}

{{--@endsection--}}
