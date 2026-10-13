<?php

namespace App\Helpers\Serializers;

use DOMDocument;
use SimpleXMLElement;

class XmlSerializer{
    // if is array => recursive
    // else,
    /*
    *<response>
    *   <switches>
    *       <switch>
    *        <switch_id>1</switch_id>
    *        <name>Cherry MX Red</name>
    *        <type>Linear</type>
    *       </switch>
    *       <switch>
    *        <switch_id>2</switch_id>
    *        <name>Cherry MX Blue</name>
    *        <type>Clicky</type>
    *       </switch>
    *   </switches>
    *</response>
    */
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
