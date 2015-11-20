<?php

namespace App\Http\Middleware;

use Closure;

class ClearBladeExtendedCache
{
    /**
     * Handle an incoming request and delete all view cache if the request is made in the local environment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (env('APP_ENV') === 'local') {
            $cachedViewsDirectory=app('path.storage').'/framework/views/';
            $files = glob($cachedViewsDirectory.'*');
            foreach($files as $file) {
                if(is_file($file)) {
                    @unlink($file);
                }
            }
        }


        return $next($request);
    }
}
