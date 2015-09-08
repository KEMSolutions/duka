<?php

namespace App\Http\Middleware;

use Closure;

class ValidateWebhook
{

    /**
     * Generate a signature using the same method used by the console to sign webhooks and the api key we have on file
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    protected function expectedSignatureForRequestBody($body) {
        $apiKey = getenv("KEM_API_KEY");

        $signature = base64_encode(hash('sha512', $body . $apiKey, true));

        return $signature;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $expectedSignature = $this->expectedSignatureForRequestBody($request->getContent());
        $receivedSignature = $request->getUser();

        if ($expectedSignature !== $receivedSignature){
            return response('Invalid webhook signature.', 401);
        }


        return $next($request);
    }
}
