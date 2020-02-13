<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['showProjects', 'show']);
    }

    public function showProjects($status = null)
    {
        $projects = null;

        if ($status !== null && in_array($status, ['active', 'hold', 'canceled', 'completed'])) {
            if (Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
                $projects = Project::where('project_status', '=', $status)
                    ->orderByDesc('created_at')
                    ->get();
            } else {
                $projects = Auth::user()->projects()
                    ->where('project_status', '=', $status)
                    ->orderByDesc('created_at')
                    ->get();
            }
        } else {
            if (Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
                $projects = Project::orderByDesc('created_at')
                    ->get();
            } else {
                $projects = Auth::user()->projects()
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        $page_title = 'All Projects';
        switch ($status) {
            case 'active':
                $page_title = 'Active Projects';
                break;
            case 'hold':
                $page_title = 'Hold Projects';
                break;
            case 'canceled':
                $page_title = 'Canceled Projects';
                break;
            case 'completed':
                $page_title = 'Completed Projects';
                break;
        }

        if (!$projects) {
            return view('admin.project.lists')->with([
                'message' => 'No Records Found!',
                'alert-type' => 'error',
                'page_title' => $page_title
            ]);
        }

        return view('admin.project.lists')->with([
            'projects' => $projects,
            'page_title' => $page_title
        ]);
    }

    public function assignToProject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $project = Project::findOrFail($id);
        $existing = $project->projectLogs()
            ->where('pl_user_id', '=', $request->post('admin'))
            ->first();
        if ($existing) {
            return redirectBackWithNotification('error', 'Already Assigned To This Project');
        }

        $pLogs = new ProjectLog();

        $pLogs->pl_project_id = $project->project_id;
        $pLogs->pl_user_id = $request->post('admin');

        if ($pLogs->save()) {
            addActivity('project', $project->project_id, 'Project Assigned');
            addActivity('user', $request->post('admin'), 'Project Assigned');
            return redirectBackWithNotification('success', 'Admin Successfully Assigned To this Project.');
        }

        return redirectBackWithNotification();
    }

    public function show($id)
    {
        $project = null;

        if (Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::find($id);
        } else {
            $project = Auth::user()->projects()->find($id);
        }
        if (!$project) {
            return redirectBackWithNotification('error', 'Project not found or not authorised!');
        }

        $role_manager = Role::whereRoleSlug('manager')
            ->firstOrFail();

        $admins = $role_manager->users;

        $assignedAdmins = $project->users()
            ->where('role_id', '=', $role_manager->role_id)
            ->get();

        $received = $project->payments()->where('payment_type', '=', 'credit')
            ->where('payment_purpose', '=', 'project_money')
            ->get();

        if (!Auth::user()->isAdmin() && !Auth::user()->isAccountant()) {
//            $expenses = $project->payments()->where('payment_type', '=', 'debit')
//                ->where('payment_from_user', '=', Auth::id())
//                ->orderByDesc('payment_date')
//                ->groupby('payment_purpose')
//                ->get();

            $expenses = $project->payments()->where('payment_type', '=', 'debit')
//                ->orderByDesc('bsoft_payments.payment_date')
                ->select(
                    DB::raw('
                            payment_purpose,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_by) AS payment_by,
                            MAX(payment_date) AS payment_date
                    '))
                ->groupby('payment_purpose')
                ->get();
        } else {
            $expenses = $project->payments()->where('payment_type', '=', 'debit')
//                ->orderByDesc('bsoft_payments.payment_date')
                ->select(
                    DB::raw('
                            payment_purpose,
                            sum(payment_amount) AS payment_amount,
                            MAX(payment_by) AS payment_by,
                            MAX(payment_date) AS payment_date
                    '))
                ->groupby('payment_purpose')
                ->get();

        }

        return view('admin.project.show')->with([
            'project' => $project,
            'admins' => $admins,
            'assigned' => $assignedAdmins,
            'received' => $received,
            'expenses' => $expenses
        ]);
    }

    public function add()
    {
        return view('admin.project.add')->with([
            'clients' => Role::whereRoleSlug('client')->firstOrFail()->users()->orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $client = null;
        if ($request->post('client_type') == 'old') {
            $client = Role::whereRoleSlug('client')->firstOrFail()
                ->users()
                ->find($request->post('client_id'));
        }
        if ($request->post('client_type') == 'new') {
            $client = $this->createNewClient($request->all());
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'location' => ['required', 'string', 'max:191'],
            'price' => ['required', 'numeric'],
            'status' => ['required', 'string', 'min:4'],
            'description' => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $project = new Project();

        $project->project_client_id = $client->id;
        $project->project_name = $request->post('name');
        $project->project_location = $request->post('location');
        $project->project_price = $request->post('price');
        $project->project_status = $request->post('status');
        $project->project_description = $request->post('description');
        $project->project_image = $request->post('project_image');

        $project->save();

        addActivity('project', $project->project_id, 'Project Created');

        return redirectUrlWithNotification(route('project.show', ['id' => $project->project_id]),
            'success', 'Project Successfully Created!');
    }

    public function edit($id)
    {
        $project = Project::find($id);

        return view('admin.project.edit')->with([
            'project' => $project,
            'clients' => Role::whereRoleSlug('client')->firstOrFail()->users()->orderBy('name')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return redirectBackWithNotification('error', 'Project not found authorised!');
        }

        $client = null;
        if ($request->post('client_type') == 'old') {
            $client = Role::whereRoleSlug('client')->firstOrFail()
                ->users()
                ->find($request->post('client_id'));
        }
        if ($request->post('client_type') == 'new') {
            $client = $this->createNewClient($request->all());
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'location' => ['required', 'string', 'max:191'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $project->project_client_id = $client->id;
        $project->project_name = $request->post('name');
        $project->project_location = $request->post('location');
        $project->project_price = $request->post('price');
        $project->project_description = $request->post('description');
        $project->project_image = $request->post('project_image');;

        $project->save();

        addActivity('project', $project->project_id, 'Project Updated');

        return redirectUrlWithNotification(route('project.show', ['id' => $project->project_id]),
            'success', 'Project Successfully Updated!');
    }

    public function delete($id)
    {

    }

    /**
     * @param array $data
     * @return User|\Illuminate\Http\RedirectResponse
     */
    protected function createNewClient(array $data)
    {
        $validator = Validator::make($data, [
            'client_number' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['nullable', 'string', 'email', 'max:255'],
            'client_address' => ['nullable', 'string', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirectBackWithValidationError($validator);
        }

        $role = Role::whereRoleSlug('client')->firstOrFail();

        $client = new User();

        $client->role_id = $role->role_id;
        $client->name = $data['client_name'];
        $client->email = $data['client_email'];
        $client->mobile = substr($data['client_number'], -10);
        $client->address = $data['client_address'];
        $client->save();

        addActivity('user', $client->id, 'Client Created');

        return $client;
    }

    public function changeStatus(Request $request)
    {
        $project = Project::findOrFail($request->post('project'));

        $project->project_status = $request->post('status');

        if ($project->save()) {
            addActivity('project', $project->project_id, 'Project Status Changed!');
            return redirectBackWithNotification('success', 'Project Status Successfully Changed!');
        }
        return redirectBackWithNotification();
    }
}
