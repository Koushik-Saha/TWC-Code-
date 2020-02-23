@extends('layouts.master')

@section('title', 'Item Details')

@section('style')

@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card comp-card" style="min-height: 310px;">
                <div class="card-body">
                    <div class="image-responsive" align="center">
                        @if($item->item_image == "" || !isset($item->item_image) || $item->item_image == NULL)
                            <img height="120" class="img-radius img-thumbnail" width="120"
                                 src="{{asset('files/assets/images/materials.jpg')}}" alt="">
                        @else
                            <img height="120" class="img-radius img-thumbnail" width="120"
                                 src="{{ asset($item->item_image) }}" alt="">
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <h5>Name :
                                <span
                                    style="color: darkblue;">{{ $item->item_name . ' ( ' . $item->item_unit . ' )' }}</span>
                            </h5>
                            <h6>Item Initial Price :
                                <span style="color: red;">{{ number_format($item->item_price,2) . ' TK' }}</span>
                            </h6>
                            <h6>
                                Mother Category :
                                <span style="color: #e65100;">{{ $item->motherCategory->mother_name }}</span>
                            </h6>
                            <h6>
                                Category :
                                <span style="color: #e65100;">{{ $item->category->category_name }}</span>
                            </h6>
                            <h6>
                                Sub Category :
                                <span style="color: #e65100;">{{ $item->subCategory->sub_category_name }}</span>
                            </h6>
                            <h6>
                                Manufacture :
                                <span style="color: #e65100;">{{ $item->manufacture->name }}</span>
                            </h6>
                            <h6>
                                Reusable:
                                @if( $item->item_reusable)
                                    <span class="label label-success">Yes</span>
                                @else
                                    <span class="label label-danger">No</span>
                                @endif
                            </h6>
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
                        <table class="table table-hover" id="itemHistory">
                            <thead>
                            <tr>
{{--                                <th scope="col">#</th>--}}
                                <th scope="col">Name</th>
                                <th scope="col">Manager Price</th>
                                <th scope="col">Vat</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Project</th>
                                <th scope="col">Requested User</th>
                                <th scope="col">Request Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($itemList as $index => $log)
                                <tr>
{{--                                    <th scope="row">{{ $index++ }}</th>--}}
                                    <td>{{ $log->item_id }}</td>
                                    <td>{{ number_format($log->price,2) }} TK</td>
                                    <td>{{ number_format($log->vate,2) }}%</td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ number_format($log->amount,2) }} TK</td>
                                    <td>
                                        <a href="{{ route('project.show', ['id' => $log->project_id]) }}"
                                           title="See Project Details">
                                            {{ $log->requestProject->project_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('administrators.show', ['id' => $log->request_id]) }}"
                                           title="See User Information">
                                            {{ $log->requestUser->name }}
                                        </a>
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ \Carbon\Carbon::parse($log->request_date)->toFormattedDateString() }}
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

    @if(Auth::user()->isAdmin())
        <script>
            $('#itemHistory').DataTable({
                "scrollY": true,
                "scrollX": true,
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'csv', 'pdf', 'print'
                ]
            });
            $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
        </script>
    @else
        <script>
            $('#itemHistory').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ]
            });
            $('.buttons-print').addClass('btn btn-success mr-1');
        </script>
    @endif

@endsection
