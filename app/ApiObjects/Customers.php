<?php namespace App\ApiObjects;

use KemAPI;

class Customers extends KemApiObject
{
    public function __construct() { parent::__construct('customers'); }

    public function create($email, $name = null, $postcode = null, $language = null)
    {
        // TODO: validate data (using User class?).
        // ...

        // Build user object for API.
        $user = new \stdClass;
        $user->email = $email;
        $user->name = $name;
        $user->postcode = $postcode;
        $user->language = $language ?: \Localization::getCurrentLocale();

        // Create user.
        return KemAPI::post($this->baseRequest, $user);
    }
}
