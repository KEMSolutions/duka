<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * Middleware to check CSRF token on all requests, even GET, OPTIONS and HEAD.
 *
 * @package App\Http\Middleware
 */
class VerifyCsrfTokenOnAllRequests extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
	 */
	public function handle($request, Closure $next)
    {
        if ($this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
	}

}
