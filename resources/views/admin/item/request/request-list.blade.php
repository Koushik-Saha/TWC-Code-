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
                                    <th scope="col">Submit By</th>
                                    <th scope="col">From</th>
                                    <th scope="col">Bundle</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($itemList as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>
                                            <a href="{{ route('administrators.show', ['id' => $item->request_id]) }}"
                                               title="See User Information">
                                                {{ $item->requestUser->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('project.show', ['id' => $item->project_id]) }}"
                                               title="See Project Details">
                                                {{ $item->requestProject->project_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('request-item-list', ['id' => $item->cartId])}}"
                                               title="See Requested Item">
                                                {{ $item->cartId }} - {{ $index++ }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->created_at->format('d M Y, h:i A') }}
                                        </td>
                                        <td>
                                            @if( $item->status_req === 1 )
                                                <i class="fas fa-check-circle bg-c-blue"></i>
                                            @else
                                                <i class="fas fa-times-circle bg-c-red"></i>
                                            @endif
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
