<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function index() {
        $this->checkPermission();

        $loans = Loan::orderByDesc('created_at')->get();

        return view('admin.loans.index')
            ->with([
                'loans' => $loans
            ]);
    }

    public function show($id) {
        $this->checkPermission();

        $loan = Loan::findOrFail($id);

        return view('admin.loans.show')
            ->with([
                'loan'  => $loan
            ]);
    }

    public function newLoan() {
        $this->checkPermission();


        return view('admin.loans.new');
    }

    public function storeLoan(Request $request) {
        $this->checkPermission();

        $validator = Validator::make($request->all(), [
            'loan_amount'   => ['required', 'numeric'],
            'loan_name'     => ['required', 'string'],
            'loan_from'     => ['required', 'string']
        ]);
        if($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $loan = new Loan();

        $loan->loan_from = $request->post('loan_from');
        $loan->loan_name = $request->post('loan_name');
        $loan->loan_no = $request->post('loan_no');
        $loan->loan_amount = $request->post('loan_amount');
        $loan->loan_note = $request->post('loan_note');

        if(!$loan->save()) {
            return redirectBackWithNotification();
        }
        addActivity('loan', $loan->loan_id, 'New Loan Added');

        $payment = createNewPayment([
            'type' => 'credit',
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => null,
            'from_bank_account' => null,
            'amount' => $request->post('loan_amount'),
            'project' => null,
            'purpose' => 'loan_received',
            'by' => 'cash',
            'date' => Carbon::now()->toDateString(),
            'image' => null,
            'note'  => $request->post('note')
        ], 'Loan Received for - ' . $loan->loan_name);
        if(!$payment) {
            return redirectBackWithNotification();
        }

        return redirectUrlWithNotification(route('loan.show', ['id' => $loan->loan_id]), 'success', 'Loan Successfully Added!');
    }

    public function payLoanView() {
        $this->checkPermission();

        $loans = Loan::where('loan_amount', '!=', \DB::raw('loan_paid'))->get();

        return view('admin.loans.pay')
            ->with([
                'loans' => $loans
            ]);
    }

    public function payLoan(Request $request) {
        $this->checkPermission();

        $validator = Validator::make($request->all(), [
            'loan_id'        => ['required', 'numeric'],
            'bank_id'        => ['nullable', 'numeric'],
            'payment_by'     => ['string', 'required'],
            'amount'         => ['required', 'numeric', 'min:100'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }
        $loan = Loan::findOrFail($request->post('loan_id'));
        $payment = createNewPayment([
            'type' => 'debit',
            'to_user' => null,
            'from_user' => null,
            'to_bank_account' => null,
            'from_bank_account' => ($request->post('bank_id')) ? $request->post('bank_id') : null,
            'amount' => $request->post('amount'),
            'project' => null,
            'purpose' => 'loan_payment',
            'by' => $request->post('payment_by'),
            'date' => Carbon::now()->toDateString(),
            'image' => null,
            'note'  => $request->post('note')
        ], 'Loan Payment for - ' . $loan->loan_name);
        if(!$payment) {
            return redirectBackWithNotification();
        }
        $loan->loan_paid += $request->post('amount');
        $loan->save();
        addActivity('loan', $loan->loan_id, 'Loan Paid');
        if(strtolower($request->post('payment_by')) === 'bank') {
            $offBank = BankAccount::findOrFail($request->post('bank_id'));
            $offBank->bank_balance = $offBank->bank_balance - (float) $request->post('amount');
            $offBank->save();
        }
        return redirectBackWithNotification('success', 'Loan Payment Successful!');

    }

    public function bankAccounts() {
        $banks = BankAccount::whereBankUserId(null)->get();
        return view('admin.loans.ajax-banks')
            ->with([
                'banks' => $banks
            ]);
    }

    /**
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    protected function checkPermission() {
        $role = Auth::user()->role->role_slug;

        if($role == 'administrator' || $role == 'accountant') {
            return true;
        }
        return redirectBackWithNotification('error', 'Sorry! You Are Not Authorised!.');
    }
}
