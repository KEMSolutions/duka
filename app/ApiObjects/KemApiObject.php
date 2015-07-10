<?php namespace App\ApiObjects;

use Log;
use Cache;
use KemAPI;
use Products;
use Localization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

abstract class KemApiObject
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
    protected $slugInvalidCharacters = '/[^a-z0-9_-]/i';

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
     * @return object               Requested object.
     */
    public function get($id, $requestParams = [])
    {
        // Check that $id is either a valid number or a valid slug.
        if ((is_numeric($id) && $id < 0) || preg_replace($this->slugInvalidCharacters, '', $id) != $id) {
            return $this->badRequest('Invalid identifier [req: '. $this->baseRequest .'].');
        }

        // Retrieve object from cache, or make an API call.
        if (!$object = Cache::get($this->cacheNamespace . $id))
        {
            $object = KemAPI::get($this->baseRequest .'/'. $id, $requestParams);

            // Check for errors.
            if (!$object ||
                (is_object($object) && property_exists($object, 'error')) ||
                (!is_object($object) && !is_array($object))) {
                return $object;
            }

            // Cache result.
            $this->cache($object, $id);

            // Look for products to cache within results.
            $this->findAndCache($object);
        }

        return $object;
    }

    /**
     * Caches an object. This method can be overridden in child classes to reflect whatever makes sense.
     *
     * @param object $object    Object to be cached.
     * @param mixed $requestID  Original ID used to make API call.
     */
    protected function cache($object, $requestID = null)
    {
        // Cache object by ID and by slug.
        $expires = Carbon::now()->addHours(3);
        $requestID = $requestID ?: $object->id;
        Cache::put($this->cacheNamespace . $requestID, $object, $expires);
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
                Log::debug('Skipping object...');
                Log::debug('Is empty? '. (empty($item) ? 'yes' : 'no'));
                Log::debug('Is ID set? '. (isset($item->id) ? 'yes' : 'no'));
                Log::debug('Is ID empty? '. (empty($item->id) ? 'yes' : 'no'));
                continue;
            }

            Log::info('Caching "'. $namespace . $item->id .'" until "'. $expires .'"');
            Cache::put($namespace . $item->id, $item, $expires);
            if (isset($item->slug) && strlen($item->slug)) {
                Cache::put($namespace . $item->slug, $item, $expires);
            }
        }
    }

    /**
     * @return string Namespace to be used with cache.
     */
    public function getCacheNamespace() {
        return $this->cacheNamespace;
    }

    /**
     * Shortcut for returning a "Bad Request" response.
     *
     * @param string $msg   Optional message to pass on.
     * @return mixed        Bad request response.
     */
    protected function badRequest($msg = 'Bad Request.') {
        return JsonResponse::create(['status' => 400, 'error' => $msg], 400)->getData();
    }
}
