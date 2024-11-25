<?php

/**
 * Converts JPEG or PNG images to semi-graphic Alphamosaic Videotex Stream.
 *
 * Usage: php ./image-converter.php <lines> <columns> <image filename> <videotex filename>
 *
 * Lines: number of lines of the rendered image in the Minitel screen
 * Columns: number of columns of the rendered image in the Minitel screen
 * Image Filename: JPEG or PNG imaage filename (.jpg, .jpeg, .png, etc.)
 * Videotex Filename: Videotex output filename (usually .vdt)
 */

// Protection against Web requests
require_once "xmlint-import/helpers/no_web.php";

require_once __DIR__ . "/../../vendor/ludosevilla/minipavicli/MiniPaviCli.php";
require_once __DIR__ . "/../helpers/VideotexHelper.php";
require_once __DIR__ . "/../helpers/ImageHelper.php";

// Define a usage function
function printUsage(array $argv)
{
    $execName = $argv[0];
    echo "Usage: php $execName <lines> <columns> <image-filename> <videotex-filename>\n";
    echo "\n";
    echo "Lines: number of lines of the rendered image in the Minitel screen\n";
    echo "Columns: number of columns of the rendered image in the Minitel screen\n";
    echo "Image Filename: JPEG or PNG image filename (.jpg, .jpeg, .png, etc.)\n";
    echo "Videotex Filename: Videotex output filename (usually .vdt)\n";
    exit(1);
}

// Validate the number of arguments
if ($argc !== 5) {
    echo "Error: Invalid number of arguments.\n";
    printUsage($argv);
}

// Assign arguments to variables
$lines = $argv[1];
$columns = $argv[2];
$imageFilename = $argv[3];
$outputFilename = $argv[4];

// Basic parameter validation
if (!is_numeric($lines) || !is_numeric($columns) || $lines < 1 || $lines > 24 || $columns < 1 || $columns > 40) {
    echo "Error: Lines and columns must be numeric values [1..24] & [1..40].\n";
    exit(1);
}

if (!file_exists($imageFilename)) {
    echo "Error: Image file '{$imageFilename}' does not exist.\n";
    exit(1);
}

if (file_exists($outputFilename)) {
    echo "Error: Output file '{$outputFilename}' does exist.\n";
    exit(1);
}

// Convert the image to Alphamosaic Videotex and output it
$image = imagecreatefromstring(file_get_contents($imageFilename));

list($videotex_output, $lignes, $cols) = \MiniPaviFwk\helpers\ImageHelper::imageToAlphamosaic($image, $lines, $columns);
file_put_contents($outputFilename, $videotex_output);

echo "Image converted to Alphamosaic Videotex successfully, $lignes lignes x $cols cols, on $outputFilename file.\n";
