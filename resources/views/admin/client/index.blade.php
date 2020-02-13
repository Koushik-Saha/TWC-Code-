@extends('layouts.master')

@section('title', 'All Clients')

@section('style')
    <style>
        .form-group {
            margin-bottom: unset;
        }

        .form-group {
            margin-bottom: unset;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <a class="btn waves-effect waves-light btn-warning" href="{{ route('project.add') }}">
                <i class="fas fa-plus"></i>Create Clients
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card comp-card">
                <div class="card-body">
                    <h5 style="text-align: center;">Client List</h5>
                    <div class="table-responsive">
                        <table id="table1" class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">phone</th>
                                <th scope="col">Projects</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach($clients as $index => $client)
                                    <td>{{$index+1}}</td>
                                    <td><a href="{{route('client.show',$client->id)}}">{{$client->name}}</a></td>
                                    <td>{{ mobileNumber($client->mobile) }}</td>
                                    <td>
                                        @foreach($client->clientProjects as $project)
                                            <a href="{{ route('project.show', ['id' => $project->project_id]) }}">
                                                {{ $project->project_name }}
                                            </a>
                                            @if(!$loop->last)
                                                ,
                                                <br>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('client.edit', ['id' => $client->id]) }}"
                                           class="btn btn-primary" title="Edit Client">
                                            <i class="feather icon-edit"></i>
                                        </a>
                                    </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
@endsection


@section('script')
    <script>
        $(document).on('click', '#deleteBtn', function (el) {
            el.preventDefault();
            var postId = $(this).data("id");

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Deleting...", {
                            icon: "success",
                        });
                        window.location.href = window.location.href = "delete/" + postId;
                    }
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
        $('#table1').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        });
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    </script>
@endsection
