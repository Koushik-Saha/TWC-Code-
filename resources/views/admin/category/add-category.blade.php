@extends('layouts.master')

@section('title', 'Add Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="{{route('category.store')}}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Category</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Select Mother Category<span
                                            class="required text-danger">*</span></small>
                                    <select id="motherCategory" name="motherCategory" class="form-control" required>
                                        <option>Select a mother category....</option>
                                        @foreach($motherCategories as $motherCategory)
                                            <option value="{{$motherCategory->id}}">{{$motherCategory->mother_name}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('category_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('motherCategory') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Category<span
                                            class="required text-danger">*</span></small>
                                    <input type="text" id="categoryName" name="categoryName" class="form-control"
                                           placeholder="" required disabled>

                                    @if ($errors->has('category_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('category_name') }}</small>
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

        <div class="row">
            <div class="col-md-12">
                <div class="card comp-card">
                    <div class="card-body">
                        <h5 class="w-100 text-center">All Categories</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" id="categoryTable">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Mother Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($category as $index => $mc)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>{{$mc->category_name}}</td>
                                        <td>{{$mc->mother_name}}</td>
                                        <td>
                                            <a href="{{ route('categories', ['id' => $mc->id]) }}"
                                               class="btn btn-sm btn-outline-success">Edit</a>
{{--                                            <a href="#" class="btn btn-sm btn-info" data-toggle="modal"--}}
{{--                                               data-target="#exampleModalCenter{{$index}}">Edit</a>--}}
                                            <a id="deleteBtn" data-id="{{$mc->id}}" href="#"
                                               class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>


                                    <div class="modal fade" id="exampleModalCenter{{$index}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Modal
                                                        title</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('category.update',$mc->id)}}" method="post">
                                                        @csrf
                                                        {{method_field('PUT')}}
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Name</label>
                                                            {{-- <input type="hidden" name="id" value="{{$mc}}"> --}}
                                                            <input required="" name="category_name" type="text"
                                                                   class="form-control" id="exampleInputEmail1"
                                                                   aria-describedby="emailHelp"
                                                                   placeholder="Enter email"
                                                                   value="{{$mc->category_name}}"><br><br>
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

    <script>
        $('#categoryTable').DataTable({});

        $(document).on('click', '#deleteBtn', function (el) {
            var manuId = $(this).data("id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this this category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("You are deleted this category!", {
                            icon: "success",
                        });
                        window.location.href = "delete/" + manuId;
                    }


                });
        });

        $(document).on('click', '#deleteNo', function (el) {
            swal("Oops", "You can't delete this. Some item belongs to it!!", "error")
        })
    </script>

    <script>
        $('#motherCategory').on('change', e => {
            document.getElementById("categoryName").removeAttribute("disabled");
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
@endsection
