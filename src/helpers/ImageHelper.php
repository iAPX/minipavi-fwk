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
    public static function imageToAlphamosaic(
        \GDImage $image,
        int $lignes,
        int $cols,
        bool $relative = true,
        ?int $startLigne = null,
        ?int $startCol = null
    ): array {
        $width = imagesx($image);
        $height = imagesy($image);
        $videotex = new VideotexHelper();

        imagefilter($image, IMG_FILTER_GRAYSCALE);

        // Scale it
        $imageRatio = $width / $height;
        $targetHeight = $lignes * 3;
        $targetWidth = $cols * 2;
        $targetRatio = $targetWidth / $targetHeight;
        if ($targetRatio > $imageRatio) {
            $newHeight = $targetHeight;
            $newWidth = ceil($targetHeight * $imageRatio);
        } else {
            $newWidth = $targetWidth;
            $newHeight = ceil($targetWidth / $imageRatio);
        }

        // return [$imageRatio . "/" . $targetRatio . "/" . $newWidth . "x" . $newHeight, 0, 0];
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $output = self::imageToVideotex($resizedImage, $relative, $startLigne, $startCol);

        // Free memory
        imagedestroy($image);
        imagedestroy($resizedImage);

        // On évite les doublons, généralement 0 ou 63 (vide ou plein).
        $output = self::repeatCars($output);

        return [$output, ceil($newHeight / 3.0), ceil($newWidth / 2.0)];
    }

    private static function imageToVideotex(
        \GDImage $image,
        bool $relative = true,
        ?int $startLigne = null,
        ?int $startCol = null
    ): string {
        $output = VDT_G1;
        $width = ceil(imagesx($image) / 2);
        $height = ceil(imagesy($image) / 3);

        // We don't know the actual colour attributes
        $textColour = -1;
        $backgroundColour = -1;

        for ($ligne = 0; $ligne < $height; $ligne++) {
            if (!$relative) {
                // We position and reset our colours
                $output .= MiniPaviCli::setPos($startCol, $startLigne + $ligne) . VDT_G1;
                $textColour = 7;
                $backgroundColour = 0;
            }
            for ($col = 0; $col < $width; $col++) {
                list($car, $textColour, $backgroundColour) = self::imagePartToMosaic(
                    $image,
                    $col * 2,
                    $ligne * 3,
                    $textColour,
                    $backgroundColour
                );
                $output .= $car;
            }
            if ($relative && $width < 40 && $ligne < $height - 1) {
                // Fill up to 40 cars, with BLACK alphamosaic spaces, except for last line
                if ($backgroundColour !== 0) {
                    // Background should always be 0 for the space to be exploitable in text mode
                    $output .= "\x1b\x50";
                    $backgroundColour = 0;
                }
                $output .= str_repeat(" ", 40 - $width);
            }
        }

        return $output;
    }

    private static function imagePartToMosaic(
        \GDImage $image,
        int $x,
        int $y,
        int $oldTextColour,
        int $oldBackgroundColour
    ): array {
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

        // 2-Median of the values, @TODO better it by creating 2 groups
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
        $newTextColour = self::gammaCorrection($high_value);
        $newBackgroundColour = self::gammaCorrection($low_value);

        list($coloredChar,$setTextColour, $setBackgroundColour) = self::optimizedColourChar(
            $character,
            $newTextColour,
            $newBackgroundColour,
            $oldTextColour,
            $oldBackgroundColour
        );

        return [$coloredChar, $setTextColour, $setBackgroundColour];
    }

    private static function optimizedColourChar(
        int $value,
        int $newTextColour,
        int $newBackgroundColour,
        int $oldTextColour,
        int $oldBackgroundColour
    ): array {
        $colours = '';

        $setTextColour = false;
        $setBackgroundColour = false;

        if ($newTextColour === $newBackgroundColour) {
            if ($newTextColour === $oldTextColour) {
                // Use old text colour if correct
                $value = 63;
            } else {
                // Use background colour and later optimizations on value 0.
                $value = 0;
            }
        }
        if ($value === 0) {
            // All background colour
            if ($newBackgroundColour === $oldTextColour) {
                $value === 63;
            } elseif ($newBackgroundColour !== $oldBackgroundColour) {
                $colours = "\x1B" . chr(80 + $newBackgroundColour);
                $setBackgroundColour = $newBackgroundColour;
            }
        } elseif ($value === 63) {
            // All text (foreground) colour
            if ($newTextColour === $oldBackgroundColour) {
                $value === 0;
            } elseif ($newTextColour !== $oldTextColour) {
                $colours = "\x1B" . chr(64 + $newTextColour);
                $setTextColour = $newTextColour;
            }
        } else {
            // A mix of both background and text colours
            if ($newTextColour === $oldBackgroundColour || $newBackgroundColour === $oldTextColour) {
                // At least one colour we won't set!
                $tmp = $newTextColour;
                $newTextColour = $newBackgroundColour;
                $newBackgroundColour = $tmp;
                $value = $value ^ 63;  // We invert it ;)
            }
            if ($newTextColour !== $oldTextColour && $value !== 0) {
                $colours .= "\x1B" . chr(64 + $newTextColour);
                $setTextColour = $newTextColour;
            }

            if ($newBackgroundColour !== $oldBackgroundColour && $value !== 63) {
                $colours .= "\x1B" . chr(80 + $newBackgroundColour);
                $setBackgroundColour = $newBackgroundColour;
            }
        }

        return [
            $colours . chr(32 + $value),
            $setTextColour !== false ? $setTextColour : $oldTextColour,
            $setBackgroundColour !== false ? $setBackgroundColour : $oldBackgroundColour
        ];
    }

    private static function gammaCorrection(float $luminance): int
    {
        // Because luminances are not linearly represented by colours 0 .. 7
        $luminances = [0, 4, 1 , 5, 2, 6, 3, 7];

        // This is the real trick, you have to do a violent gamma correction! That will do the job!
        $key_values = [6.18, 6.70, 7.10, 7.40, 7.62, 7.78, 7.90, 8.0];
        foreach ($key_values as $key => $value) {
            if ($luminance <= $value) {
                return $luminances[$key];
            }
        }
        // Fallback
        return 7;
    }

    private static function repeatCars(string $output): string
    {
        $cars = ["\x20", "\x5F"];
        foreach ($cars as $car) {
            while (($p = strpos($output, $car . $car . $car . $car)) !== false) {
                // take the length of the pattern
                $length = 0;
                while (substr($output, $p + $length, 1) === $car) {
                    $length++;
                }

                // First character
                $repeated = $car;
                $repetitions = $length - 1;

                while ($repetitions >= 63) {
                    $repeated .= VDT_REP . chr(64 + 63);
                    $repetitions -= 63;
                }
                if ($repetitions > 1) {
                    $repeated .= VDT_REP . chr(64 + $repetitions);
                } elseif ($repetitions === 1) {
                    $repeated .= $car;
                }
                $output = substr($output, 0, $p) . $repeated . substr($output, $p + $length);
            }
        }
        return $output;
    }
}
