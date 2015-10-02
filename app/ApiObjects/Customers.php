<?php namespace App\ApiObjects;

use Log;
use Auth;
use Cache;
use Crypt;
use KemAPI;
use Validator;
use Localization;

use Carbon\Carbon;
use App\Models\Customer;

class Customers extends BaseObject
{
    /**
     * Regex for validating base64-encoded emails.
     */
    protected $slugInvalidCharacters = '/[^a-z0-9=\/+]/i';

    /**
     * Endpoint for address requests to API.
     */
    public $addressRequest = 'addresses';

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
        $result = KemAPI::post($this->baseRequest, $customer);

        // Return instance of App\Models\Customer.
        return static::isError($result) ? $result : new Customer((array) $result);
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

        $headers = ['Accept-Language' => $customer->locale['id']];

        // Update customer record.
        $result = (array) KemAPI::put($this->baseRequest .'/'. $customer->id, $customer, $headers);

        // Replace cached data.
        if (!static::isError($result))
        {
            $expiresAt = Carbon::now()->addHours(3);
            $keyAppend = '.'. json_encode(['embed' => 'addresses']);
            Cache::put($this->getCacheKey($result['id'] . $keyAppend), $result, $expiresAt);
            Cache::put($this->getCacheKey($result['email'] . $keyAppend), $result, $expiresAt);
        }

        // Return instance of App\Models\Customer.
        return static::isError($result) ? $result : new Customer($result);
    }

    /**
     * Updates an address or creates a new one.
     *
     * @param int $id
     * @param array $details
     * @return \stdClass
     */
    public function addOrCreateAddress($id, array $details)
    {
        // Make sure we have valid customer details.
        if (!array_key_exists('customer', $details) || !is_array($details['customer'])) {
            $details['customer'] = ['id' => Auth::id()];
        }

        // Update address.
        $id = (int) $id;
        if ($id > 0) {
            $result = KemAPI::put($this->addressRequest .'/'. $id, $details);
        }

        // Or create a new one.
        else {
            $result = KemAPI::post($this->addressRequest, $details);
        }

        return $result;
    }
    public function addAddress(array $details) {
        return $this->addOrCreateAddress(0, array_except($details, 'id'));
    }
    public function updateAddress(array $details) {
        return $this->addOrCreateAddress($details['id'], array_except($details, 'id'));
    }

    /**
     * Creates a validator object to validate customer details.
     *
     * @param array $details    Details to be validated.
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate(array $details)
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
     * Creates a validator object to validate an address.
     *
     * @param array $details    Details to be validated.
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateAddress(array $data)
    {
        return Validator::make($data, [
            'line1' => 'required|string',
            'line2' => 'string',
            'name' => 'string',
            'postcode' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string|min:2',
            'country' => 'required|string|length:2',
            'phone' => 'string'
        ]);
    }

    public function getDefault() {
        return Auth::guest() ? new Customer : Auth::user();
    }

    public function getDefaultAddress()
    {
        $customer = $this->getDefault();

        return count($customer->addresses) ? $customer->addresses[0] : (object) [
            'line1' => '',
            'line2' => '',
            'name' => $customer->name,
            'postcode' => $customer->postcode,
            'city' => '',
            'province' => 'QC',
            'country' => 'CA',
            'phone' => ''
        ];
    }
}
