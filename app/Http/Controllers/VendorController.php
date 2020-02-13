<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function __construct() {
//        $this->middleware('admin')->except(['index', 'show', 'pay', 'bankAccounts']);
    }

    public function bankAccounts(Request $request) {
        if(Auth::user()->isAdmin()) {
            $banks = BankAccount::whereBankUserId(null)->get();
        }
        else {
            $banks = BankAccount::whereBankUserId(Auth::id())->get();
        }
        return view('admin.vendor.ajax-banks')
            ->with([
                'banks' => $banks
            ]);
    }

    public function pay(Request $request) {
        $validator = Validator::make($request->all(), [
            'project_id'     => ['required', 'numeric'],
            'vendor_id'      => ['required', 'numeric'],
            'date'           => ['date', 'required'],
            'refund'         => ['required', 'numeric', 'max:1'],
            'amount'         => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }
        if($request->post('refund') == 0) {
            $amount = (float) $request->post('amount');
        }
        else {
            $amount = (float) $request->post('amount') - ((float) $request->post('amount') * 2);
        }
        if($request->post('payment_by') == 'cash') {
            $bankId = null;
        }
        else {
            $bankId = $request->post('bank_id');
            if(!$bankId) {
                return redirectBackWithNotification();
            }
        }

        if($request->post('amount') > 0) {
            $payment = createNewPayment([
                'type' => 'debit',
                'to_user' => $request->post('vendor_id'),
                'from_user' => Auth::id(),
                'to_bank_account' => null,
                'from_bank_account' => $bankId,
                'amount' => $amount,
                'project' => $request->post('project_id'),
                'purpose' => ($request->post('refund') == 0) ? 'vendor_payment' : 'vendor_refund',
                'by' => $request->post('payment_by'),
                'date' => $request->post('date'),
                'image' => null,
                'note'  => $request->post('note')
            ]);
            if(!$payment) {
                return redirectBackWithNotification();
            }
            if($request->post('payment_by') == 'bank' || $request->post('payment_by') == 'check') {
                if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
                    $offBank = BankAccount::findOrFail($request->post('bank_id'));
                    $offBank->bank_balance = $offBank->bank_balance - (float) $request->post('amount');
                    $offBank->save();
                }
            }
            return redirectBackWithNotification('success', 'Payment Successful!');
        }
        return redirectBackWithNotification();
    }

    public function index() {
        if(Auth::user()->isAdmin() || Auth::user()->isAccountant() || Auth::user()->isManager()) {
            $projects = Project::orderByDesc('created_at')
                ->select('bsoft_projects.project_id', 'bsoft_projects.project_name')
                ->get();

            $role_id = Role::whereRoleSlug('supplier')->firstOrFail()->role_id;

            $vendors = DB::table('bsoft_users')
                ->join('bsoft_project_logs', 'bsoft_users.id', '=', 'bsoft_project_logs.pl_user_id')
                ->join('bsoft_projects', 'bsoft_projects.project_id', '=', 'bsoft_project_logs.pl_project_id')
                ->select('bsoft_users.*', 'bsoft_project_logs.pl_project_id', 'bsoft_projects.project_name', 'bsoft_projects.project_id')
                ->where('role_id', '=', $role_id)
                ->orderBy('id','desc')
                ->get();


            return view('admin.vendor.index')->with([
                'projects'   => $projects,
                'vendors'    => $vendors

            ]);
        }
        $projects = Auth::user()->projects()
            ->where('bsoft_projects.project_status', '=', 'active')
            ->orderByDesc('bsoft_projects.created_at')
            ->select('bsoft_projects.project_id', 'bsoft_projects.project_name')
            ->get();

        return view('admin.vendor.index')->with([
            'projects'   => $projects
        ]);
    }

    public function show($id) {
        $vendor = Role::whereRoleSlug('supplier')->firstOrFail()
            ->users()
            ->findOrFail($id);

        $project = Project::all();

        $total_payable_money = $vendor->itemLogs->sum('il_cost');

        $total_paid = $vendor->vendorPayments->sum('payment_amount');

        return view('admin.vendor.details')->with([
            'vendor'    => $vendor,
            'total_payable_money'    => $total_payable_money,
            'total_paid'  => $total_paid,
            'project' => $project
        ]);
    }

    public function add() {
        if(Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isAccountant()) {
            if(!Auth::user()->isManager()) {
                $projects = Project::whereProjectStatus('active')
                    ->orderByDesc('created_at')
                    ->get();
            }
            else {
                $projects = Auth::user()->projects()->orderByDesc('created_at')
                    ->get();
            }
            return view('admin.vendor.add')
                ->with([
                    'projects'  => $projects
                ]);
        }
        return redirectBackWithNotification('error', 'You are not authorised!');
    }

    public function store(Request $request) {
        if(Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isAccountant()) {
            $validator = Validator::make($request->all(), [
                'project_id' => ['required', 'numeric'],
                'mobile'     => ['required', 'string', 'max:14'],
                'name'       => ['required', 'string', 'max:255'],
                'email'      => ['nullable', 'string', 'email', 'max:255'],
                'address'    => ['nullable', 'string', 'min:8', 'max:255'],
            ]);

            if ($validator->fails()) {
                return redirectBackWithValidationError($validator);
            }
            $role = Role::whereRoleSlug('supplier')->firstOrFail();

            $client = new User();

            $client->role_id = $role->role_id;
            $client->name = $request->post('name');
            $client->email = $request->post('email');
            $client->mobile = substr($request->post('mobile'), -10);
            $client->address = $request->post('address');
            $client->image = $request->post('image');
            $client->note = $request->post('note');

            $client->save();
            addActivity('user', $client->id, 'Vendor Created');

            $log = new ProjectLog();
            $log->pl_project_id = $request->post('project_id');
            $log->pl_user_id = $client->id;

            if($log->save()) {
                addActivity('project', $request->post('project_id'), 'Vendor Assigned');
                return redirectUrlWithNotification(route('vendor.all'), 'success', 'Vendor Successfully Created!');
            }
        }
        return redirectBackWithNotification('error', 'You are not authorised!');
    }

    public function edit($id) {
        if(Auth::user()->isAdmin() || Auth::user()->isManager() || Auth::user()->isAccountant()) {
            if(!Auth::user()->isManager()) {
                $projects = Project::whereProjectStatus('active')
                    ->orderByDesc('created_at')
                    ->get();
            }
            else {
                $projects = Auth::user()->projects()->orderByDesc('created_at')
                    ->get();
            }

            $vendor = Role::whereRoleSlug('supplier')->firstOrFail()
                ->users()
                ->findOrFail($id);

            return view('admin.vendor.edit')->with([
                'client'    => $vendor,
                'projects'  => $projects
            ]);
        }
        return redirectBackWithNotification('error', 'You are not authorised!');
    }

    public function update(Request $request, $id) {
        if(!Auth::user()->isAdmin() && !Auth::user()->isManager() && !Auth::user()->isAccountant()) {
            return redirectBackWithNotification('error', 'You are not authorised!');
        }
        $vendor = Role::whereRoleSlug('supplier')->firstOrFail()
            ->users()
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mobile'     => ['required', 'string', 'max:14'],
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'string', 'email', 'max:255'],
            'address'    => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $vendor->name = $request->post('name');
        $vendor->email = $request->post('email');
        $vendor->mobile = substr($request->post('mobile'), -10);
        $vendor->address = $request->post('address');
        $vendor->image = $request->post('image');
        $vendor->note = $request->post('note');

        $vendor->save();

        addActivity('user', $vendor->id, 'Vendor Updated');

        return redirectUrlWithNotification(route('vendor.show', ['id' => $vendor->id]));
    }
}
