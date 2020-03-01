@extends('layouts.master')

@section('title', 'Item List')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('approve-item')}}" method="post" enctype="multipart/form-data" id="frm" >
                    @csrf
                    <div class="card comp-card">
                        <div class="card-body">
                            <h5 class="w-100 text-center">Item List</h5>
                            <div class="table-responsive">
                                <table class="table table-hover" id="itemList">
                                    <thead>
                                    <tr>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Vat</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($itemList as $index => $item)
                                        <tr>
                                            <th>
                                                <input style="width: 200px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][item_id]"
                                                       id="item_id" value="{{ $item->item_id}}" readonly/>
                                            </th>
                                            <th>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][price]"
                                                       id="price" value="{{ $item->price }}" readonly/>
                                            </th>
                                            <th>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][vat]"
                                                       id="vat" value="{{ $item->vat }}" readonly/>
                                            </th>
                                            <th>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][quantity]"
                                                       id="quantity" value="{{ $item->quantity }}" readonly/>
                                            </th>
                                            <th>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][amount]"
                                                       id="amount" value="{{ $item->amount }}"
                                                       readonly/>
                                            </th>
                                            <th hidden>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][request_code]"
                                                       id="request_code" value="{{ $item->request_code }}"
                                                       readonly/>
                                            </th>
                                            <th hidden>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][user_id]"
                                                       id="request_code" value="{{ $item->request_id }}"
                                                       readonly/>
                                            </th>
                                            <th hidden>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][cartId]"
                                                       id="cartId" value="{{ $item->cartId }}"
                                                       readonly/>
                                            </th>
                                            <th hidden>
                                                <input style="width: 100px; background-color: white; border: none"
                                                       type="text" name="addmore[{{ $item->item_id }}][project_id]"
                                                       id="project_id" value="{{ $item->project_id }}"
                                                       readonly/>
                                            </th>
                                            <th>
                                                <a href="{{ route('edit-request-inventory', ['id' => $item->id]) }}"
                                                   class="btn btn-sm btn-outline-primary">Edit</a>
                                                <a href="{{ route('delete-request-inventory', ['id' => $item->id]) }}"
                                                   class="btn btn-sm btn-danger">Delete</a>
                                            </th>
                                            <td>
                                                <input type="checkbox" data-id="{{ $item->id }}" name="status_req" class="js-switch" {{ $item->status_req == 1 ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>Total</th>
                                        <th>{{ number_format($itemList->sum('price'),2) }}</th>
                                        <th></th>
                                        <th>{{ number_format($itemList->sum('quantity'),2) }}</th>
                                        <th>{{ number_format($itemList->sum('amount'),2) }}</th>
                                        <th></th>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="form-row mb-3">
                                    <div class="col text-center" id="counter">
                                        @if( $item->status_req === 0 )
                                            <input type="submit" value="Approved Item" class="btn btn-outline-success text-uppercase">
                                        @else
                                            <h3 class="label-danger">You have approved this package</h3>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>
        $('#itemList').DataTable({});

        $(document).on('click', '#deleteBtn', function (el) {
            var mcId = $(this).data("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Request!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("You have deleted a Request", {
                            icon: "success",
                        });
                        window.location.href = window.location.href = "request/delete/" + mcId;
                    }


                });
        });

    </script>

    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { size: 'small' });
        });

        $(document).ready(function(){

            $('#itemList').on('change', '.js-switch', function(){
                // ... skipped ...
                let status_req = $(this).prop('checked') === true ? 1 : 0;
                let userId = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('change-approve-status') }}',
                    data: {'status_req': status_req, 'user_id': userId},
                    success: function (data) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                    }
                });
            });
        });

    </script>



@endsection
