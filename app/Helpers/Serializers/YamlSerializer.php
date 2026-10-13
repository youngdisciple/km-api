<?php

namespace App\Helpers\Serializers;

use Symfony\Component\Yaml\Yaml;

class YamlSerializer{

    public static function serialize (array $data): string{
        $yaml = Yaml::dump($data, 10, 2);

        return $yaml;
    }
}
