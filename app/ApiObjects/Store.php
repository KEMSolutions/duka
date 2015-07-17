<?php namespace App\ApiObjects;

class Store extends BaseObject
{
    public function __construct() { parent::__construct('store'); }

    /**
     * Shortcut for self::get('').
     *
     * @return object   Store details.
     */
    public function info() {
        return parent::get('');
    }

    public function logo($width = 200, $height = 60, $mode = 'fit')
    {
        // Format mode.
        if (is_array($mode))
        {
            $modes = [];
            foreach ($mode as $key => $value) {
                $modes[] = is_integer($key) ? $value : $key .':'. $value;
            }

            $mode = implode(',', $modes);
        }

        return str_replace(['{width}', '{height}', '{mode}'], [$width, $height, $mode], $this->info()->logo->url);
    }
}

