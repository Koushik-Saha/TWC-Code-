<?php

use App\Models\User;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Validator;
use App\Models\Option;
use App\Models\Project;
use App\Models\Activity;
use App\Models\Payment;
use Carbon\Carbon;

// helper function


/**
 * @param string $name
 * @return mixed|string|null
 */
function getOption(string $name) {
    $option = Option::whereOptionName($name)->first();

    return ($option) ? $option->option_content : null;
}

/**
 * @param string|null $status
 * @return int
 */
function countProjects(string $status = null) {
    if (!$status) {
        return (Auth::user()->isAdmin() || Auth::user()->isAccountant()) ? Project::all()->count() : Auth::user()->projects()->count();

    }
    return (Auth::user()->isAdmin() || Auth::user()->isAccountant()) ? Project::whereProjectStatus(strtolower($status))->get()->count() :
        Auth::user()->projects()->where('project_status', '=', strtolower($status))->get()->count();
}

/**
 * @param Exception $e
 * @return \Illuminate\Http\RedirectResponse
 */
function redirectBackWithException(Exception $e) {
    $err_msg = Lang::get($e->getMessage());
    $notification = [
        'message'       => $err_msg,
        'alert-type'    => 'error'
    ];

    return redirect()->back()->with($notification);
}

/**
 * @param string $type
 * @param string $msg
 * @return \Illuminate\Http\RedirectResponse
 */
function redirectBackWithNotification(string $type = 'error', string $msg = 'Something Wrong!') {
    $notification = [
        'message'       => $msg,
        'alert-type'    => $type
    ];

    return redirect()->back()->with($notification);
}

/**
 * @param string $url
 * @param string $type
 * @param string $msg
 * @return \Illuminate\Http\RedirectResponse
 */
function redirectUrlWithNotification(string $url, string $type = 'success', string $msg = 'Looking Good!') {
    $notification = [
        'message'       => $msg,
        'alert-type'    => $type
    ];

    return redirect($url)->with($notification);
}

function redirectBackWithValidationError(Validator $validator) {
    $notification = [
        'message' => 'Please Fill Up fields properly!',
        'alert-type' =>'error'
    ];
    return redirect()->back()->withInput()->withErrors($validator)->with($notification);
}

/**
 * @param string $for
 * @param int $forId
 * @param string $note
 * @return bool
 */
function addActivity(string $for, int $forId, string $note) {
    $activityFor = 'activity_';
    $activityFor .= ($for === 'user') ? 'for_user_id' : $for . '_id';

    return Activity::insert([
        'activity_of_user_id'   => Auth::id(),
        'activity_note'         => $note,
        $activityFor            => $forId,
        'created_at'            => \Carbon\Carbon::now(config('app.timezone'))
    ]);
}

/**
 * @param string $status
 * @param string $msg
 * @return \Illuminate\Http\JsonResponse
 */
function sendJsonResponse(string $status = 'success', string $msg = 'OK!') {
    return response()->json([
        'status' => strtolower($status),
        'msg'    => $msg
    ]);
}

/**
 * @param array $data
 * @param string $note
 * @return bool
 */
function createNewPayment(array $data, string $note = 'New Payment Made!') {
    $payment = new Payment();
    /*
     [
        'type' => '', 'to_user' => '', 'from_user' => '', 'to_bank_account' => '', 'from_bank_account' => '',
        'project' => '', 'amount' => '', 'purpose' => '', 'by' => '', 'date' => '', 'image' => ''
    ]
     */
    $payment->payment_type = isset($data['type']) ? $data['type'] : null;
    $payment->payment_to_user = isset($data['to_user']) ? $data['to_user'] : null;
    $payment->payment_from_user = isset($data['from_user']) ? $data['from_user'] : null;
    $payment->payment_to_bank_account = isset($data['to_bank_account']) ? $data['to_bank_account'] : null;
    $payment->payment_from_bank_account = isset($data['from_bank_account']) ? $data['from_bank_account'] : null;
    $payment->payment_for_project = $data['project'];
    $payment->payment_purpose = $data['purpose'];
    $payment->payment_amount = $data['amount'];
    $payment->payment_by = isset($data['by']) ? $data['by'] : 'cash';
    $payment->payment_date = isset($data['date']) ? $data['date'] : Carbon::now()->toDateString();
    $payment->payment_image = isset($data['image']) ? $data['image'] : null;
    $payment->payment_note = isset($data['note']) ? $data['note'] : null;

    if($payment->save()) {
        addActivity('payment', $payment->payment_id, $note);
        return true;
    }
    return false;
}

/**
 * @param string $img
 * @param string $template
 * @return UrlGenerator|string
 */
function imageCache(string $img, string $template = 'original') {
    return route('imagecache', ['template' => $template, 'filename' => $img]);
}

/**
 * @param string|null $mobile
 * @return string
 */
function mobileNumber(string $mobile = null) {
    return ($mobile) ? '+880 ' . substr($mobile, 0, 4) . ' ' . substr($mobile, -6) : null;
}


function getMonthlyAttendancesOfUser(string $month, User $user) {
    return $user->attendances()
        ->whereYear('updated_at', '=', \Str::before($month, '-'))
        ->whereMonth('updated_at', '=', \Str::after($month, '-'))
        ->get();
}

function getMonthlyPaymentsToStaff(string $month, User $user) {
    return $user->staffPayments()
        ->whereYear('updated_at', '=', \Str::before($month, '-'))
        ->whereMonth('updated_at', '=', \Str::after($month, '-'))
        ->get();
}