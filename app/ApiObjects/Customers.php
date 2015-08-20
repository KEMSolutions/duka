<?php namespace App\ApiObjects;

use Log;
use Auth;
use Cache;
use Crypt;
use KemAPI;
use Localization;

use Carbon\Carbon;
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

        // Embed the customer addresses while we're retrieving their details.
        // NOTE: any change in this parameter should be reflected in the "update" method
        // so that the cache can be cleared properly.
        $requestParams['embed'] = 'addresses';

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

    /**
     * Updates a customer record.
     *
     * @param \App\Models\Customer $customer
     * @param array $details
     * @param string $passwd
     * @return \App\Models\Customer|object
     */
    public function update(Customer $customer, array $details, $passwd = null)
    {
        // Update customer details.
        $customer->fill($details);

        if ($passwd) {
            $customer->metadata['password'] = bcrypt($passwd);
        }

        $customer->metadata['password'] = Crypt::encrypt($customer->metadata['password']);
        $customer->metadata = json_encode($customer->metadata);

        // Update customer record.
        $result = (array) KemAPI::put($this->baseRequest .'/'. $customer->id, $customer);

        // Replace cached data.
        if (!static::isError($result))
        {
            $expiresAt = Carbon::now()->addHours(3);
            $keyAppend = '.'. json_encode(['embed' => 'addresses']);
            Cache::put($this->getCacheKey($result['id'] . $keyAppend), $result, $expiresAt);
            Cache::put($this->getCacheKey($result['email'] . $keyAppend), $result, $expiresAt);
        }

        // Return instance of App\Models\Customer.
        return new Customer($result);
    }

    public function getDefault()
    {
        return Auth::guest() ? new Customer : Auth::user();
    }
}
