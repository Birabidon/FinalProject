<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanChangeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user'); // Get the user from the route
        if(Auth::user()->can('update', $user)) { // Check if the logged-in user is the same as the user in the route or if the logged-in user is an admin
            return $next($request);
        }
        return redirect()->back()->with('error', 'You are not authorized to edit this user');
    }
}
