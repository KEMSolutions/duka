<?php namespace App\Http\Middleware;

use Auth;
use Closure;

use Symfony\Component\HttpFoundation\Response;

class ApiBasicAuthentication
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
        $authenticated = false;

        // If we were passed an authorization header, validate it.
        if ($request->getUser())
        {
            $authenticated = Auth::attempt([
                'email' => $request->getUser(),
                'password' => $request->getPassword()
            ]);
        }

        // If not, check if a user was already authenticated.
        else {
            dd(Auth::user());
            $authenticated = (Auth::user());
        }

        // Continue the regular process if authentication passed. If not, ask the client to
        // authenticate using Basic Authentication.
        return $authenticated
            ? $next($request)
            : new Response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
    }
}
