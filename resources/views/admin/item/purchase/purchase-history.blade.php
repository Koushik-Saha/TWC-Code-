@extends('layouts.master')

@section('title', 'Purchase History')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">Purchase Package List</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="packageList">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Purchase From</th>
                                    <th scope="col">Purchase By</th>
                                    <th scope="col">Requested Bundle List</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($itemList as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>
                                            <a href="{{ route('project.show', ['id' => $item->project_id]) }}"
                                               title="See Project Details">
                                                {{ $item->purchaseProject->project_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('administrators.show', ['id' => $item->user_id]) }}"
                                               title="See User Information">
                                                {{ $item->purchaseUser->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('purchase-item-details', ['id' => $item->cartId])}}" title="See Requested Item">
                                                {{ $item->cartId }} - {{ $index++ }}
                                            </a>
                                        </td>
                                        <td>
                                            @if( $item->status === 0 )
                                                <span class="label label-danger">Not Purchased</span>
                                            @else
                                                <span class="label label-success">Purchased</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->created_at->format('d M Y, h:i A') }}
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
    </div>


@endsection


@section('script')

    <script>
        $('#packageList').DataTable({

        });
    </script>

@endsection
