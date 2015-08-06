<?php namespace App\ApiObjects;

use Log;
use Cache;
use KemAPI;
use Products;
use Localization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

abstract class BaseObject
{
    /**
     * @var string  Base API request for this object, e.g. "products" or "layouts".
     */
    public $baseRequest = '';

    /**
     * @var string  Current locale. Used for caching purposes.
     */
    public $locale = 'en';

    /**
     * @var string  Namespace used to cache individual objects, e.g. "en.api.products.1234".
     */
    public $cacheNamespace = 'api.';

    /**
     * @var string  Regular expression used to validate slug.
     */
    protected $slugInvalidCharacters = '/[^a-z0-9_\-]/i';

    /**
     * Constructor.
     *
     * @param string $baseRequest Base API request for this object, e.g. "products" or "layouts".
     */
    public function __construct($baseRequest)
    {
        // Store the base request.
        $this->baseRequest = $baseRequest;

        // Store the current locale.
        $this->locale = Localization::getCurrentLocale();

        // Build the cache namespace.
        $this->cacheNamespace = $this->locale .'.api.'. $this->baseRequest .'.';
    }

    /**
     * Retrieves a list of objects within the scope.
     *
     * @return array    Array of all defined objects in this scope.
     */
    public function all()
    {
        // Retrieve object from cache, or make an API call.
        if (!$object = Cache::get($this->cacheNamespace . 'list'))
        {
            $object = KemAPI::get($this->baseRequest);

            // Check for errors.
            if (!$object ||
                (is_object($object) && property_exists($object, 'error')) ||
                (!is_object($object) && !is_array($object))) {

                return $object;
            }

            // Cache result.
            $this->cache($object, 'list');

            // Look for products to cache within results.
            $this->findAndCache($object);
        }

        return $object;
    }

    /**
     * Retrieves an object from the API, the type of which is defined by $this->baseRequest.
     *
     * @param mixed $id             ID or slug of requested object.
     * @param array $requestParams  Parameters to include with API request.
     * @param int $expires          Hours to keep object in cache.
     * @return object               Requested object.
     */
    public function get($id, array $requestParams = [], $expires = 3)
    {
        // Check that $id is either a valid number or a valid slug.
        if ((is_numeric($id) && $id < 0) || preg_replace($this->slugInvalidCharacters, '', $id) != $id) {
            return $this->badRequest('Invalid identifier [req: ' . $this->baseRequest . '].');
        }

        // Validate request parameters.
        $requestParams = $this->validateRequestParams($requestParams);

        // Check to see if object has already been cached.
        $cacheKey = $id .'.'. json_encode($requestParams);
        if ($expires && $object = Cache::get($this->cacheNamespace . $cacheKey)) {
            return $object;
        }

        // If not, retrieve the data from the API. We use the response object so that
        // we may retrieve more information about the response later if necessary.
        $response = KemAPI::get($this->baseRequest .'/'. $id, $requestParams, true);

        // Catch any errors without throwing them back.
        if (!$response) {
            return $this->badRequest('Received null [req: ' . $this->baseRequest . '].');
        }

        $object = json_decode($response->getBody());

        // If we have an error, skip the cache and return the result.
        if ($this->isError($object) || $response->getStatusCode() != 200) {
            return $object;
        }

        // If we have a results set, try to add pagination information.
        if (isset($requestParams['page']) && isset($requestParams['per_page']))
        {
            $object->paginationLinks = $response->getHeader('Links');
            $object->paginationTotal = $response->getHeader('X-Total-Count');
        }

        // Cache result.
        if ($expires > 0)
        {
            $this->cache($object, $cacheKey, Carbon::now()->addHours($expires));

            // Look for products to cache within results.
            $this->findAndCache($object);
        }

        return $object;
    }

