<?php

namespace Tests\Helpers;

class XmlTestHelper
{
    /**
     * Load and return the contents of an XML file.
     *
     * @param string $filename
     * @return string
     * @throws \Exception
     */
    public static function loadXmlFile($filename)
    {
        $path = __DIR__ . '/../data/' . $filename;

        if (!file_exists($path)) {
            throw new \Exception("File not found: " . $path);
        }

        return file_get_contents($path);
    }

    /**
     * Convert an array to XML.
     *
     * @param array $data
     * @return string
     */
    public static function arrayToXml(array $data)
    {
        $xml = new \SimpleXMLElement('<root/>');
        array_walk_recursive($data, function($value, $key) use ($xml) {
            $xml->addChild($key, htmlspecialchars($value));
        });

        return $xml->asXML();
    }
}
