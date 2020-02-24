@extends('layouts.master')

@section('title', 'Items List By Projects')

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
        <div class="col-md-8 offset-2">
            <div class="card comp-card">
                <div class="card-body">
                    <h3 style="text-align: center;">Items of a Project</h3><br>
                    <form action="{{route('items.index')}}" method="post" id="labSearch">
                        @csrf
                        <div class="form-group">
                            <select name="pid" id="pid" class="form-control" required="">
                                <option disabled="" selected>Select Project To See Item List</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div><br>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-block btn-primary"><span style="font-size: 15px;">Search</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--<div class="col-md-4">
        </div>
        <div class="col-md-4">
            <div class="card comp-card">
                <div class="card-body">
                    <h3 style="text-align: center;">Transferred Items</h3><br>
                    <form action="{{route('items.transferred')}}" method="post" id="itemSearch">
                        @csrf
                        <div class="form-group">
                            <select name="ipid" id="ipid" class="form-control" required="">
                                <option disabled="" selected>Select Project To See Item List</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div><br>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-block btn-primary"><span style="font-size: 15px;">Search</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="ajaxResult"> --}}
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
                    url  : "{{route('items.index')}}",
                    type : "POST",
                    data : {pid:pid},
                    success : function(response){
                        if (response.status == "error") {
                            alert(response.message);
                        }
                        else{
                            $("#ajaxResult").html(response);
                        }
                    },
                    error : function(xhr, status){

                    }
                });
            });
            {{--$("#itemSearch").submit(function(e) {
                e.preventDefault();
                let ipid=$("#ipid").val();

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{route('items.transferred')}}",
                    type : "POST",
                    data : {pid: ipid},
                    success : function(response){
                        if (response.status == "error") {
                            alert(response.message);
                        }
                        else{
                            $("#ajaxResult").html(response);
                        }
                    },
                    error : function(xhr, status){

                    }
                });
            });--}}
        });
    </script>
@endsection
