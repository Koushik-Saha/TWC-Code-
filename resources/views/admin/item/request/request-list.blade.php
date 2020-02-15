@extends('layouts.master')

@section('title', 'Request List')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">All Requested Item List</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="itemList">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Submit By</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($itemList as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <th>
                                            <a href="{{ route('administrators.show', $item->id) }}" title="See User Information">
                                                {{ $item->requestUser->name }}
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('project.show', ['id' => $item->project_id]) }}" title="See Project Details">
                                                {{ $item->requestProject->project_name }}
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('request-item-list', ['id' => $item->request_id])}}" title="See Requested Item">
                                               Package {{ $item->request_id }}
                                            </a>
                                        </th>
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
