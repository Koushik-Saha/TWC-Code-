@extends('layouts.master')

@section('title', 'All Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">All Item List</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="itemList">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Mother Category</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Sub Category</th>
                                    <th scope="col">Manufacture</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Item Unit</th>
                                    <th scope="col">Item Price</th>
                                    <th scope="col">Reusable</th>
                                    <th scope="col">Item Image</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inventory as $index => $item)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>{{$item->motherCategory->mother_name}}</td>
                                        <td>
                                            @if( $item->category_id === null)
                                                <span class="label label-danger">Not Selected</span>
                                            @else
                                                {{$item->category->category_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if( $item->sub_category_id === null)
                                                <span class="label label-danger">Not Selected</span>
                                            @else
                                                {{$item->subCategory->sub_category_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if( $item->manufacture_id === null)
                                                <span class="label label-danger">Not Selected</span>
                                            @else
                                                {{$item->manufacture->name}}
                                            @endif
                                        </td>
                                        <td>{{$item->item_name}}</td>
                                        <td>{{$item->item_unit}}</td>
                                        <td>{{number_format($item->item_price,2)}}</td>
                                        <td>
                                            @if( $item->item_reusable)
                                                <span class="label label-success">Yes</span>
                                            @else
                                                <span class="label label-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if( $item->item_image === null)
                                                <span class="label label-danger">Not Selected</span>
                                            @else
                                                <img src="{{ asset($item->item_image) }}" alt="BD SOFT IT" style="max-width: 80px; max-height: 80px">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('edit-inventory', ['id' => $item->id]) }}"
                                               class="btn btn-sm btn-outline-success">Edit</a>
                                            <a id="deleteBtn" data-id="{{$item->id}}" href="#"
                                               class="btn btn-sm btn-danger">Delete</a>
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
