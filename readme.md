# Duka

Duka is a fully functional eCommerce application built on the [KEM API](http://docs.kem.guru). Duka is open source and released under the [MIT license](http://opensource.org/licenses/MIT).

# Setup

Duka is built on the popular PHP framework [Laravel](http://laravel.com). To get started, make sure
[composer is installed](https://getcomposer.org/).

1. Fork this repository and clone it on your machine.
2. Create an environment file: `cp .env.sample .env` and update the values accordingly.
4. Install the dependencies: `composer install`.

Since running composer doesn't require root access, Duka can be deployed on almost any shared hosting running PHP 5.5.9+. Note that you might also have to create a symlink to boukem's /public folder:

E.g. on Cpanel: ```ln -s my-duka-fork/public public_html```

Duka source code contains a Procfile, allowing it to run on Heroku on apache2, out of the box. At this moment, nginx is not supported.

# Quick Reference

## Javascript

Duka makes use of a combination of [Semantic UI](http://semantic-ui.com) features and custom components. We are using Gulp to create a minified, uglified production script.  
The script entry point is located in `public/js/dev/components/init.js`, where all independant modules should be called. 



###### File structure (as of 18/09/2015)

The `/public/js` folder contains 3 subfolders: *data*, *dev*, *prod*.

-`data`  
This folder contains json resources to be taken advantage of. (country list, world states, ...).

-`dev/components`  
Every component should be stored here. We tried to roughly follow the same folder hierarchy that is in the `/resources/views` since each component should be responsible for a specific view feature.  
Some components require more than one file to enable their full feature (eg. cart-drawer), the `*InitContainer*` should be the one called in `init.js`.

-`utils`  
This folder regroups all helper, miscellaneous, utilities functions. In our case, the `UtilityContainer` plays that role. 

-`prod`
This folder should only contain production scripts. Ideally, only one minified script combining all components located in `dev/components` should be here. 

###### Components. 
Every component are created in independent files and are suffixed by the keyword `Container`.
If a component can stand on its own (eg. with no component dependencies), its last method should be an `init` method that is registered in `public/js/dev/components/init.js`. 


## Routing

Most named routes are defined in "app/Http/routes.php" and can be used through the [route()](http://laravel.com/docs/5.0/routing#named-routes) shortcut in Laravel:

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

Generally, interactions with the API should be done through the other facades, defined in "app/ApiObjects":

    // Some examples of how the facades can be used.
	$brand = Brands::get( 444 );
    $category = Categories::get( 300 );
    $customer = Customers::get( 'john@example.com' );
	$homepage = Layouts::get( '' );
    $product = Products::get( 616 );
    $estimate = Orders::estimate( $products, $address );

That is all.