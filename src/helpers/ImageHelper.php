<?php

/**
 * ImageHelper offers services to convert images (JPEG, PNG, etc.) to Minitel screen
 *
 * - Only alphamosaic/semi-graphic supported now
 */

namespace MiniPaviFwk\helpers;

use MiniPavi\MiniPaviCli;
use MiniPaviFwk\helpers\VideotexHelper;

class ImageHelper
{
    public static function imageToAlphamosaic(\GDImage $image, int $lignes, int $cols): array
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $videotex = new VideotexHelper();

        // Decontrasté, put in greyscale
        imagefilter($image, IMG_FILTER_CONTRAST, -50);
        imagefilter($image, IMG_FILTER_GRAYSCALE);

        // Scale it
        $newHeight = $lignes * 3;
        $newWidth = $cols * 2;
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $output = self::imageToVideotex($resizedImage);

        // Free memory
        imagedestroy($image);
        imagedestroy($resizedImage);
        
        return [$output, $lignes, $cols];
    }

    private static function imageToVideotex(\GDImage $image): string
    {
        $output = VDT_G1;
        $width = ceil(imagesx($image) / 2);
        $height = ceil(imagesy($image) / 3);

        $textColour = 7;
        $backgroundColour = 0;

        for($ligne = 0; $ligne < $height; $ligne++) {
            for($col = 0; $col < $width; $col++) {
                list($car, $textColour, $backgroundColour)= self::imagePartToMosaic($image, $col * 2, $ligne *3, $textColour, $backgroundColour);
                $output .= $car;
            }
        }

        return $output;
    }

    private static function imagePartToMosaic(\GDImage $image, int $x, int $y, int $oldTextColour, int $oldBackgroundColour) : array
    {
        // To transport the colour informations
        $setTextColour = $oldTextColour;
        $setBackgroundColour = $oldBackgroundColour;

        // 1-Gets the values in logarithm representation, base 2
        $values = [];
        for ($dy = 0; $dy < 3; $dy++) {
            for ($dx = 0; $dx < 2; $dx++) {
                $values[] = log(imagecolorat($image, $x + $dx, $y + $dy) & 0xFF, 2);
            }
        }

        // 2-Median of the values
        // @TODO need to regroup values first, through array_keys ceil($values[]) ;) and use of a true median 4, 4, 4, 4, 4, 0 : median is not 4, it's 2!
        sort($values, SORT_NUMERIC);
        $median = ($values[2] + $values[3]) / 2.0;

        // 2-Choose 2 representations
        $low_value = ($values[0] + $values[1] + $values[2]) / 3.0;
        $high_value = ($values[3] + $values[4] + $values[5]) / 3.0;

        // 3 - prepare the character
        $character = 0;
        $value = 1;
        for ($dy = 0; $dy < 3; $dy++) {
            for ($dx = 0; $dx < 2; $dx++) {
                $grey = log(imagecolorat($image, $x + $dx, $y + $dy) & 0xFF, 2);
                if ($grey >= $median) {
                    $character += $value;
                }
                $value *= 2;
            }
        }

        // 4-Set text and background colours, optimized!
        // $colours = "\x1B" . chr(64 + self::gammaCorrection($high_value)) . "\x1B" . chr(80 + self::gammaCorrection($low_value));
        $newTextColour = self::gammaCorrection($high_value);
        $newBackgroundColour = self::gammaCorrection($low_value);
        if ($newTextColour === $oldBackgroundColour || $newBackgroundColour === $OldTextColour) {
            // At least one colour we won't set!
            $tmp = $newTextColour;
            $newTextColour = $newBackgroundColour;
            $newBackgroundColour = $tmp;
            $character = $character ^ 63;  // We invert it ;)
        }
        $colours = '';

        if ($newTextColour !== $oldTextColour && $value !== 0) {
            $colours .= "\x1B" . chr(64 + $newTextColour);
            $setTextColour = $newTextColour;
        }

        if ($newBackgroundColour !== $oldBackgroundColour && $value !== 63) {
            $colours .= "\x1B" . chr(80 + $newBackgroundColour);
            $setBackgroundColour = $newBackgroundColour;
        }

        return [$colours . chr(32 + $character), $setTextColour, $setBackgroundColour];
    }

    private static function gammaCorrection(float $luminance): int
    {
        // This is the real trick, you have to do a brutal gamma correction! That will do the job!
        $key_values = [6.18, 6.70, 7.10, 7.40, 7.62, 7.78, 7.90, 8.0];
        foreach ($key_values as $key => $value) {
            if ($luminance <= $value) {
                return $key;
            }
        }
        return 7;
    }

}
