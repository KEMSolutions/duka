<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Allow the insertion of product cards in blade templates, simply using the @product(id) directive
		\Blade::directive('product', function($id) {
			// Strangely enough, we'll receive everything that followes product, including parentheses... let's strip them out. 
            $id = preg_replace("/[^0-9]/", "", $id);
            return '<?php
            	$product = \Products::get(' . $id . ');

			if ($product && isset($product->localization)) {
				echo view("product._card", ["product"=>$product])->render();
			}
            ?>';
        });
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
