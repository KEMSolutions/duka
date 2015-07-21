<?php namespace App\Http\Controllers\Auth;

use Log;
use Redirect;
use Session;
use Validator;
use Customers;
use Localization;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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
	use AuthenticatesAndRegistersUsers, ThrottlesLogins {
        AuthenticatesAndRegistersUsers::postRegister as registerNewUser;
        AuthenticatesAndRegistersUsers::postLogin as loginUser;
    }

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 */
	public function __construct()
	{
        // Define some paths.
        $this->loginPath = route('auth.login');
        $this->redirectAfterLogout = $this->redirectPath = route('home');

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
        // Performance check.
        if (User::findByEmail($request->input('email')))
        {
            // TODO: localize.
            return redirect(route('auth.login'))
                ->withInput($request->only('email'))
                ->withMessages('[test] Account already exists.');
        }

        // Validate user details.
        $user = Customers::getCustomerObject(
            $request->input('email'),
            $request->input('name'),
            $request->input('postcode')
        );

        // Check if user already exists on the main server.
        $record = Customers::get($user->email);
        if (!Customers::isError($record))
        {
            $request->merge([
                'id' => $record->id,
                'email' => $record->email,
                'name' => $record->name,
                'postcode' => $record->postcode,
                'language' => $record->language
            ]);
        }

        // If not, create them and retrieve their unique ID.
        else
        {
            $user = Customers::create($user->email, $user->name, $user->postcode);

            // Catch any errors from the server.
            if (Customers::isError($user))
            {
                Log::error('Could not create user on main server.');
                abort(500);
            }

            // Update new user details with validated data & user ID.
            $request->merge([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'postcode' => $user->postcode,
                'language' => $user->language
            ]);
        }

        // Register the user locally.
        return $this->registerNewUser($request);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        // TODO: make sure the form fields have the attribute "required."

        $username = $request->input($this->loginUsername());

        // Check if the user is in our database.
        if (!User::where([$this->loginUsername() => $username])->first())
        {
            // If the user does not exist in our database, or on the main server, redirect
            // them to the registration page.
            // TODO: localize.
            $record = Customers::get($username);
            if (Customers::isError($record))
            {
                return redirect(route('auth.register'))
                    ->withInput($request->only($this->loginUsername()))
                    ->withMessages(['[test] That account does not exist.']);
            }

            // If the user does not exist in our database but has an account on the main server,
            // invite them to create a new password here. Maybe the database was reset...
            // TODO: localize.
            else
            {
                return redirect(route('auth.register'))
                    ->withInput(['name' => $record->name, $this->loginUsername() => $record->email])
                    ->withMessages(['[test] Please create a new password.']);
            }
        }

        return $this->loginUser($request);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
            'id' => $data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'language' => $data['language']
        ]);
    }

    /**
     * @param string $redirectTo
     * @param array $messages
     * @return mixed
     */
    private function fail($redirectTo, array $messages = []) {
        return redirect($redirectTo)->withErrors($messages);
    }
}

