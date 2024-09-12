<?php

namespace helpers\xml;

class ValidationXml
{
    /**
     * Extract keys to be validated from XML page element
     */

    public static function validationKeys(\SimpleXMLElement $page): array
    {
        // @TODO extract and interpret validations
        $validations = $page->entree->validation;
        DEBUG && trigger_error("Validation XmL : " . print_r($validations, true));
        $keys = [];
        foreach ($validations as $validation) {
            DEBUG && trigger_error("1 Validation XmL : " . print_r($validation, true));
            $keys[] = (string) $validation["touche"];
        }
        return $keys;
    }
}
