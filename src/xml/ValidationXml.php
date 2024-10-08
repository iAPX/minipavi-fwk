<?php

/**
 * Extract keys to be validated from XML page element
 */

namespace MiniPaviFwk\xml;

class ValidationXml
{
    /**
     * Extract keys to be validated from XML page element
     */

    public static function validationKeys(\SimpleXMLElement $page): array
    {
        $validations = $page->entree->validation;
        trigger_error("Validation XmL : " . print_r($validations, true), E_USER_NOTICE);
        $keys = [];
        foreach ($validations as $validation) {
            trigger_error("1 Validation XmL : " . print_r($validation, true), E_USER_NOTICE);
            $keys[] = (string) $validation["touche"];
        }
        return $keys;
    }
}
