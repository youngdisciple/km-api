<?php

namespace App\Helpers;

use App\Exceptions\HttpNotAcceptableException;

class AcceptHeaderParser{

    /*
    * Function that parses the HTTP Accept header and determines the best matching format.
    *
    * @param $acceptHeader The needle
    *
    * @param $supportedTypes The haystack
    *
    * @return ?string The matched format or null if not found.
    */
    public static function getBestMatch(string $acceptHeader, array $supportedTypes): ?string
    {
        // early return 'application/json' if no accept field
        if ($acceptHeader === '') {
            return 'application/json';
        }

        $supportedTypes = array_map('strtolower', $supportedTypes);

        // 1. Parses the Accept header string into individual media types with their quality factors.
        $headersAndQ = [];
        foreach(explode(',', $acceptHeader) as $mediaType){
            // $accept e.g. "application/xml;x=sadf;q=0.9"
            // we need to account for extra fields too
            $fields = explode(';', $mediaType);
            $type = strtolower(trim(array_shift($fields)));

            $q = 1.0;
            foreach($fields as $field){
                if(str_contains($field, 'q=')) {
                    $q = floatval(trim(explode('=', $field)[1]));
                }
            }

            $headersAndQ[$type] = $q;
        }

        // 2. Sorts them by quality factor in descending order (highest quality first).
        arsort($headersAndQ);

        // 3. Returns the first media type that matches one of the supported types.

        foreach ($headersAndQ as $type => $q) {
            if ($type === '*/*') {
                if (!empty($supportedTypes)){
                    return $supportedTypes[0];
                }

                continue;
            }

            if (str_ends_with($type, '/*')) {
                $prefix = substr($type, 0, -1); // e.g. application/* => application/
                foreach($supportedTypes as $supported) {
                    if (str_starts_with($supported, $prefix)) {
                        return $supported;
                    }
                }

                continue;
            }

            if (in_array(strtolower($type), $supportedTypes)) {
                return $type;
            }
        }

        // 4. Returns null if no match is found.
        return null;
    }
}
