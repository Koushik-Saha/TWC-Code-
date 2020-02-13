<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\User;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AttendanceController extends Controller
{

    public function searchAttendance(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
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
            ->where('status','=','1')
            ->orderBy('name')
            ->get();


        return view('admin.manpower.ajax-labours-attendence')
            ->with([
                'staffs'    => $staffs,
                'project'   => $project
            ]);
    }

    public function storeAttendance(Request $request) {
        $this->checkPermission();
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::findOrFail($request->post('project_id'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('project_id'));
        }
        if(!$project) {
            return sendJsonResponse('error', 'Project not found or you are not authorised!');
        }
        $dtCheck = $this->checkDateTimeForAttendance($request->post('date'), $request->post('shift'));
        if($dtCheck != 'ok') {
            return sendJsonResponse('error', $dtCheck);
        }

        $staff = $project->users()->find($request->post('labour_id'));
        if(!$staff) {
            return sendJsonResponse('error', 'Staff not found!');
        }

        $oldAtt = Attendance::whereAttendanceProjectId($project->project_id)
            ->where('attendance_user_id', '=', $staff->id)
            ->where('attendance_date', '=', $request->post('date'))
            ->where('attendance_shift_id', '=', $request->post('shift'))
            ->first();

        if($oldAtt) {
            return sendJsonResponse('error', 'Attendance already taken!');
        }


        $attendance = new Attendance();

        $attendance->attendance_date = $request->post('date');
        $attendance->attendance_user_id = $request->post('labour_id');
        $attendance->attendance_project_id = $project->project_id;
        $attendance->attendance_shift_id = $request->post('shift');
        $attendance->attendance_payable_amount = number_format($staff->salary / 2, 2);
        $attendance->attendance_paid_amount = $request->post('paid');
        $attendance->attendance_note = $request->post('note');

        $attendance->save();

        addActivity('attendance', $attendance->attendance_id, 'Attendance Added');
        if($attendance->attendance_paid_amount > 0) {
            $payment = createNewPayment([
                'type' => 'debit',
                'to_user' => $attendance->attendance_user_id,
                'from_user' => (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) ? Auth::id() : null,
                'to_bank_account' => null,
                'from_bank_account' => null,
                'amount' => $attendance->attendance_paid_amount,
                'project' => $attendance->attendance_project_id,
                'purpose' => 'salary',
                'by' => 'cash',
                'date' => $attendance->attendance_date,
                'image' => null,
                'note'  => $attendance->attendance_note
            ]);
            if(!$payment) {
                return redirectBackWithNotification();
            }
        }

        return sendJsonResponse('success', 'Attendance Added Successfully!');
    }

    public function report(Request $request) {
        $this->checkPermission();

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

        return view('admin.manpower.report.index')
            ->with([
                'projects'  => $projects
            ]);

    }

    public function edit($id) {
        if(!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }

        $attendance = Attendance::findOrFail($id);

        return view('admin.manpower.attendance-edit')
            ->with([
                'attendance'    => $attendance
            ]);
    }

    public function update(Request $request, $id) {
        if(!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }

        $validator = Validator::make($request->all(), [
            'date'      => ['required'],
            'shift'     => ['required', 'numeric'],
            'paid'      => ['nullable', 'numeric'],
            'note'      => ['required', 'string']
        ]);

        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $attendance = Attendance::findOrFail($id);


        $oldAtt = Attendance::whereAttendanceProjectId($attendance->attendance_project_id)
            ->where('attendance_user_id', '=', $attendance->attendance_user_id)
            ->where('attendance_date', '=', $request->post('date'))
            ->where('attendance_shift_id', '=', $request->post('shift'))
            ->where('attendance_paid_amount', '=', $request->post('paid'))
            ->first();

        if($oldAtt) {
            return redirectBackWithNotification('error', 'Attendance already taken!');
        }

        $attendance->attendance_date = $request->post('date');
        $attendance->attendance_shift_id = $request->post('shift');
        $attendance->attendance_note = $request->post('note');

        if(!$attendance->save()) {
            return redirectBackWithNotification();
        }

        if($request->has('paid') && $request->post('paid') > 0) {
            if($attendance->attendance_paid_amount > 0 && $request->post('paid') != $attendance->attendance_paid_amount) {
                $payment = Payment::where('payment_to_user', '=', $attendance->user->id)
                    ->where('payment_for_project', '=', $attendance->project->project_id)
                    ->where('payment_amount', '=', $attendance->attendance_paid_amount)
                    ->where('payment_purpose', '=', 'salary')
                    ->first();

                if($payment) {
                    $payment->payment_amount = $request->post('paid');
                    $payment->save();
                }
            }
            if(!$attendance->attendance_paid_amount || $attendance->attendance_paid_amount <= 0) {
                $payment = createNewPayment([
                    'type' => 'debit',
                    'to_user' => $attendance->attendance_user_id,
                    'from_user' => (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) ? Auth::id() : null,
                    'to_bank_account' => null,
                    'from_bank_account' => null,
                    'amount' => $request->post('paid'),
                    'project' => $attendance->attendance_project_id,
                    'purpose' => 'salary',
                    'by' => 'cash',
                    'date' => $attendance->attendance_date,
                    'image' => null,
                    'note'  => $attendance->attendance_note
                ]);
                if(!$payment) {
                    return redirectBackWithNotification();
                }
            }
            $attendance->attendance_paid_amount = $request->post('paid');
            $attendance->save();
        }

        return redirectBackWithNotification('success', 'Attendance Updated!');
    }

    public function delete(Request $request) {
        if(!Auth::user()->isAdmin()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }

        $attendance = Attendance::findOrFail($request->post('attendance_id'));

        $attendance->forceDelete();

        $payment_from = Carbon::parse($attendance->updated_at)->subMinutes(5);
        $payment_to = Carbon::parse($attendance->updated_at)->addMinutes(5);

        $payment = Payment::where('payment_to_user', '=', $attendance->user->id)
            ->where('payment_for_project', '=', $attendance->project->project_id)
            ->where('payment_amount', '=', $attendance->attendance_paid_amount)
            ->where('payment_purpose', '=', 'salary')
            ->whereBetween('updated_at', [$payment_from, $payment_to])
            ->first();

        if($payment) {
            $payment->forceDelete();
        }

        return redirectBackWithNotification('success', 'Successfully Deleted!');
    }

    public function showReport(Request $request) {
        $this->checkPermission();

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::findOrFail($request->post('pid'));
        }
        else {
            $project = Auth::user()->projects()
                ->findOrFail($request->post('pid'));
        }

        $attendances = $project->attendances()
            ->whereBetween('attendance_date', [Carbon::parse($request->post('start')), Carbon::parse($request->post('end'))])
            ->orderByDesc('attendance_date')
            ->get();

        return view('admin.manpower.report.ajax-attendance')
            ->with([
                'project'       => $project,
                'attendances'   => $this->makeAttendanceReport($attendances),
                'start'         => Carbon::parse($request->post('start'))->toFormattedDateString(),
                'end'           => Carbon::parse($request->post('end'))->toFormattedDateString()
            ]);
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    protected function makeAttendanceReport(Collection $collection) {
        $attendances = collect();

        foreach ($collection as $item) {
            $uuid = Carbon::parse($item->attendance_date)->format('Y_m_d') . '_' . $item->user->id;

            if($attendances->isNotEmpty() && $existingKey = $this->uuidExists($uuid, $attendances)) {
                $shift = ['id' => $item->shift->shift_id, 'name' => $item->shift->shift_name];

                array_push($attendances[$existingKey]->shifts, $shift);
                $attendances[$existingKey]->payable += ($item->attendance_payable_amount ? $item->attendance_payable_amount : 0);
                $attendances[$existingKey]->paid += ($item->attendance_paid_amount ? $item->attendance_paid_amount : 0);
            }
            else {
                $attendances->push( $this->makeNewCollectionItem($item) );
            }
        }

        return $attendances;
    }

    /**
     * @param string $uniqueId
     * @param Collection $collection
     * @return mixed
     */
    protected function uuidExists(string $uniqueId, Collection $collection) {
        return $collection->search(function ($item, $key) use ($uniqueId) {
            return $item->uuid == $uniqueId;
        });
    }

    /**
     * @param Attendance $attendance
     * @return stdClass
     */
    protected function makeNewCollectionItem(Attendance $attendance) {
        $item = new stdClass();
        $item->uuid = Carbon::parse($attendance->attendance_date)->format('Y_m_d') . '_' . $attendance->user->id;
        $item->user = ['id' => $attendance->user->id, 'name' => $attendance->user->name];
        $item->date = Carbon::parse($attendance->attendance_date)->toFormattedDateString();
        $item->shifts = [
            ['id'    => $attendance->shift->shift_id, 'name'  => $attendance->shift->shift_name]
        ];
        $item->payable = $attendance->attendance_payable_amount ? $attendance->attendance_payable_amount : 0;
        $item->paid = $attendance->attendance_paid_amount ? $attendance->attendance_paid_amount : 0;
        $item->taken_by = $attendance->activity->activityBy->name;
        $item->attendance_id = $attendance->attendance_id;

        return $item;
    }

    /**
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function checkPermission() {
        $role = Auth::user()->role->role_slug;

        if($role == 'administrator' || $role == 'manager' || $role == 'accountant') {
            return true;
        }
        return redirectBackWithNotification('error', 'Sorry! You Are Not Authorised!.');
    }

    protected function checkDateTimeForAttendance(string $date, int $shift_id) {
        $shift = WorkingShift::findOrFail($shift_id);
        $date = Carbon::parse($date);
        $date->setTimeFromTimeString($shift->shift_start);

//        if(!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
//            if($date->diffInMinutes(Carbon::now(), false) > 180) {
//                return 'You Can\'t add attendance for the shift now!';
//            }
//        }
        if($date->diffInMinutes(Carbon::now(), false) < 0) {
            return 'Shift has\'t started yet!';
        }
        return 'ok';
    }


}
