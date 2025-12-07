<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class IsAccountApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty(Auth::user()->approved_by)) {

            return response()->json([
                'message' =>  'Account has not been approved',
                'status' => 'account_approval_required'
            ], 400);;
        }

        return $next($request);
    }
}
