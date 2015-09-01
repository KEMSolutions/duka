<?php namespace App\Http\Controllers;

use Log;
use Auth;
use Lang;
use Crypt;
use Store;
use Session;
use Redirect;
use Customers;
use Localization;

use Illuminate\Support\Str;
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

		// Enable the guest middleware.
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
     *
     * @param Illuminate\Http\Request $request
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

		$details = $request->except(['password', 'password_confirmation', '_token']);

        // Validate password, if we are updating that as well. The password confirmation
        // has been checked by the validator already, so we don't need to worry about that.
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
	public function getReset() {
		return view('auth.reset');
	}

	/**
	 * Handles post requests from the password reset form.
	 */
	public function postReset(Request $request)
	{
		// Retrieve email address and find related customer account.
		$email = $request->input('email');
		$customer = Customers::get($email);
		if (Customers::isError($customer)) {
			return redirect(route('auth.reset'))->withErrors([Lang::get('passwords.user')])->withInput();
		}

		// Create the reset token. This is the same hasing used by Laravel
		// in \Illuminate\Auth\Passwords\DatabaseTokenRepository;
		$token = hash_hmac('sha256', Str::random(40), config('app.key'));

		// Since we don't have a password_resets table, we'll attach the customer's ID to this token
		// so that we may easily retrieve their record later on.
		$link = Crypt::encrypt($customer->id .':'. $token);

		// Store the token.
		$customer->metadata['password_token'] = $token;
		$saved = Customers::update($customer, []);
		if (Customers::isError($saved)) {
			Log::error('Could not save the password token to reset a customer password.');
			return redirect(route('auth.reset'))->withErrors([Lang::get('boukem.error_occurred')])->withInput();
		}

		// Retrieve the email template.
		$view = config('auth.password.email', 'emails.password');
		$data = [
			'token' => urlencode($link),
			'customer' => $customer,
			'store' => Store::info()
		];

		// Send the email.
		$sent = app()->mailer->send($view, $data, function($mailer) use($customer) {
			$mailer->to($customer->getEmailForPasswordReset());
		});

		return redirect(route('home'))->withMessages([Lang::get('passwords.sent')]);
	}

	/**
	 * Handles a customer password reset request.
	 *
	 * @param string $emailToken
	 */
	public function getToken($emailToken)
	{
		// Retrieve data from the token.
		if (!$emailToken = Crypt::decrypt($emailToken)) {
			Log::error('Could not retrieve data from password token.');
			return redirect(route('home'))->withErrors([Lang::get('boukem.error_occurred')]);
		}

		// The email token includes the password token and customer ID.
		$emailToken = @explode(':', $emailToken, 2);
		$customerId = (int) $emailToken[0];
		$passwordToken = $emailToken[1];
		if ($customerId < 1 || strlen($passwordToken) < 1) {
			Log::error('Invalid password token data.');
			return redirect(route('home'))->withErrors([Lang::get('boukem.error_occurred')]);
		}

		// Retrieve the customer record and remove the token.
		$customer = Customers::get($customerId);
		if (Customers::isError($customer)) {
			Log::error('Could not retrieve customer record with id "'. $customerId .'".');
			return redirect(route('home'))->withErrors([Lang::get('boukem.error_occurred')]);
		}

		$checkToken = $customer->metadata['password_token'];
		$customer->metadata['password_token'] == '';
		Customers::update($customer, []);

		// Validate the password token.
		if ($passwordToken !== $checkToken) {
			return redirect(route('home'))->withErrors([Lang::get('passwords.token')]);
		}

		// Log in the customer, so that they may reset their password.
		Auth::login($customer);

		return redirect(route('auth.account'))->withMessages([Lang::get('passwords.new')]);
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
}
