<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Calendar;

class ManPowerController extends Controller
{
    public function __construct() {
        $this->middleware('can:add-man-power')->except(['edit', 'update', 'designations', 'addDesignation']);
        $this->middleware('can:manage-man-power')->only(['edit', 'update', 'designations', 'addDesignation']);
    }

    public function searchStaff(Request $request) {
        if(Auth::user()->can('manage-man-power')) {
            $project = Project::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staffs = $project->users()
            ->whereIn('role_id', $roles)
            ->get();

        return view('admin.manpower.ajax-labours-list')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project
            ]);
    }

    public function designations() {
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->get();

        return view('admin.manpower.designation')
            ->with([
                'roles' => $roles
            ]);
    }

    public function addDesignation(Request $request) {
        $role = new Role();

        $role->role_name = $request->post('name');
        $role->save();
        return redirectBackWithNotification('success', 'New Role Added!');
    }

    public function deleteDesignation(Request $request) {
        $role = Role::findOrFail($request->post('id'));

        if($role->users->count() > 0) {
            return redirectBackWithNotification('error', 'You can\'t Delete ' . $role->role_name . ' because It has staffs assigned in!');
        }

        if(!$role->delete()) {
            return redirectBackWithNotification();
        }
        return redirectBackWithNotification('success', 'Designation successfully deleted!');
    }

    public function pay(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id'     => ['required', 'numeric'],
            'labour_id'      => ['required', 'numeric'],
            'date'           => ['date', 'required'],
            'amount'         => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }
        if($request->post('amount') > 0) {
            $payment = createNewPayment([
                'type' => 'debit',
                'to_user' => $request->post('labour_id'),
                'from_user' => Auth::id(),
                'to_bank_account' => null,
                'from_bank_account' => null,
                'amount' => $request->post('amount'),
                'project' => $request->post('project_id'),
                'purpose' => 'salary',
                'by' => 'cash',
                'date' => $request->post('date'),
                'image' => null,
                'note' => $request->post('note')
            ], 'Worker Payment Successful!');
            if(!$payment) {
                return redirectBackWithNotification();
            }
            return redirectBackWithNotification('success', 'Payment Successful!');
        }
        return redirectBackWithNotification();
    }

    public function index() {
        $projects = null;

        if(Auth::user()->can('manage-man-power')) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('project_name')
                ->get();
        }
