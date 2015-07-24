<?php namespace App\ApiObjects;

use KemAPI;
use Localization;

class Customers extends BaseObject
{
    /**
     * @var string  Validating base64 encoded emails.
     */
    protected $slugInvalidCharacters = '/[^a-z0-9=\/+]/i';

    public function __construct() { parent::__construct('customers'); }

    /**
     * Retrieves a customer record.
     *
     * @param mixed $id             ID or email of customer.
     * @param array $requestParams  Parameters to include with API request.
     * @param int $expires          Hours to keep object in cache.
     * @return object               Customer record.
     */
    public function get($id, $requestParams = [], $expires = 0)
    {
        // If we're retrieving a record by email and it hasn't already been base64 encoded,
        // we need to handle that.
        if (!is_numeric($id) && strpos($id, '@')) {
            $id = base64_encode($id);
        }

        return parent::get($id, $requestParams, $expires);
    }

    /**
     * @param $email
     * @param null $name
     * @param null $postcode
     * @param null $language
     * @return mixed
     */
    public function create($email, $name = null, $postcode = null, $language = null)
    {
        $user = $this->getCustomerObject($email, $name, $postcode, $language);

        // TODO: check for validation errors.
        // ...

        // Create user.
        return KemAPI::post($this->baseRequest, $user);
    }

    /**
     * @param $id
     * @param $email
     * @param null $name
     * @param null $postcode
     * @param null $language
     * @return mixed
     */
    public function update($id, $email, $name = null, $postcode = null, $language = null)
    {
        $user = $this->getCustomerObject($email, $name, $postcode, $language);

        // TODO: check for validation errors.
        // ...

        // Create user.
        return KemAPI::put($this->baseRequest .'/'. $id, $user);
    }

    /**
     * @param $email
     * @param mixed $name
     * @param mixed $postcode
     * @param mixed $language
     * @return \stdClass
     */
    public function getCustomerObject($email, $name = null, $postcode = null, $language = null)
    {
        // TODO: validate data.
        // ...

        // Build user object for API.
        $user = new \stdClass;
        $user->email = $email;
        $user->name = $name;
        $user->postcode = $postcode;
        $user->language = $language ?: Localization::getCurrentLocale();

        return $user;
    }
}

