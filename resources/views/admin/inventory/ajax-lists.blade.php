<br>
<div class="row">
<div class="col-md-12">
	<div class="card comp-card">
		<div class="card-body">
			<h3 style="text-align: center;">All Items of <span style="color: #f91484;">{{ $project->project_name }}</span> Project</h3>
			<div class="table-responsive">
				<table class="table" id="allProjects">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Item Name</th>
                            <th scope="col">Unit Name</th>
                            <th scope="col">Quantity</th>
                            @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                <th scope="col">Initial Price</th>
                                <th scope="col">Total Cost</th>
                            @endif
                            <th scope="col">Final Cost</th>
							<th scope="col">Reusable</th>
                            <th scope="col">Transfer</th>
                            @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
							    <th scope="col">Action</th>
                            @endif
						</tr>
					</thead>
					<tbody>
						@foreach($project->items->unique() as $index => $inv)
						<tr>
							<th scope="row">{{ $index+1 }}</th>
							<td>
                                <a href="{{ route('items.show', ['pid' => $project->project_id, 'id' => $inv->item_id]) }}" title="See Details">
                                    {{ $inv->item_name }}
                                </a>
                            </td>
                            <td>
                                {{ $inv->item_unit }}
                            </td>
                            <td>
                                @if($trans = $inv->itemLogs()->where('il_project_from', '=', $project->project_id)->get())
                                    {{ $inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_quantity') - $trans->sum('il_quantity') }}
                                @else
                                    {{ $inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_quantity') }}
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                            <td>
                                    {{ number_format($inv->item_price,2) }}
                            </td>
                            @endif
                            @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                <td>
                                    @if($trans = $inv->itemLogs()->where('il_project_from', '=', $project->project_id)->get())
                                        {{ number_format($inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_cost') - $trans->sum('il_cost'),2) }}
                                    @else
                                        {{ number_format($inv->itemLogs()->where('il_project_id', '=', $project->project_id)->sum('il_cost'),2) }}
                                    @endif
                                </td>
                            @endif
                            <td>
                                {{ number_format($inv->item_price_final,2) }}
                            </td>
							<td>
								@if( $inv->item_reusable  )
									<span class="label label-success">Yes</span>
								@else
									<span class="label label-danger">No</span>
								@endif
							</td>
                            <td>
                                @if( $inv->item_reusable  )
                                    <button type="button" class="btn btn-warning transferBtn" id="{{ $inv->item_id }}" data-toggle="modal" data-target="#transferModal" title="Transfer To Another Project" style="padding: 0 8px;">
                                        <i class="feather icon-trending-up" id="{{ $inv->item_id }}"></i>
                                    </button>
                                @else
                                    <span class="font-weight-bold">N/A</span>
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
                                <td>
                                     <a href="{{ route('items.edit', ['id' => $inv->item_id]) }}"
                                        class="btn btn-sm btn-outline-success">Edit</a>
                                </td>
                            @endif
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
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('items.transfer') }}" method="post">
                <div class="modal-header">
                    <h4 class="modal-title text-center w-100" id="exampleModalLabel">Transfer Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="project_from" value="{{ $project->project_id }}">
                        <input type="hidden" name="item_id" value="" id="itemId">

                        <div class="form-group">
                            <label for="project_to">Transfer To: <span class="text-danger">*</span></label>
                            <select name="project_to" id="project_to" class="custom-select">
                                <option selected disabled>--- Select A Project ---</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="quantity">Transfer Quantity: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>
                    <div class="form-group">
                        <label for="note">Note: </label>
                        <textarea rows="3" class="form-control" id="note" name="note" style="resize: none;"></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--@section('script')--}}
<script>
    $(document).ready(function () {
        $('.transferBtn').on('click', function (e) {
            $('#itemId').val(e.target.id);
        });
    });
</script>

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
        });
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    });


</script>

{{--@endsection--}}
