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

    /**
     * Overwrite base method to prevent caching.
     *
     * @param mixed $id     ID or email of customer.
     * @return object       Requested object.
     */
    public function get($id)
    {
        // Performance check.
        if ((is_numeric($id) && $id < 0) || preg_replace($this->slugInvalidCharacters, '', $id) != $id) {
            return $this->badRequest('Invalid identifier [req: '. $this->baseRequest .'].');
        }

        return KemAPI::get($this->baseRequest .'/'. $id);
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
     * @param null $name
     * @param null $postcode
     * @param null $language
     * @return \stdClass
     */
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

