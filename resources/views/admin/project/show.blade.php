@extends('layouts.master')

@section('title', $project->name . '- Details')

@section('style')
    <style>
        .bsoft-btn {
            padding: 5px 12px;
        }
        .bsoft-btn i {
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <h1 class="text-center">Project Details</h1>
    <hr>
    <div class="row justify-content-around">
        <div class="col-md-4">
            <div class="card comp-card" style="    min-height: 310px;">
                <div class="card-body">
                    <div class="row">
                        <div style="margin: 0 auto;">
                            @if($project->project_image !== null)
                                <img height="80" width="80" src="{{ imageCache($project->project_image, '80') }}" alt="{{ $project->project_name }}" style="border-radius: 50%;">
                            @else
                                <img height="80" width="80" src="{{asset('images/labour-image/man.png')}}" class="img-radius">
                            @endif
                        </div>
                    </div>
                    <br>
                    <h5 class="text-center text-success">Name: <strong class="font-weight-bold">{{$project->project_name}}</strong></h5>

                    <h6 class="text-center">Address: {{$project->project_location}}</h6>

                    @if(Auth::user()->isAdmin() && $project->project_status !== 'canceled' && $project->project_status !== 'completed')
                        <div class="adminMenu">
                            <div class="text-center p-2">
                                <a class="btn btn-warning bsoft-btn" href="{{ route('project.edit', ['id' => $project->project_id ]) }}">
                                    <i class="feather icon-edit"></i>
                                </a>
                            </div>

                            <div class="text-center p-2">
                                <button class="btn btn-success bsoft-btn" data-toggle="modal" data-target="#completeProjectModal" title="Mark as Complete">
                                    <i class="feather icon-check-circle"></i>
                                </button>

                                @if($project->project_status !== 'hold')
                                    <button class="btn btn-warning bsoft-btn" data-toggle="modal" data-target="#holdProjectModal" title="Hold This Project">
                                        <i class="feather icon-alert-triangle"></i>
                                    </button>
                                @else
                                    <button class="btn btn-primary bsoft-btn" data-toggle="modal" data-target="#activeProjectModal" title="Start This Project Again">
                                        <i class="feather icon-alert-triangle"></i>
                                    </button>
                                @endif

                                <button class="btn btn-danger bsoft-btn" data-toggle="modal" data-target="#cancelProjectModal" title="Cancel This Project">
                                    <i class="feather icon-x-circle"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Complete Modal -->
                        <div class="modal fade" id="completeProjectModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('project.change_status') }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="project" value="{{ $project->project_id }}">
                                        <input type="hidden" name="status" value="completed">
                                        <div class="modal-header">
                                            <h5 class="modal-title w-100 text-center" id="changeStatusModalLabel">Complete Project</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are You Sure Complete This Project?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Complete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if($project->project_status !== 'hold')
                            <!-- Hold Modal -->
                            <div class="modal fade" id="holdProjectModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('project.change_status') }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="project" value="{{ $project->project_id }}">
                                            <input type="hidden" name="status" value="hold">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="changeStatusModalLabel">Hold Project</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are You Sure Hold This Project?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning">Hold</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                        <!-- Active Modal -->
                            <div class="modal fade" id="activeProjectModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('project.change_status') }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="project" value="{{ $project->project_id }}">
                                            <input type="hidden" name="status" value="active">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="changeStatusModalLabel">Start Project Again</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are You Sure Start This Project Again?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning">Start</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelProjectModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('project.change_status') }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="project" value="{{ $project->project_id }}">
                                        <input type="hidden" name="status" value="canceled">
                                        <div class="modal-header">
                                            <h5 class="modal-title w-100 text-center" id="changeStatusModalLabel">Cancel Project</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are You Sure Cancel This Project?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
            <div class="col-md-4">
                <div class="card prod-p-card card-blue mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Estimated Cost</h6>
                                <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($project->project_price, 2) }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card prod-p-card card-green mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Received Balance</h6>
                                <h3 class="m-b-0 f-w-700 text-white">৳ {{ ($received) ? number_format($received->sum('payment_amount'), 2) : 0 }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card prod-p-card card-danger mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-5 text-white">Total Expenses</h6>
                                <h3 class="m-b-0 f-w-700 text-white">৳ {{ number_format($expenses->sum('payment_amount'), 2)}}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-alt text-c-red f-18"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
        <div class="col-md-4">
            <div class="card comp-card" style="    min-height: 310px;">
                <div class="card-body">
                    <div class="row">
                        <div style="margin: 0 auto;">
                            @if($project->client->image !== null)
                                <img height="80" width="80" src="{{ imageCache($project->client->image, '80') }}" class="img-radius" alt="{{ $project->client->name }}">
                            @else
                                <img height="80" width="80" src="{{asset('images/labour-image/man.png')}}" class="img-radius" alt="{{ $project->client->name }}">
                            @endif
                        </div>
                    </div>
                    <br>
                    <h5 class="text-center text-success">
                        Client: <a class="font-weight-bold" href="{{ route('client.show', ['id' => $project->client->id]) }}" style="font-size: inherit;">
                            {{ $project->client->name }}
                        </a>
                    </h5>
                    <h6 class="text-center">Phone: <span class="font-weight-bold">{{ mobileNumber($project->client->mobile) }}</span></h6>

                    <h6 class="text-center">Address: {{$project->client->address}}</h6>

                    @if(Auth::user()->isAdmin())
                        <div class="text-center p-2">
                            <a class="btn btn-warning bsoft-btn" href="{{ route('client.edit', ['id' => $project->client->id ]) }}">
                                <i class="feather icon-edit"></i>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <h3 class="card-header text-center">Managers Assigned To This Project</h3>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table1" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Mobile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assigned as $index => $adm)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><a href="{{ route('administrators.show', $adm->id) }}">{{ $adm->name }}</a></td>
{{--                                            <td>{{ $adm->name }}</td>--}}
                                            <td>{{ mobileNumber($adm->mobile) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card comp-card">
                    <h3 class="text-center card-header">Assign Manager to This Project</h3>
                    <div class="card-body">
                        <form action="{{route('project.assign', ['id' => $project->project_id])}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label sr-only">Manager Name: <span class="red">*</span></label>
                                <select class="custom-select" id="recipient-name" name="admin" required>
                                    <option selected>----- Select Manager -----</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}">
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" align="center">
                                <button type="submit" class="btn btn-mat btn-primary"> Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h3 class="text-center">Received From Client</h3>
                        <div class="table-responsive">
                            <table id="rcvdTable" class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"> Date</th>
                                    <th scope="col">Method</th>
                                    <th scope="col"> Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($received as $index => $transaction)
                                    <tr>
                                        <td>{{$index+1}}</td>

                                        <td>{{$transaction->payment_date}}</td>
                                        <td class="text-capitalize">{{$transaction->payment_by}}</td>
                                        <td>{{number_format($transaction->payment_amount,2)}}</td>

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
    @endif
    <div id="ajaxResult">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h3 class="text-center">Expenses of The Project</h3>
                        <div class="table-responsive">
                            <table id="expensesTable" class="table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"> Date</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Manager Name</th>
                                    <th scope="col">Purpose</th>
                                    <th scope="col"> Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($expenses as $index => $transaction)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->payment_date)->toFormattedDateString() }}</td>
                                        <td class="text-capitalize">{{$transaction->payment_by}}</td>
                                        @foreach($assigned as $index => $adm)
                                            <td><a href="{{ route('administrators.show', $adm->id) }}">{{ $adm->name }}</a></td>
                                        @endforeach
                                        <td>{{$transaction->payment_purpose}}</td>
                                        <td>{{number_format($transaction->payment_amount,2)}}</td>
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
    <script>
        $('#table1').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        });

        $('#table2').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        } );

        $('#rcvdTable').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        } );

        $('#expensesTable').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        } );
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    </script>
@endsection

