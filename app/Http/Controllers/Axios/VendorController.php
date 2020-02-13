<?php

namespace App\Http\Controllers\Axios;

use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index(Request $request, $project_id) {
        $per_page = ($request->get('per_page'))? $request->get('per_page') : 15;
        $search = htmlspecialchars($request->get('search'));

        if(Auth::user()->isAdmin() || Auth::user()->isAccountant()) {
            $project = Project::find($project_id);
        } else {
            $project = Auth::user()->projects()
            ->where('project_status', '=', 'active')
            ->where('project_id', '=', $project_id)
            ->first();
        }
        if(!$project) {
            return response([
                'error'     => 'Not Found',
                'message'   => 'Requested Project not found or you are not authorised!'
            ], 404);
        }
        $role_id = Role::whereRoleSlug('supplier')->firstOrFail()->role_id;
        $vendors = $project->employees()
            ->where('role_id', '=', $role_id)
            ->orderBy('name')
            ->simplePaginate($per_page);

        return response()->json($vendors, 200);
    }
}