    /**
     * Formats and validates request parameters.
     *
     * @param array $params     Request parameters to format and validate.
     * @return array            Validated parameters.
     */
    public function validateRequestParams(array $params)
    {
        // The "embed" parameter is a comma-separated list of words.
        if (isset($params['embed']))
        {
            // Convert arrays to strings.
            if (is_array($params['embed']) && count($params['embed'])) {
                $params['embed'] = implode(',', $params['embed']);
            }

            // Ignore any other data type.
            elseif (!is_string($params['embed'])) {
                $params['embed'] = '';
            }
        }

        // The "filers" parameter is comma-separated list of words, which can be themselves separated by semi-colons.
        if (isset($params['filters']))
        {
            // Convert arrays to strings.
            if (is_array($params['filters']))
            {
                $filters = [];
                foreach ($params['filters'] as $key => $filter) {
                    $filters[] = $key .':'. (is_array($filter) ? implode(';', $filter) : $filter);
                }

                $params['filters'] = implode(',', $filters);
            }

            // Ignore any other data type.
            elseif (!is_string($params['filters'])) {
                $params['filters'] = '';
            }
        }

        // The "order" parameter can take one of 6 values.
        if (isset($params['order']) && !in_array($params['order'], ['name', '-name', 'price', '-price', 'added', '-added'])) {
            $params['order'] = '';
        }

        // The page parameters should be integers, and the per_page should not exceed 40.
        if (isset($params['page']) || isset($params['per_page']))
        {
            $params['page'] = max(1, (int) $params['page']);
            $params['per_page'] = max(1, min(40, (int) $params['per_page']));
        }

        // Sort alphabetically, to help with caching.
        ksort($params);

        return $params;
    }

    protected function sortBy($property, $array)
    {
        // Create temporary array.
        $temp = [];
        foreach ($array as $item)
        {
            if (!$item) {
                continue;
            }

            $temp[$item->{$property}] = $item;
        }

        ksort($temp, SORT_NATURAL);

        return $temp;
    }

    /**
     * Caches an object. This method can be overridden in child classes to reflect whatever makes sense.
     *
     * @param object $object    Object to be cached.
     * @param mixed $requestID  Original ID used to make API call.
     * @param object $expires   \Carbon\Carbon object representing when the cache should expire.
     */
    protected function cache($object, $requestID = null, $expires = null)
    {
        // Cache object by ID.
        $expires = $expires ?: Carbon::now()->addHours(3);
        $requestID = $requestID ?: $object->id;
        Cache::put($this->cacheNamespace . $requestID, $object, $expires);

        // If the object has a slug, cache it under that name too.
        if (is_object($object) && property_exists($object, 'slug')) {
            Cache::put($this->cacheNamespace . $object->slug, $object, $expires);
        }

        Log::info('Caching "'. $this->cacheNamespace . $requestID .'" until "'. $expires .'"');
    }

    /**
     * Looks for items to cache within a given set of results. This method can be overridden
     * in child classes to reflect whatever makes sense.
     *
     * @param object $object Object in question.
     */
    protected function findAndCache($object)
    {
        // Look for products to cache.
        if (isset($object->products) && count($object->products)) {
            $this->extractAndCache($object->products, Products::getCacheNamespace());
        }
    }

    /**
     * Caches each element within an array (e.g. products).
     *
     * @param array $list       Array of objects to be cached.
     * @param string $namespace Namespace to use for caching.
     */
    protected function extractAndCache($list, $namespace)
    {
        // Performance check.
        if (gettype($list) != 'array' && !($list instanceof Iterator)) {
            return;
        }

        // Cache each item in the list.
        $expires = Carbon::now()->addHours(3);
        foreach ($list as $item)
        {
            if (empty($item) || !isset($item->id) || empty($item->id) || Cache::has($namespace . $item->id)) {
                empty($item) ? Log::debug('Skipping empty object...') : null;
                (!isset($item->id) || empty($item->id)) ? Log::debug('Skipping object without ID...') : null;
                continue;
            }

            Log::debug('Caching "'. $namespace . $item->id .'" until "'. $expires .'"');
            Cache::put($namespace . $item->id, $item, $expires);
            if (isset($item->slug) && strlen($item->slug)) {
                Cache::put($namespace . $item->slug, $item, $expires);
            }
        }
    }

    /**
     * @return string   Namespace to be used with cache.
     */
    public function getCacheNamespace() {
        return $this->cacheNamespace;
    }

    public function isError($obj)
    {
        // Check that we have an object.
        if (!$obj || (!is_array($obj) && !is_object($obj))) {
            return true;
        }

        // Cast to array and check known keys for errors.
        $obj = (array) $obj;

        if (isset($obj['error'])) {
            return true;
        }

        return false;
    }

    /**
     * Shortcut for returning a "Bad Request" response.
     *
     * @param string $msg   Optional message to pass on.
     * @return mixed        Bad request response.
     */
    protected function badRequest($msg = 'Bad Request.') {
        return ['status' => 400, 'error' => $msg];
//        return JsonResponse::create(['status' => 400, 'error' => $msg], 400)->getData();
    }
}

