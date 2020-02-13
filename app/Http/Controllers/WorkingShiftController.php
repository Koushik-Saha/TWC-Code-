<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkingShiftController extends Controller
{
    public function __construct() {
        $this->middleware('can:manage-shifts');
    }

    public function index() {
        $projects = Project::whereProjectStatus('active')
            ->orderBy('project_name')
            ->get();

        return view('admin.shifts.index')
            ->with([
                'projects'  => $projects
            ]);
    }

    public function show(Request $request) {
        $project = Project::findOrFail($request->post('pid'));

        return view('admin.shifts.ajax-result')
            ->with('project', $project);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id'        => ['required'],
            'name'              => ['required', 'string', 'max:255'],
            'start_time'        => ['required'],
            'end_time'          => ['required']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $shift = new WorkingShift();

        $shift->shift_name = $request->post('name');
        $shift->shift_project_id = $request->post('project_id');
        $shift->shift_start = $request->post('start_time');
        $shift->shift_end = $request->post('end_time');

        if($shift->save()) {
            addActivity('project', $request->post('project_id'), 'Shift added to the Project');
            addActivity('shift', $shift->shift_id, 'Shift added to the Project');

            return redirectBackWithNotification('success', 'Shift Successfully Added!');
        }
        return redirectBackWithNotification();
    }

    public function edit($id) {

    }

    public function update(Request $request, $id) {

    }

    public function delete(Request $request) {
        $shift = WorkingShift::findOrFail($request->post('shift_id'));

        try {
            $shift->delete();
            return redirectBackWithNotification('success', 'Shift Deleted!');
        }
        catch (\Exception $exception) {
            return redirectBackWithException($exception);
        }
    }

    protected function checkPermission() {
        if(!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            return redirectBackWithNotification('error', 'Sorry! You are not authorised!');
        }
    }
}
