@extends('layouts.master')

@section('title', 'Add Mother Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="{{route('mother-category.store')}}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Add a mother category</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Mother Category<span
                                            class="required text-danger">*</span></small>
                                    <input type="text" id="mother_name" name="mother_name" class="form-control"
                                           placeholder="Mother Category" required>

                                    @if ($errors->has('mother_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('mother_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Add
                                    </button>
                                    <button type="button" class="btn btn-inverse">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">All Mother categories</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="motherTable">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(App\Models\MotherCategory::all() as $index => $mc)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>{{$mc->mother_name}}</td>
                                        <td>
                                            <a href="{{ route('category', ['id' => $mc->id]) }}"
                                               class="btn btn-sm btn-outline-success">Edit</a>
{{--                                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal"--}}
{{--                                               data-target="#motherCategory{{$mc->id}}">Edit</a>--}}
                                            <a id="deleteBtn" data-id="{{$mc->id}}" href="#"
                                               class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="motherCategory{{$mc->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Update</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('mother-category.update',$mc->id)}}"
                                                          method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Name</label>
                                                            {{-- <input type="hidden" name="id" value="{{$mc}}"> --}}
                                                            <input name="mother_name" type="text" class="form-control"
                                                                   id="exampleInputEmail1" aria-describedby="emailHelp"
                                                                   placeholder="Enter mother category"
                                                                   value="{{$mc->mother_name}}"><br><br>
                                                            <input type="submit" class=" btn btn-success"
                                                                   value="Update">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>

    <script>
        $('#motherTable').DataTable({

        });
    </script>

    <script>
        $(document).on('click', '#deleteBtn', function (el) {
            var mcId = $(this).data("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("You have deleted a mother category!", {
                            icon: "success",
                        });
                        window.location.href = window.location.href = "mother-category/delete/" + mcId;
                    }


                });
        });

        $(document).on('click', '#deleteNo', function (el) {
            swal("Oops", "You can't delete this. Some item belongs to it!!", "error")
        })
    </script>
@endsection
