@extends('layouts.master')

@section('title', 'Purchase Package List')

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
                                            <a href="{{ route('purchase-item', ['id' => $item->cartId])}}" title="See Requested Item">
                                                {{ $item->cartId }} - {{ $index++ }}
                                            </a>
                                        </td>
                                        <td>
                                            @if( $item->status === 0 )
                                                <span class="label label-danger">Not Purchase</span>
                                            @else
                                                <span class="label label-success">Purchase</span>
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
