<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function __construct() {
        $this->middleware('can:manage-shifts');
    }

    public function index() {
        $clients = Role::whereRoleSlug('client')->firstOrFail()
            ->users()
            ->orderBy('name')
            ->get();

        return view('admin.client.index')
            ->with([
                'clients' => $clients
            ]);
    }

    public function show($id) {
        $client = Role::whereRoleSlug('client')->firstOrFail()
            ->users()
            ->where('id', '=', $id)
            ->firstOrFail();

        $received = $client->clientPayments()->where('payment_type', 'credit')->get();
        $payable = $client->clientProjects->sum('project_price');

        return view('admin.client.show')->with([
            'client'    => $client,
            'received'  => $received,
            'payable'   => $payable
        ]);
    }

    public function edit($id) {
        $client = Role::whereRoleSlug('client')->firstOrFail()
            ->users()
            ->findOrFail($id);

        return view('admin.client.edit')
            ->with([
                'client'    => $client
            ]);
    }

    public function update(Request $request, $id) {
        $client = Role::whereRoleSlug('client')->firstOrFail()
            ->users()
            ->findOrFail($id);
        $validator = Validator::make($request->all(), [
            'mobile'    => ['required', 'string', 'max:255'],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'string', 'email', 'max:255'],
            'address'   => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $client->name = $request->post('name');
        $client->email = $request->post('email');
        $client->mobile = substr($request->post('mobile'), -10);
        $client->address = $request->post('address');
        $client->image = $request->post('client_image');
        $client->note = $request->post('note');

        $client->save();

        addActivity('user', $client->id, 'Client Updated');

        return redirectUrlWithNotification(route('client.show', ['id' => $client->id]));
    }

    public function delete($id) {

    }
}
