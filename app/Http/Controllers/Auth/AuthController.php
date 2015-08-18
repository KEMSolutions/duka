<?php namespace App\Http\Controllers\Auth;

use Log;
use Auth;
use Lang;
use Crypt;
use Session;
use Redirect;
use Customers;
use Validator;
use Localization;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


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

		$this->middleware('guest', ['except' => ['getLogout', 'getAccount', 'postAccount']]);
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
            return redirect(route('auth.login'))
                ->withInput($request->only('email'))
                ->withMessages([Lang::get('boukem.account_exists')]);
        }

        // Validate user details.
        $details = Customers::getCustomerObject([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'postcode' => $request->input('postcode')
        ]);

        // Check if user already exists on the main server.
        $record = Customers::get($details->email);
        if (Customers::isError($record))
        {
            $record = Customers::create((array) $details);

            // Catch any errors from the server.
            if (Customers::isError($record))
            {
                Log::error('Could not create customer record on main server.');
                abort(500);
            }
        }

        // Update user details with validated data & user ID.
        $request->merge([
            'id' => $record->id,
            'email' => $record->email,
            'name' => $record->name,
            'postcode' => $request->input('postcode', $record->postcode),
            'language' => $record->language
        ]);

        // Add a record for our new user in the local database.
        $user = $this->create($request->all());

        // Because the ID is the primary key, it will be incremented in this instance of $user.
        // We'll have to change it back just so we can log them in. The database record, however,
        // has the right information.
        $user->id = $record->id;

        // Log them in.
        Auth::login($user);

        return redirect($this->redirectPath());
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $username = $request->input($this->loginUsername());

        // Check if the user is in our database.
        if (!User::where([$this->loginUsername() => $username])->first())
        {
            // If the user does not exist in our database, or on the main server, redirect
            // them to the registration page.
            $record = Customers::get($username);
            if (Customers::isError($record))
            {
                return redirect(route('auth.register'))
                    ->withInput($request->only($this->loginUsername()))
                    ->withMessages([Lang::get('boukem.account_does_not_exists')]);
            }

            // If the user does not exist in our database but has an account on the main server,
            // invite them to create a new password here. Maybe the database was reset...
            else
            {
                return redirect(route('auth.register'))
                    ->withInput(['name' => $record->name, $this->loginUsername() => $record->email])
                    ->withMessages([Lang::get('boukem.please_create_password')]);
            }
        }

        return $this->loginUser($request);
    }

    public function getAccount()
    {
        // Check that user is authenticated.
        if (!Auth::check()) {
            return redirect(route('auth.login'));
        }

        return view('auth.account')->withUser(Customers::get(Auth::user()->id));
    }

    public function postAccount(Request $request)
    {
        // Check that user is authenticated.
        if (!Auth::check()) {
            return redirect(route('auth.login'));
        }

        // Make sure we're editing a valid customer record.
        if (!$record = Customers::get(Auth::user()->id)) {
            Log::error('Could not retrieve customer record while updating account details.');
            abort(500);
        }

        // Validate password, if we are updating that as well.
        $updatePasswd = false;
        $passwd = $request->input('password');
        if (strlen($passwd))
        {
            if ($passwd !== $request->input('password_confirmation') || strlen($passwd) < 6) {
                return redirect(route('auth.account'))->withMessages([Lang::get('passwords.password')]);
            }

            $updatePasswd = true;
            $request->merge([
                'metadata' => [
                    'password' => bcrypt($passwd)
                ]
            ]);
        }

        // Update account details.
        $result = Customers::update(Auth::user()->id, $request->except(['password', 'password_confirmation']));

        // Update local database.
        if (!Customers::isError($result))
        {
            $message = Lang::get('boukem.account_saved');

            $user = Auth::user();
            $user->email = $result->email;
            $user->name = $result->name;
            if ($updatePasswd) {
                $user->password = bcrypt($passwd);
            }

            $user->save();
        }
        else {
            $message = Lang::get('boukem.account_not_saved');
        }

        return redirect(route('auth.account'))->withMessages([$message]);
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

