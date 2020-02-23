@extends('layouts.master')

@section('title', 'Purchase Item')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">Purchase Item</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="itemList">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Vat</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Request Code</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($itemList as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <th>
                                            {{ $item->item_id }}
                                        </th>
                                        <th>
                                            {{ number_format($item->price,2) }}
                                        </th>
                                        <th>
                                            {{ number_format($item->vat,2) }}
                                        </th>
                                        <th>
                                            {{ $item->quantity }}
                                        </th>
                                        <th>
                                            {{ number_format($item->amount,2) }}
                                        </th>
                                        <th>
                                            {{ $item->request_code }}
                                        </th>
                                        <th hidden>
                                            {{ $a = $item->cartId }}
                                        </th>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th></th>
                                    <th>Total</th>
                                    <th>{{ number_format($itemList->sum('price'),2) }}</th>
                                    <th></th>
                                    <th>{{ number_format($itemList->sum('quantity'),2) }}</th>
                                    <th>{{ number_format($itemList->sum('amount'),2) }}</th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                            <form action="{{ route('change-status', $a)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row mb-3">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-outline-success text-uppercase" name="status" value="1">Purchase</button>
{{--                                        <input type="submit" value="{{ $a }}" id="" name="status"--}}
{{--                                               class="btn btn-outline-success text-uppercase">--}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>
        $('#itemList').DataTable({

        });

        $(document).on('click', '#deleteBtn', function (el) {
            var mcId = $(this).data("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Product!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("You have deleted a product", {
                            icon: "success",
                        });
                        window.location.href = window.location.href = "items-del/delete/" + mcId;
                    }


                });
        });

    </script>

@endsection
