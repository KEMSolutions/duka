<?php namespace App\Http\Controllers\Auth;

use Customers;
use Localization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * This controller handles the registration of new users, as well as the
 * authentication of existing users. By default, this controller uses
 * a simple trait to add these behaviors.
 *
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
	use AuthenticatesAndRegistersUsers {
        postRegister as registerNewUser;
    }

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
        $this->redirectTo = route('home');

		$this->middleware('guest', ['except' => 'getLogout']);
	}

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        // TODO: Check that user email doesn't already exist.
        // ...

        // Retrieve a unique user ID through the API.
        $user = Customers::create(
            $request->input('email'),
            $request->input('name'),
            $request->input('postcode')
        );

        // Check for errors.

        // Add our new user ID.
        $request->merge(['id' => $user->id]);

        // Register the user locally.
        return $this->registerNewUser($request);
    }
}
