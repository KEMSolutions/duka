<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\ClearBladeExtendedCache'
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'csrf'=>'App\Http\Middleware\VerifyCsrfToken',
        'api.csrf' => 'App\Http\Middleware\VerifyApiCsrfToken',
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.api' => 'App\Http\Middleware\ApiBasicAuthentication',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
		'validateWebhook'=>'App\Http\Middleware\ValidateWebhook',
        'localeSessionRedirect' => 'Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect',
        'localizationRedirect' => 'Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter',
	];

}
