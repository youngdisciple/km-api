<?php

namespace App\Helpers\Serializers;

use DOMDocument;
use SimpleXMLElement;

class XmlSerializer{
    private static function array_to_xml(array $data, SimpleXMLElement $xml, $parentKey = ''): void {
        foreach( $data as $key => $value) {
            $child = null;
            if(is_array($value)){
                //see if key is numerical
                $isNumerical = preg_match( "/^\d{1,9}$/", $key);
                if ($isNumerical) {
                    //defaults to item if parentKey is not a plural
                    $qualifiedName = 'item';

                    // if plural, qualifiedName has it's 's' removed
                    if ($parentKey[-1] == "s") {
                        $qualifiedName = substr($parentKey, 0, strlen($parentKey) - 1);
                    }

                    $child = $xml->addChild($qualifiedName);
                } else {
                    $child = $xml->addChild($key);
                }

                XmlSerializer::array_to_xml($value, $child, $isNumerical ? '' : $key);
            }else{
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }


    /**
    * Function that turns a php array into a xml-serialized string.
    *
    * @param array $data The data to be serialized
    *
    * @param $rootElement The root element that represents the php array, defaults to 'response'
    *
    * @return string The xml-serialized data
    */
    public static function serialize (array $data, string $rootElement = 'response'): string{
        $xml = new SimpleXMLElement("<$rootElement/>");
        XmlSerializer::array_to_xml($data, $xml);
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML();
    }
}
