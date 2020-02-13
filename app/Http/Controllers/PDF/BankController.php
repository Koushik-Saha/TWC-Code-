<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use stdClass;

class BankController extends Controller
{
    public function income(Request $request, $type, $id = null) {
        $per_page = ($request->get('per_page'))? $request->get('per_page') : 15;
        $search = htmlspecialchars($request->get('search'));

        if($type === 'project') {
            if(!$id) {
                return response()->json('Project Id Required!', 404);
            }
            $payments = Payment::wherePaymentType('credit')
                ->where('payment_purpose', '=', 'project_money')
                ->where('payment_for_project', '=', $id)
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        } else if($type === 'loan') {
            $payments = Payment::wherePaymentType('credit')
                ->where('payment_purpose', '=', 'loan_received')
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        } else {
            $payments = Payment::wherePaymentType('credit')
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        }

        return response()->json($this->makePaymentCollection($payments), 200);
    }

    public function expense(Request $request, $type, $id = null) {
        $per_page = ($request->get('per_page'))? $request->get('per_page') : 15;
        $search = htmlspecialchars($request->get('search'));

        if($type === 'project') {
            if(!$id) {
                return response()->json('Project Id Required!', 404);
            }
            $payments = Payment::wherePaymentType('debit')
                ->where('payment_for_project', '=', $id)
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        } else if($type === 'loan') {
            $payments = Payment::wherePaymentType('debit')
                ->where('payment_purpose', '=', 'loan_payment')
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        } else {
            $payments = Payment::wherePaymentType('debit')
                ->orderByDesc('payment_date')
                ->simplePaginate($per_page);
        }

        return response()->json($this->makePaymentCollection($payments), 200);
    }

    public function cashTransactions(Request $request, $type, $id = null) {
        $per_page = ($request->get('per_page'))? $request->get('per_page') : 15;
        $search = htmlspecialchars($request->get('search'));

        $roles = Role::whereIn('role_slug', ['administrator', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        if($type === 'project') {
            if(!$id) {
                return response()->json('Project Id Required!', 404);
            }
            $payments = Payment::wherePaymentBy('cash')
                ->where('payment_for_project', '=', $id)
                ->orderByDesc('payment_date')
                ->get()
                ->filter(function ($payment) use ($roles) {
                    return in_array($payment->activity->activityBy->role_id, $roles);
                });
        } else if($type === 'loan') {
            $payments = Payment::wherePaymentBy('cash')
                ->whereIn('payment_purpose', ['loan_received', 'loan_payment'])
                ->orderByDesc('payment_date')
                ->get()
                ->filter(function ($payment) use ($roles) {
                    return in_array($payment->activity->activityBy->role_id, $roles);
                });
        } else {
            $payments = Payment::wherePaymentBy('cash')
                ->orderByDesc('payment_date')
                ->get()
                ->filter(function ($payment) use ($roles) {
                    return in_array($payment->activity->activityBy->role_id, $roles);
                });
        }
        $page = $request->get('page') ?: (Paginator::resolveCurrentPage() ?: 1);
        $paginatedPayments = new LengthAwarePaginator($payments->forPage($page, $per_page), $payments->count(), $per_page, $page);

        return response()->json($this->makePaymentCollection($paginatedPayments), 200);
    }

    protected function makePaymentCollection($payments) {
        $payments->getCollection()->transform(function($payment) {
            $output = new stdClass();
            $output->method = $payment->payment_by;
            $output->type = $payment->payment_type;
            $output->date = Carbon::parse($payment->payment_date)->toFormattedDateString();
            $output->amount = $payment->payment_amount;
            $output->by = $payment->activity->activityBy->name;
            $output->from = ($payment->fromUser) ? $payment->fromUser->name : null;
            $output->to = ($payment->toUser) ? $payment->toUser->name : null;
            $output->purpose = str_replace('_', ' ', $payment->payment_purpose);
            $output->project_id = ($payment->project) ? $payment->project->project_id : null;
            $output->project_name = ($payment->project) ? $payment->project->project_name : null;

            return $output;
        });

        return $payments;
    }
}
