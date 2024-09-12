<?php

/**
 * Provides validation for Minitel Function Keys
 */

namespace helpers;

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

    public function __construct(\helpers\VideotexController $controller)
    {
        DEBUG && trigger_error("Validation Touche __construct() : " . print_r($keys, true));

        // Identify methods that start with 'choix' or 'touche' to add the key to the key_mask
        $methods = get_class_methods($controller);

        foreach ($methods as $method) {
            DEBUG && trigger_error("Validation method : $method");
            if (substr($method, 0, 6) === 'touche') {
                // touche{Touchname}()
                $touche = strtoupper(substr($method, 6));
                if (array_key_exists($touche, self::KEY_VALUES)) {
                    DEBUG && trigger_error("Validation method touche : $touche - $method");
                    $this->keyMask |= self::KEY_VALUES[$touche];
                }
            } elseif (substr($method, 0, 5) === 'choix') {
                // choix{Saisie}{Touchname}()
                $choix = strtoupper($method);
                foreach (self::KEY_VALUES as $touche => $value) {
                    if (substr($choix, -strlen($touche)) === $touche) {
                        DEBUG && trigger_error("Validation method touche : $touche - $method");
                        $this->keyMask |= $value;
                        break;
                    }
                }
            }
        }

        // Handle keywords validation keys
        $this->addValideKeys($controller->keywordHandler->validationKeys());

        DEBUG && trigger_error("Validation Touche __construct() value : " . $this->keyMask);
    }


    public function addValideKeys(array $keys)
    {
        DEBUG && trigger_error("Validation Touche addValideKeys() : " . print_r($keys, true));
        $this->keyMask |= $this->createKeyMask($keys);
    }


    public function getKeyMask(): int
    {
        DEBUG && trigger_error("Validation value : $this->keyMask");
        return $this->keyMask;
    }

    private function createKeyMask(array $keys)
    {
        $key_mask = 0;
        foreach ($keys as $key) {
            if (is_string($key)) {
                // We autorise to use Key names, case independent
                $key_mask |= self::KEY_VALUES[strtoupper($key)];
            } else {
                $key_mask |= $key;
            }
        }
        return $key_mask;
    }
}
