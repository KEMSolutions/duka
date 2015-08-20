# Boukem 2

TODO: include a nice & short description... :)

# Setup

Boukem 2 is built on the popular PHP framework [Laravel](http://laravel.com). To get started, make sure
[composer is installed](https://getcomposer.org/) on your machine and run the following commands from the root folder.

1. Install the dependencies: `composer install`.
2. Create an environment file: `cp .env.sample .env`.
3. Setup the database: `cp storage/database.sqlite.sample storage/database.sqlite`.

You're now ready to go! Have a look at the environment file and update the relevant parameters.

# Quick Reference

## Front-end 

#####Javascript
######Global
The script entry point is located in `js/dev/actions/init.js`, where all the modules are registered. 

Every module has its own namespace (suffixed by `Container`).

We are using Gulp to create a minified version of all the scripts. 
The gulpfile is located at the root of the project and uses Laravel Elixir.

######File structure (as of 20/07/2015)
```
js
│
└───dev
|    └───actions
|    |   └─── checkout
|    |   └─── layout
|    |   └─── products
|    |   └─── site
|    |
|    └─── init.js
|    |
|    └─── components
|    |    └─── checkout
|    |    └─── layout
|    |    └─── products
|    |    └─── site
|    |   
|    └─── utils
|
└───prod
```
We are trying to be as consistent as possible with the `resources/views` filetree, so as to be the most clear about each script responsability.  
The subfolder `actions` holds every view specific overall logic whereas `components` owns only individual modules.  
There is one Utility module providing miscellaneous functions , located in `js/dev/utils/utility.js`.  
After gulping all the scripts in the `dev` folder, there will be one minified and optimized script located in `js/prod/boukem2.js`.  
The master page refers to this production script.


######Modules. 
Every module (whether under `actions`, `components` or `utils`) follows the same naming and coding convention, thus allowing a better and more consistent understanding.  
All modules' namespaces are suffixed with the keyword `Container`.  
The last method inside a module is an `init` method, registering all methods that wished to be called outside of the container.

*Note that `UtilityContainer` available in `utility.js` do not have an `init` method due to its helper nature.

#####Views
Coming later...

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