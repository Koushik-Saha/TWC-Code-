@extends('layouts.master')

@section('title', 'Designation')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Create New Designation
            </button>
            <br><br>
        </div>
        <br><br>
        <div class="col-12">
            <div class="card">
                <h3 class="card-header w-100 text-center">
                    All Designations
                </h3>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Number of Employee</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $role->role_name }}</td>
                                    <td>{{ $role->users->count() }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $role->role_id }}">
                                            <i class="feather icon-trash-2"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="deleteModal{{ $role->role_id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title w-100 text-center" id="deleteModalLabel">Delete {{ $role->role_name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="font-size: 16px; font-weight: 600; text-align: center;">
                                                        @if($role->users->count() > 0)
                                                            <span class="text-danger">
                                                                You can't Delete "{{ $role->role_name }}" because It has staffs assigned in!
                                                            </span>
                                                        @else
                                                            Are you sure wanna delete designation "<strong>{{ $role->role_name }}</strong>"
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('man_power.designation.delete') }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" value="{{ $role->role_id }}" name="id">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            @if(!$role->users || $role->users->count() <= 0)
                                                                <button type="submit" class="btn btn-danger">Confirm</button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('man_power.designation.add') }}" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title text-center w-100" id="exampleModalLabel">Add New Designation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="desName" class="font-weight-bold">Designation Name</label>
                                <input type="text" name="name" id="desName" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
