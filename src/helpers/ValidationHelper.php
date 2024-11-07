<?php

/**
 * Helper to provide Function Keys Validation masks
 */

namespace MiniPaviFwk\helpers;

class ValidationHelper
{
    public const SOMMAIRE = 0b00000001;
    public const ANNULATION = 0b00000010;
    public const RETOUR = 0b00000100;
    public const REPETITION = 0b00001000;
    public const GUIDE = 0b00010000;
    public const CORRECTION = 0b00100000;
    public const SUITE = 0b01000000;
    public const ENVOI = 0b10000000;

    public const ALL = 0b11111111;

    public const FORM_ALL = 0b10011001;     // SOMMAIRE | REPETITION | GUIDE | ENVOI
}
