@extends('layouts.master')

@section('title', 'Item Details Of Project')

@section('style')

@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-4">
		<div class="card comp-card" style="min-height: 310px;">
            <div class="card-body">
                <div class="image-responsive" align="center">
                    @if($item->item_image == "" || !isset($item->item_image) || $item->item_image == NULL)
                        <img height="120" class="img-radius img-thumbnail" width="120" src="{{asset('files/assets/images/materials.jpg')}}" alt="">
                    @else
                        <img height="120" class="img-radius img-thumbnail" width="120" src="{{ asset($item->item_image) }}" alt="">
                    @endif
                </div><hr>
                <div class="row">
                    <div class="col-md-12" align="center">
                        <h6>Name : {{ $item->item_name . ' ( ' . $item->item_unit . ' )' }}</h6>
                        <h6>Project : {{ $project->project_name }}</h6>
                        <h6>
                            Total Buy: {{ $itemLogs->sum('il_quantity') }}
                            <span style="color: red;">{{ '( ' . number_format($itemLogs->sum('il_cost'),2) . ' TK)' }}</span>
                        </h6>
                        <h6>
                            Total Transferred: {{ $itemTransferLogs->sum('il_quantity') }}
                            <span style="color: red;">{{ '( -' . $itemTransferLogs->sum('il_cost') . ' TK)' }}</span>
                        </h6>
                        <h6>Current Quantity: {{ $itemLogs->sum('il_quantity') - $itemTransferLogs->sum('il_quantity') }}</h6>
                        <h5 style="color: red;">Cost : {{ number_format($itemLogs->sum('il_cost') - $itemTransferLogs->sum('il_cost'),2) }} TK</h5>
                    </div>
                </div>
            </div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
        <div class="card comp-card">
            <div class="card-header">
                <h3 class="w-100 text-center">Item History</h3>
            </div>
            <div class="card-body">

                <div class="table-responsive-sm">
                    <table class="table table-hover" id="logsTable">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Cost</th>
                            <th scope="col">By</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($itemLogs as $index => $log)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>{{ $log->item->item_name }}</td>
                                <td>{{ $log->il_quantity }}</td>
                                <td>{{ number_format($log->il_cost,2) }} TK</td>
                                <td>{{ $log->activity->activityBy->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y h:i A') }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card comp-card">
            <div class="card-header">
                <h3 class="w-100 text-center">Transfer History</h3>
            </div>
            <div class="card-body">

                <div class="table-responsive-sm">
                    <table class="table table-hover" id="transLogsTable">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Cost</th>
                            <th scope="col">By</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($itemTransferLogs as $index => $log)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>{{ $log->item->item_name }}</td>
                                <td>{{ $log->il_quantity }}</td>
                                <td>{{ $log->il_cost }} TK</td>
                                <td>{{ $log->activity->activityBy->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y h:i A') }} </td>
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
<div id="ajaxResult">

</div>

@endsection






@section('script')
    <script>
        $('#logsTable').DataTable();
        $('#transLogsTable').DataTable();
    </script>
@endsection
