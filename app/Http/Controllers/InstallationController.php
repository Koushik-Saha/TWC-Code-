<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class InstallationController extends Controller
{

    public function install() {
        return view('install');
    }

    public function saveInstallation(Request $request) {
        $validator = Validator::make($request->all(), [
            'company'   => ['required', 'string', 'max:255'],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $notification = [
                'message' => 'Please Fill Up fields properly!',
                'alert-type' =>'error'
            ];
            return redirect()->back()->withInput()->withErrors($validator)->with($notification);
        }

        // create Roles
        $this->createRoles();
        // create Admin
        $this->createAdmin($request->all());
        // store company name

        if(!$this->storeCompanyName($request->post('company'))) {
            abort(500);
        }
        return redirect()->route('index');
    }

    protected function createAdmin(array $data) {
        $admin_role = Role::whereRoleSlug('administrator')->firstOrFail();

        DB::table('bsoft_users')->insert([
            'role_id'   => $admin_role->role_id,
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password'])
        ]);
    }

    protected function createRoles() {
        $names = ['Administrator', 'Manager', 'Project Manager', 'Accountant', 'Client', 'Supplier', 'Engineer', 'Machine', 'Labour', 'Helper'];
        $slugs = ['administrator', 'manager', 'project_manager', 'accountant', 'client', 'supplier', 'engineer', 'machine', 'labour', 'helper'];

        foreach ($names as $index => $name) {
            DB::table('bsoft_roles')->insert([
                'role_name'     => $name,
                'role_slug'     => $slugs[$index]
            ]);
        }
    }

    protected function storeCompanyName(string $name) {
        $option = new Option();

        $option->option_name = 'company_name';
        $option->option_content = $name;

        $option->save();

        $option1 = new Option();

        $option1->option_name = 'company_cash';
        $option1->option_content = 0;

        return $option1->save();
    }
}
