<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageVisit;

class TrackPageVisits
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            PageVisit::logVisit();
        } catch (\Throwable $e) {
            report($e);
        }

        return $next($request);
    }
}
