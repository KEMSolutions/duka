<?php namespace App\ApiObjects;

use KemAPI;
use Localization;

class Customers extends KemApiObject
{
    /**
     * @var string  Validating base64 encoded emails.
     */
    protected $slugInvalidCharacters = '/[^a-z0-9=\/+]/i';

    public function __construct() { parent::__construct('customers'); }

    public function create($email, $name = null, $postcode = null, $language = null)
    {
        $user = $this->getCustomerObject($email, $name, $postcode, $language);

        // TODO: check for validation errors.
        // ...

        // Create user.
        return KemAPI::post($this->baseRequest, $user);
    }

    public function update($id, $email, $name = null, $postcode = null, $language = null)
    {
        $user = $this->getCustomerObject($email, $name, $postcode, $language);

        // TODO: check for validation errors.
        // ...

        // Create user.
        return KemAPI::put($this->baseRequest .'/'. $id, $user);
    }

    private function getCustomerObject($email, $name = null, $postcode = null, $language = null)
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
