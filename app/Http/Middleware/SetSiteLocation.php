<?php

namespace App\Http\Middleware;

use App\Models\SiteQrCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SetSiteLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->location) {
            $siteLocation = SiteQrCode::find($request->location)->location;
            Session::put('location', $siteLocation);
        }

        return $next($request);
    }
}
