<br>
<div class="row">
    <div class="col-md-12">
        <div class="card comp-card">
            <div class="card-body">
                <h3 style="text-align: center;" class="mb-5">Take Attendance For <span class="label label-inverse-info">{{ $project->project_name }}</span> Project</h3>
                <div align="center">
                    <div class="col-md-4">
                        <label for="date" style="font-weight: 600;">Date: </label>
                        <input class="form-control" name="date" type="date" id="date" required="">
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    @csrf
                    <table class="table" id="Attendence">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Shift</th>
                            <th scope="col">Status</th>
                            <th scope="col">Food / Advance</th>
                            <th scope="col">Note</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name="project_id" value="{{ $project->project_id }}">
                        @forelse($staffs as $index => $labour)
                            <tr>
                                <th scope="row">{{ $index+1 }}</th>
                                <td>
                                    {{$labour->name}}
                                    <input type="hidden" name="labour_id_{{ $labour->id }}" value="{{$labour->id}}">
                                </td>
                                <td>
                                    @if($labour->role->role_slug=='labour')
                                        <span class="label label-success">{{$labour->role->role_name}}</span>
                                    @elseif($labour->role->role_slug=='Helper')
                                        <span class="label label-warning">{{$labour->role->role_name}}</span>
                                    @else
                                        <span class="label label-primary">{{$labour->role->role_name}}</span>
                                    @endif
                                </td>
                                <td style="min-width: 150px;">
                                    <select name="shift_{{ $labour->id }}" class="custom-select" required>
                                        <option selected disabled>--- Select Shift ---</option>
                                        @foreach($project->shifts as $shift)
                                            <option value="{{ $shift->shift_id }}">
                                                {{ $shift->shift_name }} &nbsp;&nbsp; --- &nbsp;&nbsp;
                                                {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} TO
                                                {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if( $labour->status == 1)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="number" name="paid_{{$labour->id}}" class="form-control">
                                </td>
                                <td>
                                    <textarea class="form-control" name="note_{{ $labour->id }}" id="" cols="10" rows="1"></textarea>
                                </td>
                                <td>
                                    <button class="btn btn-primary add-attendance-btn" style="padding: 6px 18px;" id="{{ $labour->id }}">Add</button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>



<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Add Attendance
    $(document).ready(function() {

        $(".add-attendance-btn").click(function(e) {
            e.preventDefault();
            let err = false;

            let labour_id = e.target.id;
            let project_id = $("input[name=project_id]").val();
            let date = $("input[name=date]").val();
            let shift = $("select[name=shift_" + labour_id + "]").val();
            let paid = $("input[name=paid_" + labour_id + "]").val();
            let note = $("textarea[name=note_" + labour_id + "]").val();

            if(typeof date !== 'string' || date.length < 8) {
                toastr.error("Please Select A Date!");
                err = true;
            }
            if(typeof shift !== 'string') {
                toastr.error("Please Select A Shift!");
                err = true;
            }

            if(!err) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('man_power.store_attendance') }}",
                    type : "POST",
                    data : { labour_id, project_id, date, shift, paid, note},
                    success : function(res){
                        if(res.status === 'error') {
                            toastr.error(res.msg);
                        }
                        if(res.status === 'success') {
                            toastr.success(res.msg);
                        }
                    },
                    error : function(xhr, status){
                        toastr.error('Something Wrong! Please try again later.');
                    }
                });
            }
        });
    });
</script>
