<?php namespace App\Providers;

use Arr;
use Customers;

use App\Models\Customer;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class KemApiUserProvider implements UserProvider
{
    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     */
    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return new Customer((array) Customers::get($identifier));
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $customer = $this->retrieveById($identifier);

        // Check if customer has a "remember me" token.
        return Arr::has($customer->metadata, 'remember_token') ? $customer : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \App\Models\Customer $customer
     * @param string $token
     * @return void
     */
    public function updateRememberToken(UserContract $customer, $token)
    {
        $customer->metadata['remember_token'] = $token;

        Customers::update($customer, []);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     * @return \App\Models\Customer|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Retrieve customer record.
        $record = Customers::get($credentials['email']);
        if (Customers::isError($record)) {
            return null;
        }

        return new Customer((array) $record);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials) {
        return $this->hasher->check($credentials['password'], $user->getAuthPassword());
    }
}
