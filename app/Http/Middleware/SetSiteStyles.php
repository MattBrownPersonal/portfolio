<?php

namespace App\Http\Middleware;

use App\Models\Deceased;
use App\Models\Site;
use Closure;
use Illuminate\Http\Request;

class SetSiteStyles
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
        // if request includes a deceased
        $code = preg_match("/^m\/(\d+)-/", $request->path(), $matches);
        if ($code) {
            $deceased = Deceased::where('code', $matches[1])->first();
            if ($deceased) {
                $siteStyle = $deceased->site->sitestyle;
                if (! $siteStyle) {
                    throw new \Exception('Site Style not set for site id:'.$deceased->site->id);
                }
                $request->session()->put('site_primary_colour', $siteStyle['primary_colour']);
                $request->session()->put('site_secondary_colour', $siteStyle['secondary_colour']);
                $request->session()->save();
            } else {
                $request->session()->forget('site_primary_colour');
                $request->session()->forget('site_secondary_colour');
                $request->session()->save();
            }
        } else {
            $code = preg_match("/m\/(.+)/", $request->path(), $matches);
            $site = Site::where('slug', $matches[1])->first();
            if (! $site) {
                $code = preg_match("/\/(.*?)\//", $request->path(), $matches);
                $site = Site::where('slug', $matches[1])->first();
            }
            $siteStyle = $site->sitestyle;
            $request->session()->put('site_primary_colour', $siteStyle['primary_colour']);
            $request->session()->put('site_secondary_colour', $siteStyle['secondary_colour']);
            $request->session()->save();
        }

        return $next($request);
    }
}
