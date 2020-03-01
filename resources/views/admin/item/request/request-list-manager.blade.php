@extends('layouts.master')

@section('title', 'Request List')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">All Requested Bundle List</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="itemList">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Vat</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Project</th>
                                    <th scope="col">Request Code</th>
                                    <th scope="col">Bundle</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($itemList as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>
                                            {{ $item->item_id }}
                                        </td>
                                        <td>
                                            {{ $item->price }}
                                        </td>
                                        <td>
                                            {{ $item->vat }}
                                        </td>
                                        <td>
                                            {{ $item->amount }}
                                        </td>
                                        <td>
                                            <a href="{{ route('project.show', ['id' => $item->project_id]) }}"
                                               title="See Project Details">
                                                {{ $item->requestProject->project_name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->request_code }}
                                        </td>
                                        <td>
                                            {{ $item->cartId }}
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
        $('#itemList').DataTable({});

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
