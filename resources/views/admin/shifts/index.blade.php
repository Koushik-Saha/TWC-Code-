@extends('layouts.master')

@section('title', 'List')

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
            <div class="card comp-card">
                <div class="card-body">
                    <form action="{{ route('shift.show') }}" method="post" id="labSearch">
                        @csrf
                        <div class="form-group">
                            <label for="pid" class="label label-inverse-success">See Shift List</label>
                            <select name="pid" id="pid" class="form-control" required="">
                                <option disabled="" selected>Select Project To See All Shifts</option>
                                @foreach($projects as $project)
                                    <option value="{{$project->project_id}}">{{$project->project_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-block hor-grd btn-grd-success"><span style="font-size: 15px;">See Shifts</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3"></div>

        <div class="col-md-5">
           <div class="card p-1">
               <form action="{{ route('shift.add') }}" method="post" id="shiftAdd">
                   @csrf
                   <div class="form-group">
                       <label for="pid" class="label label-inverse-primary">Select Project</label>
                       <select name="project_id" id="pid" class="form-control" required="">
                           <option disabled="" selected>Select Project To Add Shifts</option>
                           @foreach($projects as $project)
                               <option value="{{$project->project_id}}">{{$project->project_name}}</option>
                           @endforeach
                       </select>
                   </div>

                   <div class="form-group">
                       <label for="shiftName" class="label label-inverse-primary">Shift Name</label>
                       <input placeholder="Please enter shift name" type="text" id="shiftName" name="name" class="form-control" value="{{ old('name') }}" required>
                   </div>

                   <div class="form-group">
                       <label for="start_time" class="label label-inverse-primary">Shift Start</label>
                       <input placeholder="Please enter shift start time" type="time" id="start_time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                   </div>

                   <div class="form-group">
                       <label for="end_time" class="label label-inverse-primary">Shift Ends</label>
                       <input placeholder="Please enter shift end time" type="time" id="end_time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                   </div>

                   <br>
                   <div class="form-group">
                       <button type="submit" class="btn btn-sm btn-block hor-grd btn-grd-success"><span style="font-size: 15px;">Add Shift</span></button>
                   </div>
               </form>
           </div>
        </div>
    </div>
    <br>
    <div id="ajaxResult">
        <div class="row" align="center">
            <div class="col-md-12">
                <div class="card proj-t-card">
                    <div class="card-body">
                        <div class="row align-items-center m-b-30">
                            <div class="col-auto">
                                <i class="fas fa-search text-c-red f-30"></i>
                            </div>
                            <div class="col p-l-0">
                                <h2 class="m-b-5">Your Search Result Will Appear Here!</h2>
                                <h6 class="m-b-0 text-c-red">Live Update</h6>
                            </div>
                        </div>
                        <div class="row align-items-center text-center">
                            <div class="col">
                                <h4 class="m-b-0"><label class="label label-primary">Projects</label> </h4></div>
                            <div class="col"><i class="fas fa-exchange-alt text-c-red f-18"></i></div>
                            <div class="col">
                                <h4 class="m-b-0"><label class="label label-primary">Shifts </label></h4></div>
                        </div>
                        <h6 class="pt-badge bg-c-red"><i class="fas fa-users"></i></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')

    <script type="text/javascript">
        $(document).ready(function() {

            $("#labSearch").submit(function(e) {
                e.preventDefault();
                let pid=$("#pid").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('shift.show') }}",
                    type : "POST",
                    data : {pid:pid},
                    success : function(response){
                        $("#ajaxResult").html(response);
                    },

                    error : function(xhr, status){
                    }
                });
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
        $('#allProjects').DataTable( {
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'pdf', 'print'
            ]
        } );
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    </script>


@endsection
