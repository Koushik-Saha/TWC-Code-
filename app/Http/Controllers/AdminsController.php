<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public function __construct() {
//        $this->middleware('admin');

    }

    public function index() {
        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->pluck('role_id')
            ->toArray();
        $administrators = User::whereIn('role_id', $roles)
            ->orderBy('name')
            ->get();

        return view('admin.administrators.index')->with([
            'administrators'   => $administrators
        ]);
    }

    public function show($id) {
        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $administrator = User::whereIn('role_id', $roles)
            ->find($id);

        $total_received = $administrator->managerPayments->sum('payment_amount');

        $total_expense = $administrator->payments()->where('payment_type', '=', 'debit')->sum('payment_amount');

        return view('admin.administrators.show')->with([
            'administrator'    => $administrator,
            'total_received'  => $total_received,
            'total_expense' => $total_expense,
        ]);
    }

    public function add() {
        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->get();
        return view('admin.administrators.create')
            ->with([
                'roles' => $roles
            ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'role'      => ['required', 'numeric'],
            'mobile'    => ['required', 'numeric'],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:bsoft_users'],
            'password'  => ['required', 'string', 'confirmed', 'min:8'],
            'address'   => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $client = new User();

        $client->role_id = $request->post('role');
        $client->name = $request->post('name');
        $client->email = $request->post('email');
        $client->password = Hash::make($request->post('password'));
        $client->mobile = substr($request->post('mobile'), -10);
        $client->address = $request->post('address');
        $client->image = $request->post('image');
        $client->note = $request->post('note');

        if(!$client->save()) {
            return redirectBackWithNotification();
        }
        addActivity('user', $client->id, 'Administrator Created');

        return redirectUrlWithNotification(route('administrators.all'), 'success', 'Administrator Successfully Created!');
    }

    public function edit($id) {
        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $administrator = User::whereIn('role_id', $roles)
            ->find($id);


        return view('admin.administrators.edit')->with([
            'administrator'    => $administrator
        ]);
    }

    public function update(Request $request, $id) {
        $roles = Role::whereIn('role_slug', ['administrator', 'manager', 'accountant'])
            ->pluck('role_id')
            ->toArray();

        $administrator = User::whereIn('role_id', $roles)
            ->where('id', '=', $id)
            ->first();

        $validator = Validator::make($request->all(), [
            'role'      => ['required', 'string', 'max:55'],
            'mobile'    => ['required', 'numeric'],
            'name'      => ['required', 'string', 'max:255'],
            'password'  => ['nullable', 'string', 'confirmed', 'min:8'],
            'address'   => ['nullable', 'string', 'min:8', 'max:255'],
        ]);
        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }
        if($administrator->email !== $request->post('email')) {
            $validator = Validator::make($request->all(), [
                'email'     => ['string', 'email', 'max:255', 'unique:bsoft_users'],
            ]);
            if ($validator->fails()) {
                return redirectBackWithValidationError($validator);
            }
        }

        $role = Role::whereRoleSlug($request->post('role'))->firstOrFail();

        $administrator->role_id = $role->role_id;
        $administrator->name = $request->post('name');
        $administrator->email = $request->post('email');
        if($request->post('password')) {
            $administrator->password = Hash::make($request->post('password'));
        }
        $administrator->mobile = substr($request->post('mobile'), -10);
        $administrator->address = $request->post('address');
        $administrator->image = $request->post('image');
        $administrator->note = $request->post('note');

        $administrator->save();

        addActivity('user', $administrator->id, 'Administrator Updated');

        return redirectUrlWithNotification(route('administrators.show', ['id' => $administrator->id]));
    }
}
