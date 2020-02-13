@extends('layouts.master')

@section('title', 'Items')

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
        <div class="col-md-4 offset-4">
            <div class="card comp-card">
                <div class="card-body">
                    <h3 style="text-align: center;">All Items of All Project</h3><br>
                    <form action="{{route('items.all-lists')}}" method="post" id="labSearch">
                        @csrf
                        <div class="form-group">
{{--                                @foreach($item as $items)--}}
{{--                                    {{ $items->item_name }}--}}
{{--                                @endforeach--}}
                            <select name="pid" id="pid" class="form-control" required="">
                                <option disabled="" selected>Select All to see All Item</option>
{{--                                @foreach($item as $items)--}}
{{--                                    <option value="{{ $items->item_name }}">All</option>--}}
{{--                                @endforeach--}}
                                <option value="all">All</option>
                            </select>
                        </div><br>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-block btn-primary"><span style="font-size: 15px;">SEE ALL ITEM</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>

    </div>
    <br>
    <div id="ajaxResult">

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
                    url  : "{{route('items.all-lists')}}",
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

            $("#itemSearch").submit(function(e) {
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
            });
        });


    </script>
@endsection
