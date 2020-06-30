<?php

namespace App\Http\Middleware;

use Closure;

class CheckInstall
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
        //return $next($request);
        $filename = storage_path("installed");
        if (file_exists($filename)) {
            return $next($request);
        } else {
            return redirect('/install');
        }
    }
}
