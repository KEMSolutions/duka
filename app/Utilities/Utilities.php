<?php namespace App\Utilities;

use Localization;

class Utilities
{
    public function __construct()
    {
        // Build the cache namespace.
        $this->cacheNamespace = Localization::getCurrentLocale() .'.utilities.';
    }

    /**
     * Returns a list of countries, sorted by country code.
     *
     * @return array    Countries, sorted by code.
     */
    public function getCountryList() {
        return include __DIR__ .'/Countries/'. Localization::getCurrentLocale() .'.php';
    }


    /**
     * Returns a color brighter or darker according to a base color (hex formatted).
     *
     * Courtesy of http://stackoverflow.com/a/11951022
     * @param $hex
     * @param $steps
     * @return string
     */
    public function adjustBrightness($hex, $steps) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

    /**
     * Returns an image path according to the width, height, mode and source entered as arguments.
     *
     *
     * @param $width
     * @param $height
     * @param $mode
     * @param $source
     * @return mixed
     */
    public static function setImageSizeAndMode($width, $height, $mode, $source)
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

        return str_replace(['{width}', '{height}', '{mode}'], [$width, $height, $mode], $source);
    }
}

