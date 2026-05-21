<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrazyRaysCors
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', 'https://crazyrayssolutions.com.pk')
                ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept');
        }

        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', 'https://crazyrayssolutions.com.pk')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept');
    }
}
