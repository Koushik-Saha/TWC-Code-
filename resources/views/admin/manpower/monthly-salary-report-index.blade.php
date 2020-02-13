@extends('layouts.master')

@section('title', 'List')

@section('style')


    <style>
        .form-group label {
            margin: 5px auto;
            font-size: 12px;
            font-weight: 600;
        }


        .form-group label.title {
            font-weight: 600;
            font-size: 13px;
            padding: 8px 0;
            margin-bottom: 15px;
        }

    </style>
@endsection
@section('content')
    <div class="row">
{{--        <div class="col-md-4 offset-4" >--}}
{{--            <div class="card comp-card">--}}
{{--                <div class="card-body">--}}
{{--                    <form action="{{ route('man_power.search_staff') }}" method="post" id="labSearch">--}}
{{--                        @csrf--}}
{{--                        <div class="form-group text-center">--}}
{{--                            <label for="pid" class="label label-inverse-success w-100 title">See Labour List</label>--}}
{{--                            <select name="pid" id="pid" class="form-control" required="">--}}
{{--                                <option disabled="" selected>Select Project To See All Staffs</option>--}}
{{--                                @foreach($projects as $project)--}}
{{--                                    <option value="{{$project->project_id}}">{{$project->project_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div><br>--}}
{{--                        <div class="form-group">--}}
{{--                            <button type="submit" class="btn btn-sm btn-block hor-grd btn-grd-success"><span style="font-size: 15px;">See List</span></button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
                <div class="col-md-4 offset-4">
                    <div class="card comp-card">
                        <div class="card-body">
                            <form action="#" method="post" id="salaryReportForm">
                                @csrf
                                <div class="form-group text-center">
                                    <label for="pids" class="label label-inverse-info w-100 title">Staff Monthly Salary Report</label>
                                    <select name="pid" id="pids" class="form-control" required>
                                        <option disabled selected>Select Project To See Report</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <br>

                                <div class="form-group text-center">
                                    <label for="month" class="label label-inverse-info">Select Month</label>
                                    <input type="month" name="month" id="month" class="form-control" value="{{ date('Y-m') }}" required>
                                </div>

                                <br>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-block hor-grd btn-grd-warning"><span style="font-size: 15px;">See Report</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        {{--        <div class="col-md-4">--}}
        {{--            <div class="card comp-card">--}}
        {{--                <div class="card-body">--}}
        {{--                    <form action="#" method="post" id="labAttendence">--}}
        {{--                        @csrf--}}
        {{--                        <div class="form-group text-center">--}}
        {{--                            <label for="pid1" class="label label-inverse-success w-100 title">Take Staff Attendance</label>--}}
        {{--                            <select name="pid1" id="pid1" class="form-control" required>--}}
        {{--                                <option disabled="" selected>Select Project To Take Attendance</option>--}}
        {{--                                @foreach($projects as $project)--}}
        {{--                                    <option value="{{$project->project_id}}">{{$project->project_name}}</option>--}}
        {{--                                @endforeach--}}
        {{--                            </select>--}}
        {{--                        </div><br>--}}
        {{--                        <div class="form-group">--}}
        {{--                            <button type="submit" class="btn btn-sm btn-block hor-grd btn-grd-info"><span style="font-size: 15px;">Take Attendance</span></button>--}}
        {{--                        </div>--}}
        {{--                    </form>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
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
                                <h4 class="m-b-0"><label class="label label-primary">workers </label></h4></div>
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
                    url  : "{{ route('man_power.search_staff') }}",
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

        //salary report
        $(document).ready(function() {
            $("#salaryReportForm").on('submit', function(e) {
                e.preventDefault();
                let pid = $("#pids").val();
                let month = $('#month').val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('man_power.salary_report') }}",
                    type : "POST",
                    data : { pid: pid, month: month },
                    success: function(response) {
                        //console.log(response);
                        $("#ajaxResult").html(response);
                    },

                    error: function(xhr, status) {
                        console.log(xhr, status);
                    }
                });
            });
        });

        // ajax request for attendance
        $(document).ready(function() {
            $("#labAttendence").submit(function(e) {
                e.preventDefault();
                let pid = $("#pid1").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('man_power.search_attendance') }}",
                    type : "POST",
                    data : {pid:pid},
                    success : function(response){
                        $("#ajaxResult").html(response);
                        // alert(response);
                        // console.log(response);
                    },

                    error : function(xhr, status){
                        // alert('There is some error.Try after some time.');
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
        });
        $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
    </script>


@endsection
