<?php namespace App\Http\Controllers;

use Log;
use Auth;
use Lang;
use Crypt;
use Session;
use Redirect;
use Customers;
use Localization;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


class AccountController extends Controller
{
	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	public function __construct()
	{
        // These paths are used internally by Laravel.
        $this->loginPath = route('auth.login');
        $this->redirectAfterLogout = $this->redirectPath = route('home');

		// Enabled the guest middleware.
		$this->middleware('guest', ['except' => ['getLogout', 'getAccount', 'postAccount']]);
	}

	/**
	 * Displays the form allowing customers to edit their details.
	 */
    public function getAccount()
	{
		// Make sure user is logged in.
		if (!Auth::check()) {
            return redirect(route('auth.login'));
        }

        return view('auth.account')->withUser(Customers::get(Auth::user()->id));
    }

	/**
	 * Handles post requests from the account form.
	 */
    public function postAccount(Request $request)
    {
		// Make sure user is logged in.
		if (!Auth::check()) {
            return redirect(route('auth.login'));
        }

        // Validate incoming data.
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect(route('auth.account'))->withValidator($validator)->withInput();
        }

        // Make sure we're editing a valid customer record.
		// TODO: review whether this step is necessary...
        if (!$record = Customers::get(Auth::user()->id)) {
            Log::error('Could not retrieve customer record while updating account details.');
            abort(500);
        }

		$details = $request->except(['password', 'password_confirmation']);

        // Validate password, if we are updating that as well.
        $passwd = $request->input('password');
		$passwd = strlen($passwd) ? $passwd : null;

        // Update customer details.
        $result = Customers::update(Auth::user(), $details, $passwd);

		$message = Customers::isError($result)
			? Lang::get('boukem.account_not_saved')
			: Lang::get('boukem.account_saved');

        return redirect(route('auth.account'))->withMessages([$message]);
    }

	/**
	 * Displays the form allowing customers to reset their password.
	 */
	public function getReset()
	{

	}

	/**
	 * Handles post requests from the password reset form.
	 */
	public function postReset()
	{

	}

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        return Customer::validator($data);
    }

    /**
     * Create a new customer instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Customer
     */
    public function create(array $data)
    {
        // Create customer object.
        $customer = new Customer($data);
        $customer->metadata['password'] = bcrypt($data['password']);

        // Save details on main server.
        return Customers::create($customer);
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
