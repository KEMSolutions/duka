<?php namespace App\Models;

use Crypt;
use Customers;
use Validator;
use Localization;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Customer implements AuthenticatableContract, CanResetPasswordContract
{
    public $id = null;
    public $email = '';
    public $postcode = '';
    public $name = '';
    public $locale;
    public $phone = '';
    public $metadata;

    /**
     * @param array $details
     */
    public function __construct(array $details = [])
    {
        // Initialize some attributes.
        $this->locale = $this->metadata = [];

        // Save ID
        if (isset($details['id']) && $details['id'] > 0) {
            $this->id = $details['id'];
        }

        // Format meta data.
        if (isset($details['metadata']))
        {
            switch (gettype($details['metadata']))
            {
                case 'string':
                    $details['metadata'] = json_decode($details['metadata'], true);
                    break;

                case 'object':
                    $details['metadata'] = (array) $details['metadata'];
            }
        }

        // Fill in customer details with new data.
        foreach (get_object_vars($this) as $attribute => $empty)
        {
            if (isset($details[$attribute]) && (gettype($details[$attribute]) == gettype($this->$attribute)))
            {
                $this->$attribute = $details[$attribute];
            }
        }

        // Default locale.
        if (!isset($this->locale['language']) || !Localization::checkLocaleInSupportedLocales($this->locale['language']))
        {
            $this->locale['id'] = Localization::getCurrentLocale() .'-CA';
            $this->locale['name'] = Localization::getCurrentLocaleNativeReading();
            $this->locale['language'] = Localization::getCurrentLocale();
            $this->locale['language_name'] = Localization::getCurrentLocaleName();
            $this->locale['script'] = Localization::getCurrentLocaleScript();
        }

        // Validate some fields.
        $this->postcode = preg_replace('/[^a-z0-9\s]/i', '', $this->postcode);
    }

    /**
     * @param string $email
     * @return mixed
     */
    public static function findByEmail($email) {
        return Customers::get($email);
    }

    /**
     * Create a validator object to validate customer details.
     *
     * @param array $details    Details to be validated.
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validator(array $details)
    {
        return Validator::make($details, [
            'email' => 'required|email|max:255',
            'postcode' => '',
            'name' => 'required|max:255',
            'phone' => 'max:255',
            'password' => 'confirmed|min:6',
        ]);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return Crypt::decrypt($this->metadata['password']);
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset() {
        return $this->email;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->metadata['token'];
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->metadata['token'] = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}

