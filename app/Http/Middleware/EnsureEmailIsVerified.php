<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
//        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
//            return response()->json([
//                'message' => 'Your email address is not verified.',
//                'verification_required' => true
//            ], 403);
//        }

        if (!$request->user() ) {
            return response()->json([
                'message' => 'Your email address is not verified.',
                'verification_required' => true
            ], 403);
        }

        return $next($request);
    }
}
