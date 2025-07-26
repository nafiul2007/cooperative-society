<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $member = Auth::user()?->member;

        if (!$member) {
            return redirect()->route('profile.edit')->with('warning', 'Please complete your profile to continue.');
        }

        return $next($request);
    }
}