/*
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $manpowers = User::whereIn('role_id', $roles)
            ->orderBy('name')
            ->get();*/

        return view('admin.manpower.index')->with([
            'projects'   => $projects
        ]);
    }

    public function monthlyIndex() {
        $projects = null;

        if(Auth::user()->can('manage-man-power')) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('project_name')
                ->get();
        }
        /*
                $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
                    ->pluck('role_id')
                    ->toArray();

                $manpowers = User::whereIn('role_id', $roles)
                    ->orderBy('name')
                    ->get();*/

        return view('admin.manpower.monthly-salary-report-index')->with([
            'projects'   => $projects
        ]);
    }

    public function staffAttendance() {
        $projects = null;

        if(Auth::user()->can('manage-man-power')) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('project_name')
                ->get();
        }
        /*
                $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
                    ->pluck('role_id')
                    ->toArray();

                $manpowers = User::whereIn('role_id', $roles)
                    ->orderBy('name')
                    ->get();*/

        return view('admin.manpower.staff-attendance-index')->with([
            'projects'   => $projects
        ]);
    }

    public function show($project, $id) {
        $pro = Project::findOrFail($project);
        if(!Auth::user()->can('manage-man-power')) {
            $assigned = $pro->users()->find(Auth::id());
            if (!$assigned) {
                return redirectBackWithNotification('error', 'Not Found OR You are not authorised!');
            }
        }

        $labour = $pro->users()->findOrFail($id);

        $payable = $labour->attendances->sum('attendance_payable_amount');
        $paid = $labour->staffPayments->sum('payment_amount');

        $events = [];
        foreach ($labour->attendances as $attendance) {
            $dt_start = Carbon::parse($attendance->attendance_date)->setTimeFromTimeString($attendance->shift->shift_start);
            $dt_end = Carbon::parse($attendance->attendance_date)->setTimeFromTimeString($attendance->shift->shift_end);

            $event = Calendar::event(
                $attendance->shift->shift_name . ' Shift',
                false,
                $dt_start,
                $dt_end,
                null,
                [
                    'color' => '#f05050',
                    'url' => '#',
                ]
            );
            array_push($events, $event);
        }
        $calendar = Calendar::addEvents($events);

        return view('admin.manpower.single-details')->with([
            'labour'    => $labour,
            'project'   => $pro,
            'payable'   => $payable,
            'paid'      => $paid,
            'calendar'  => $calendar,
        ]);
    }

    public function add() {
        $projects = null;

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $projects = Project::whereProjectStatus('active')
                ->orderBy('project_name')
                ->get();
        }
        else {
            $projects = Auth::user()->projects()
                ->where('project_status', '=', 'active')
                ->orderBy('project_name')
                ->get();
        }
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->orderBy('role_name')
            ->get();

        return view('admin.manpower.create')
            ->with([
                'roles'     => $roles,
                'projects'  => $projects
            ]);
    }

    public function store(Request $request) {
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $validator = Validator::make($request->all(), [
            'mobile'        => ['required', 'numeric'],
            'name'          => ['required', 'string', 'max:255'],
            'fathers_name'  => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'min:8', 'max:255'],
            'note'          => ['nullable', 'string', 'min:8', 'max:255'],
            'role'          => ['required', 'numeric'],
            'project_id'    => ['required', 'numeric'],
            'section'       => ['required', 'string', 'max:255'],
            'salary'        => ['required', 'numeric']
        ]);
        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        if(!in_array($request->post('role'), $roles)) {
            return redirectBackWithNotification('error', 'Designation is Incorrect!');
        }


        $role = Role::findOrFail($request->post('role'));

        $client = new User();

        $client->role_id = $role->role_id;
        $client->name = $request->post('name');
        $client->fathers_name = $request->post('fathers_name');
        $client->mobile = substr($request->post('mobile'), -10);
        $client->address = $request->post('address');
        $client->image = $request->post('image');
        $client->section = $request->post('section');
        $client->salary = $request->post('salary');
        $client->note = $request->post('note');

        $client->save();

        addActivity('user', $client->id, 'Staff Created');

        $project = Project::findOrFail($request->post('project_id'));
        $pLogs = new ProjectLog();

        $pLogs->pl_project_id = $project->project_id;
        $pLogs->pl_user_id = $client->id;

        if($pLogs->save()) {
            addActivity('project', $project->project_id, 'Project Assigned');
            addActivity('user', $client->id, 'Project Assigned');
            return redirectUrlWithNotification(route('man_power.all'), 'success', 'Staff Successfully Created & Assigned to the Project.');
        }

        return redirectBackWithNotification();
    }

    public function edit($id) {
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->get();

        $staff = User::whereIn('role_id', $roles->pluck('role_id')->toArray())
            ->findOrFail($id);


        return view('admin.manpower.edit')->with([
            'staff'    => $staff,
            'roles'    => $roles
        ]);
    }

    public function update(Request $request, $id) {
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staff = User::whereIn('role_id', $roles)->find($id);
        if(!$staff) {
            return redirectBackWithNotification('error', 'Staff not found!');
        }

        $validator = Validator::make($request->all(), [
            'mobile'        => ['required', 'numeric'],
            'name'          => ['required', 'string', 'max:255'],
            'fathers_name'  => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'min:8', 'max:255'],
            'note'          => ['nullable', 'string', 'min:8', 'max:255'],
            'role'          => ['required', 'numeric'],
            'section'       => ['required', 'string', 'max:255'],
            'salary'        => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }
        $role = Role::findOrFail($request->post('role'));

        $staff->role_id = $role->role_id;
        $staff->name = $request->post('name');
        $staff->fathers_name = $request->post('fathers_name');
        $staff->mobile = substr($request->post('mobile'), -10);
        $staff->address = $request->post('address');
        $staff->image = $request->post('image');
        $staff->section = $request->post('section');
        $staff->salary = $request->post('salary');
        $staff->note = $request->post('note');

        $staff->save();

        addActivity('user', $staff->id, 'Staff Updated');

        return redirectBackWithNotification('success', 'Staff Updated Successfully!');
    }

    public function salaryReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'pid'   => ['required', 'numeric'],
            'month' => ['required']
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        if(Auth::user()->can('manage-man-power')) {
            $project = Project::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }
        $roles = Role::whereNotIn('role_slug', ['administrator', 'manager', 'accountant', 'client', 'supplier'])
            ->pluck('role_id')
            ->toArray();

        $staffs = $project->users()
            ->whereIn('role_id', $roles)
            ->get();



        $requestMonth = $request->post('month');
        $dt = Carbon::createFromDate(\Str::before($requestMonth, '-'), \Str::after($requestMonth, '-'), '01');

        return view('admin.manpower.report.monthly-salary')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project,
                'month'     => $dt->format('F Y'),
                'reqMonth'  => $requestMonth,
                'title'     => 'Salary Report of ' . $dt->format('F Y') . '-' . $project->project_name . ' :: ' . getOption('company_name')
            ]);
    }

    public function changeLaborStatus(Request $request)
    {
        $labor = User::find($request->user_id);
        $labor->status = $request->status;
        $labor->save();

        return response()->json(['message' => 'User status updated successfully.']);
    }
}
