<?php namespace App\ApiObjects;

use Crypt;
use KemAPI;
use Localization;


use App\Models\Customer;

// TODO: remove this from dependencies.
use League\Flysystem\Adapter\Local;

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
    public function get($id, array $requestParams = [], $expires = 0)
    {
        // If we're retrieving a record by email and it hasn't already been base64 encoded,
        // we need to handle that.
        if (!is_numeric($id) && strpos($id, '@')) {
            $id = base64_encode($id);
        }

        $customer = parent::get($id, $requestParams, $expires);

        // Format metadata attribute.
        if (!$this->isError($customer)) {
            $customer = new Customer((array) $customer);
        }

        return $customer;
    }

    public function create(Customer $customer)
    {
        // Format metadata before saving.
        $customer->metadata['password'] = Crypt::encrypt($customer->metadata['password']);
        $customer->metadata = json_encode($customer->metadata);

        // Create customer record.
        $result = (array) KemAPI::post($this->baseRequest, $customer);

        // Return instance of App\Models\Customer.
        return new Customer($result);
    }

    public function update(Customer $customer)
    {
        // Format metadata before saving.
        $customer->metadata['password'] = Crypt::encrypt($customer->metadata['password']);
        $customer->metadata = json_encode($customer->metadata);

        // Update customer record.
        $result = (array) KemAPI::put($this->baseRequest .'/'. $customer->id, $customer);

        // Return instance of App\Models\Customer.
        return new Customer($result);
    }

    /**
     * Creates a customer object with all the expected fields.
     *
     * @param array $details    Customer details.
     * @param array $locale     Details of customer's selected locale.
     * @return object
     */
    public function getCustomerObject(array $details = [], array $locale = [])
    {
        // Build user object for API.
        $customer = new \stdClass;
        $customer->email = '';
        $customer->phone = '';
        $customer->postcode = '';
        $customer->name = '';
        $customer->language = '';
        $customer->metadata = [];

        // Fill in some attributes.
        foreach (get_object_vars($customer) as $attribute => $empty)
        {
            if (isset($details[$attribute]) && (gettype($details[$attribute]) == gettype($customer->$attribute)))
            {
                $customer->$attribute = $details[$attribute];
            }
        }

        // Customer locale.
        $customer->locale = new \stdClass;
        $customer->locale->id = '';
        $customer->locale->name = '';
        $customer->locale->language = '';
        $customer->locale->language_name = '';
        $customer->locale->script = '';

        if (!Localization::checkLocaleInSupportedLocales($customer->language))
        {
            $customer->language = Localization::getCurrentLocale();
            $customer->locale->id = Localization::getCurrentLocale() .'-CA';
            $customer->locale->name = Localization::getCurrentLocaleNativeReading();
            $customer->locale->language = Localization::getCurrentLocale();
            $customer->locale->language_name = Localization::getCurrentLocaleName();
            $customer->locale->script = Localization::getCurrentLocaleScript();
        }

        // Fill in locale attributes.
        else
        {
            foreach (get_object_vars($customer->locale) as $attribute)
            {
                if (isset($locale[$attribute]) && strlen($locale[$attribute]))
                {
                    $customer->locale->$attribute = $locale[$attribute];
                }
            }
        }

        // Validate some fields.
        $customer->postcode = preg_replace('/[^a-z0-9\s]/i', '', $customer->postcode);

        return $customer;
    }
}

