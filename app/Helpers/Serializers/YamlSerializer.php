<?php

namespace App\Helpers\Serializers;

use Symfony\Component\Yaml\Yaml;

class YamlSerializer{

    /**
     * Function that turns a php array into a yaml-serialized string.
     *
     * @param $data The php array to serialize
     *
     * @return string The yaml-serialized data
    */
    public static function serialize (array $data): string{
        $yaml = Yaml::dump($data, 10, 2);

        return $yaml;
    }
}
