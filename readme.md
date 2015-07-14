# Boukem 2

TODO: include a nice & short description... :)

# Quick Reference

## Routing

Most named routes are defined in "app\Http\routes.php" and can be used through the [route()](http://laravel.com/docs/5.0/routing#named-routes) shortcut in Laravel:

	// Returns something like "https://example.com/en"
	$url = route( 'home' );

	// Returns something like "https://example.com/en/prod/1234"
    $url = route( 'product', [ 'id' => 1234 ] );
	
	// Returns something like "https://example.com/en/prod/en-some-slug"
    $url = route( 'product', [ 'slug' => 'en-some-slug' ] );
    
For a list of all available routes, run `php artisan route:list` from the root folder.

## API Facades

The `KemAPI` facade is defined in "app/Http/KemApiHttpClient.php" and has two useful methods:

	$foo = KemAPI::get( $request );
    $bar = KemAPI::post( $request );

Generally, interactions with the API should be done through the other facades, defined in "app\ApiObjects":

	$brand = Brands::get( 444 );
    $category = Categories::get( 300 );
	$homepage = Layouts::get( '' );
    $product = Products::get( 616 );
    $estimate = Orders::estimate( $products, $address );

That is all.