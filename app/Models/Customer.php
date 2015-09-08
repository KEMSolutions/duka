<?php namespace App\Models;

use Crypt;
use Store;
use Customers;
use Validator;
use Localization;

use Illuminate\Contracts\Support\Arrayable;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Customer implements AuthenticatableContract, CanResetPasswordContract, Arrayable
{
    public $id = null;
    public $email = '';
    public $postcode = '';
    public $name = '';
    public $locale;
    public $phone = '';
    public $metadata;
    public $addresses = [];
    public $remember_token;

    /**
     * @param array $details
     */
    public function __construct(array $details = [])
    {
        $this->locale = $this->metadata = [];

        $this->fill($details);
    }

    /**
     * Create a validator object to validate customer details.
     *
     * @param array $details    Details to be validated.
     * @return \Illuminate\Contracts\Validation\Validator
     *
     * @deprecated              Use Customers::validate($details) instead.
     */
    public static function validator(array $details) {
        return Customers::validate($details);
    }

    /*
     *
     */
    public function newAddressObject()
    {
        $address = new \stdClass;
        $address->id = 0;
        $address->line1 = '';
        $address->line2 = '';
        $address->postcode = '';
        $address->city = '';
        $address->province = 'QC';
        $address->country = 'CA';
        $address->phone = '';

        return $address;
    }

    /**
     * Get the unique identifier for the user (used internally by Laravel).
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->id;
    }

    /**
     * Get the password for the user (used internally by Laravel).
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->metadata['password'];
    }

    /**
     * Get the e-mail address where password reset links are sent (used internally by Laravel).
     *
     * @return string
     */
    public function getEmailForPasswordReset() {
        return $this->email;
    }

    /**
     * Get the token value for the "remember me" session (used internally by Laravel).
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->metadata['remember_token'];
    }

    /**
     * Set the token value for the "remember me" session (used internally by Laravel).
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->metadata['remember_token'] = $value;
    }

    /**
     * Get the column name for the "remember me" token (used internally by Laravel).
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Updates the attributes of this customer instance.
     *
     * @param array $details
     * @return void
     */
    public function fill(array $details)
    {
        // Customer ID
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

        // Format locale.
        if (isset($details['locale']) && is_string($details['locale']))
        {
            if (is_string($details['locale']))
            {
                // If the locale is supported, retrieve and store it in the customer model.
                if (array_key_exists($details['locale'], Store::locales()))
                {
                    $this->locale = (array) Store::locales()[$details['locale']];
                }

                // Since the locale has already been updated, we don't need to pass it to the
                // fill method later.
                unset($details['locale']);
            }

            elseif (!is_array($details['locale'])) {
                $details['locale'] = (array) $details['locale'];
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
            foreach (Store::locales() as $locale) {
                if ($locale->language == Localization::getCurrentLocale()) {
                    $this->locale = (array) $locale;
                }
            }
        }

        // Validate some fields.
        $this->postcode = preg_replace('/[^a-z0-9\s]/i', '', $this->postcode);
        if (isset($this->metadata['password'])
            && strlen($this->metadata['password']) > 0
            && strpos($this->metadata['password'], '$2') !== 0) {

            $this->metadata['password'] = Crypt::decrypt($this->metadata['password']);
        }
    }

    /*
     *
     */
    public function toArray() {
        return get_object_vars($this);
    }
}
