<?php

/**
 * Provides validation for Minitel Function Keys
 */

namespace MiniPaviFwk;

use MiniPaviFwk\controllers\VideotexController;

class Validation
{
    private int $keyMask = 0;

    private const SOMMAIRE = 1;
    private const ANNULATION = 2;
    private const RETOUR = 4;
    private const REPETITION = 8;
    private const GUIDE = 16;
    private const CORRECTION = 32;
    private const SUITE = 64;
    private const ENVOI = 128;

    private const KEY_VALUES = [
        'SOMMAIRE' => self::SOMMAIRE,
        'ANNULATION' => self::ANNULATION,
        'RETOUR' => self::RETOUR,
        'REPETITION' => self::REPETITION,
        'GUIDE' => self::GUIDE,
        'CORRECTION' => self::CORRECTION,
        'SUITE' => self::SUITE,
        'ENVOI' => self::ENVOI,
    ];

    public function __construct(VideotexController $controller)
    {
        trigger_error("Validation Touche __construct()", E_USER_NOTICE);

        // Identify methods that start with 'choix' or 'touche' to add the key to the key_mask
        $methods = get_class_methods($controller);
        foreach ($methods as $method) {
            trigger_error("Validation method : $method", E_USER_NOTICE);
            if (mb_substr($method, 0, 6) === 'touche') {
                // touche{Touchname}() on an single line input area
                $touche = mb_strtoupper(mb_substr($method, 6));
                if (array_key_exists($touche, self::KEY_VALUES)) {
                    trigger_error("Validation method touche : $touche - $method", E_USER_NOTICE);
                    $this->keyMask |= self::KEY_VALUES[$touche];
                }
            } elseif (mb_substr($method, 0, 13) === 'messageTouche') {
                // touche{Touchname}() on an single line input area
                $touche = mb_strtoupper(mb_substr($method, 13));
                if (array_key_exists($touche, self::KEY_VALUES)) {
                    trigger_error("Validation method messageTouche : $touche - $method", E_USER_NOTICE);
                    $this->keyMask |= self::KEY_VALUES[$touche];
                }
            } elseif (mb_substr($method, 0, 5) === 'choix') {
                // choix{Saisie}{Touchname}()
                $choix = mb_strtoupper($method);
                foreach (self::KEY_VALUES as $touche => $value) {
                    if (mb_substr($choix, -strlen($touche)) === $touche) {
                        trigger_error("Validation method touche : $touche - $method", E_USER_NOTICE);
                        $this->keyMask |= $value;
                        break;
                    }
                }
            }
        }

        // Handle keywords validation keys
        $this->addValidKeys($controller->keywordHandler->validationKeys());

        trigger_error("Validation Touche __construct() value : " . $this->keyMask, E_USER_NOTICE);
    }


    public function addValidKeys(array $keys)
    {
        trigger_error("Validation Touche addValidKeys() : " . print_r($keys, true), E_USER_NOTICE);
        $this->keyMask |= $this->createKeyMask($keys);
    }


    public function getKeyMask(): int
    {
        trigger_error("Validation value : $this->keyMask", E_USER_NOTICE);
        return $this->keyMask;
    }

    private function createKeyMask(array $keys)
    {
        $key_mask = 0;
        foreach ($keys as $key) {
            if (is_string($key)) {
                // We autorise to use Key names, case independent
                $key_mask |= self::KEY_VALUES[mb_strtoupper($key)];
            } else {
                $key_mask |= $key;
            }
        }
        return $key_mask;
    }
}
