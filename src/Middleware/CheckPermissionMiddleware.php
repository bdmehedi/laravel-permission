<?php

namespace BdMehedi\LaravelPermission\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permissions, $guard = null)
    {
        $permissions = explode('|', $permissions);
        $user = $request->user(trim($guard));

        if (!$user) {
            abort(403);
        }

        foreach ($permissions as $permission) {
            if($user->can(trim($permission))) {
                return $next($request);
            }
        }

        abort(403);
    }
}
