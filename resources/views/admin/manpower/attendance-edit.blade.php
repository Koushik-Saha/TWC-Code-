@extends('layouts.master')

@section('title', 'Attendance Report')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card comp-card p-md-5">
                <div class="card-body">
                    <form action="{{ route('man_power.attendance.edit', ['id' => $attendance->attendance_id]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group mb-5">
                            <h2 style="text-align: center;">Edit Attendance</h2>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="staff-name" class="col-form-label col-md-3 font-weight-bold">Staff Name</label>
                            <div class="col-md-9">
                                <span style="display: block; padding: 7px 15px;">{{ $attendance->user->name}}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="project-name" class="col-form-label col-md-3 font-weight-bold">Project</label>
                            <div class="col-md-9">
                                <span style="display: block; padding: 7px 15px;">{{ $attendance->project->project_name }}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="staff-role" class="col-form-label col-md-3 font-weight-bold">Designation</label>
                            <div class="col-md-9">
                                <span style="display: block; padding: 7px 15px;">{{ $attendance->user->role->role_name }}</span>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="date" class="col-form-label col-md-3 font-weight-bold">Date</label>
                            <div class="col-md-9">
                                <input class="form-control" name="date" type="date" id="date" value="{{ $attendance->attendance_date }}" required>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="shift" class="col-form-label col-md-3 font-weight-bold">Shift</label>
                            <div class="col-md-9">
                                <select name="shift" id="shift" class="custom-select" required>
                                    <option selected disabled>--- Select Shift ---</option>
                                    @foreach($attendance->project->shifts as $shift)
                                        <option value="{{ $shift->shift_id }}" {{ ($shift->shift_id == $attendance->attendance_shift_id) ? 'selected' : '' }}>
                                            {{ $shift->shift_name }} &nbsp;&nbsp; --- &nbsp;&nbsp;
                                            {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} TO
                                            {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="paid" class="col-form-label col-md-3 font-weight-bold">Food/Advance</label>
                            <div class="col-md-9">
                                <input class="form-control" name="paid" type="number" id="paid" value="{{ $attendance->attendance_paid_amount }}" required>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="note" class="col-form-label col-md-3 font-weight-bold">Note</label>
                            <div class="col-md-9">
                                <textarea name="note" id="note" rows="3" class="form-control" required>{!! $attendance->attendance_note !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="status" class="col-form-label col-md-3 font-weight-bold">Activity</label>
                            <div class="col-md-9">
                                <input type="checkbox" data-id="{{ $attendance->attendance_id }}" name="status" class="js-switch" {{ $attendance->status == 1 ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            let switchery = new Switchery(html,  { size: 'small' });
        });

        $(document).ready(function(){
            $('.js-switch').change(function () {
                let status = $(this).prop('checked') === true ? 1 : 0;
                let userId = $(this).data('attendance_id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('man_power.attendance.edit', ['id' => $attendance->attendance_id]) }}',
                    data: {'status': status, 'id': userId},
                    success: function (data) {
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                    }
                });
            });
        });

    </script>
@endsection
