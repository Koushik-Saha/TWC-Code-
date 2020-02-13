<br>
<div class="row">
    <div class="col-md-12">
        <div class="card comp-card">
            <div class="card-body">
                <h3 style="text-align: center;">Shifts of <span style="color: #f91484;">{{$project->project_name}}</span> Project</h3>
                <div class="table-responsive">
                    <table class="table" id="allProjects">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center">From</th>
                            <th scope="col" class="text-center">End</th>
                            @if(Auth::user()->isAdmin())
                                <th scope="col" class="text-center">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($project->shifts as $index => $shift)
                            <tr>
                                <th class="text-center" scope="row">{{ $index+1 }}</th>
                                <td class="text-center">{{ $shift->shift_name }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}</td>
                                @if(Auth::user()->isAdmin())
                                    <td class="text-center">
                                        <button title="Delete This Shift" shift-id="{{ $shift->shift_id }}" data-toggle="modal" data-target="#dltBtnModal" type="button" class="btn btn-link p-0 dltBtn"  style="width: auto; height: auto;">
                                            <i class="fas fa-trash-alt text-danger p-0" style="width: auto; height: auto;"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Modal --}}
    <div class="modal fade" id="dltBtnModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-c-red">
                    <h3 class="modal-title text-center w-100 text-white">Delete Working Shift</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-5">
                    <h5 class="w-100 text-center text-c-red">Are You Sure Want to Delete This Shift?</h5>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('shift.delete') }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="shift_id" id="dltShiftID" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#allProjects').DataTable( {
            responsive: true,
        } );

        $(document).ready(function () {
            $('.dltBtn').click(function (e) {
                console.log($(this).attr('shift-id'));
                $('#dltShiftID').val($(this).attr('shift-id'));
            });
        });
    </script>
</div>
