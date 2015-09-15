<?php namespace App\Http\Middleware;

use Closure;

use App\Http\Middleware\VerifyCsrfToken;

class VerifyApiCsrfToken extends VerifyCsrfToken
{
    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        // If the base class has URIs to pass through, let them pass.
        if (parent::shouldPassThrough($request)) {
            return true;
        }

        // If the request has authentication headers, we will let that pass through as well and
        // let the Authentication middleware do its job.
        // if ($request->headers->has('Authorization')) {
        if ($request->getUser() && $request->getPassword()) {
            return true;
        }

        return false;
    }
}
