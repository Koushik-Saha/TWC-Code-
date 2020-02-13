@extends('layouts.master')

@section('title', 'Create New Item')

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
@if(Auth::user()->isAdmin() || Auth::user()->isAccountant())
@section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="card comp-card">
                <div class="card-body">
                    <h3 style="text-align: center;">Create New Unit of Item</h3>
                    <form action="{{route('items.create')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Name: <span class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="name"
                                   value="{{ old('name') }}" required="">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Item Unit: <span
                                    class="red">*</span></label>
                            <input type="text" class="form-control" id="recipient-name" name="unit"
                                   value="{{ old('unit') }}" required="">
                        </div>
                        <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Per Unit Price <span class="red">*</span></label>
                                <input type="text" class="form-control" id="recipient-name" name="unit_price" value="{{ old('unit_price') }}">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Reusable: <span
                                    class="red">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" name="reusable" value="1"
                                       class="custom-control-input" required>
                                <label class="custom-control-label" for="customRadioInline1">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" name="reusable" value="0"
                                       class="custom-control-input" required>
                                <label class="custom-control-label" for="customRadioInline2">NO</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group" align="center">
                            <button type="submit" class="btn btn-mat btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-12">
            <div class="card comp-card">
                <div class="card-body">
                    <h5 class="w-100 text-center">All Items</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" id="inventoryItemTable">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Item Unit</th>
                                <th scope="col">Initial Price</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $index => $item)
                                <tr>
                                    <th scope="row">{{$item->item_id}}</th>
                                    <td>
                                        <a href="{{ route('items.showItemsDetails', ['id' => $item->item_id]) }}"
                                           title="See Details">
                                            {{ $item->item_name }}
                                        </a>
                                    </td>
                                    <td>{{$item->item_unit}}</td>
                                    <td>
                                        {{ number_format($item->item_price,2) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('items.edit', ['id' => $item->item_id]) }}"
                                           class="btn btn-sm btn-outline-success">Edit</a>
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
@endif


@section('script')
    @if(Auth::user()->isAdmin())
        <script>
            $(document).ready(function () {
                $('#inventoryItemTable').DataTable({
                    responsive: true
                });
            });
        </script>
    @else
        <script>
            $(document).ready(function () {
                $('#inventoryItemTable').DataTable({
                    responsive: true
                });
            });
        </script>
    @endif


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
@endsection
