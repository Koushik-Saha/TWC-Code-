<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Route;
use URL;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role_count = Role::all()->count();
        $admin = Role::whereRoleName('Administrator')
            ->whereRoleSlug('administrator')
            ->first();

        if(($role_count < 6 || $admin === null) && URL::current() !== url('install')) {
            return redirect()->route('install');
        }

        if($role_count >= 6 && $admin != null && URL::current() === url('install')) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
