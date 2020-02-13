@extends('layouts.master')

@section('title', 'Edit Sub  Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <form action="{{route('sub-category', ['id' => $subCategory->id])}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 style="text-align: center">Sub Category</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Select Mother Category<span
                                            class="required text-danger">*</span></small>
                                    <select id="motherCategory" name="motherCategory" class="form-control" required>
                                        <option>Select Mother Category....</option>
                                        @foreach($motherCategories as $motherCategory)
                                            <option value="{{$motherCategory->id}}">{{$motherCategory->mother_name}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('mother_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('motherCategory') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Select Category<span
                                            class="required text-danger">*</span></small>
                                    <select id="category" name="category" class="form-control" required>
                                        <option selected>Select Category....</option>
                                    </select>

                                    @if ($errors->has('category'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('category') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col">
                                    <small class="text-uppercase text-dark">Sub Category<span
                                            class="required text-danger">*</span></small>
                                    <input type="text" id="subCategory" name="subCategoryName" class="form-control"
                                           placeholder="" required disabled value="{{ old('sub_category_name', $subCategory->sub_category_name) }}">

                                    @if ($errors->has('category_name'))
                                        <small
                                            class="form-control-feedback text-danger">{{ $errors->first('subCategoryName') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update
                                    </button>
                                    <button type="button" class="btn btn-inverse">Cancel</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script>

        $('#motherCategory').on('change', e => {
            e.preventDefault();
            $('#category').empty();
            $('#category').append(`<option>Please Choose</option>`);

            var mcId = $('#motherCategory').find(":selected").val();
            console.log(mcId);
            $.ajax({
                url: `category/${mcId}`,
                success: data => {
                    data.categories.forEach(category =>
                        $('#category').append(`<option value="${category.id}">${category.category_name}</option>`)
                    )
                }
            })
        });

        $('#category').on('change', e => {
            document.getElementById("subCategory").removeAttribute("disabled");
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.min.js"></script>
@endsection
